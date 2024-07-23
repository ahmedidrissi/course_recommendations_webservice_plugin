<?php
// Write the external function description.
namespace local_course_recommendations_ws\external;

use core_course_category;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;
use curl;
use moodle_url;


class get_recommendations extends external_api
{

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters()
    {
        return new external_function_parameters([
            'user_id' => new external_value(PARAM_INT, 'the id of the user'),
            'institution' => new external_value(PARAM_TEXT, 'the institution of the user'),
            'function' => new external_value(PARAM_TEXT, 'the function of the user'),
            'category_id' => new external_value(PARAM_INT, 'the id of the parent category'),
        ]);
    }

    /**
     * Returns description of method result value
     * @return core_course_list_element[]
     */
    public static function execute_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'id' => new external_value(PARAM_INT, 'course id'),
                'fullname' => new external_value(PARAM_TEXT, 'course name'),
                'shortname' => new external_value(PARAM_TEXT, 'course short name'),
                'category' => new external_value(PARAM_TEXT, 'course category'),
                'timemodified' => new external_value(PARAM_INT, 'course last modified time'),
            ])
        );
    }

    /**
     * The function that returns the recommendations
     * @param int $user_id the id of the user
     * @param string $institution the institution of the user
     * @param string $function the function of the user
     * @param int $category_id the id of the parent category
     * @return array of course recommendations
     */
    public static function execute(
        $user_id,
        $institution,
        $function,
        $category_id
    ) {
        global $USER;

        // Validate the user_id
        if ($user_id != $USER->id) {
            throw new \moodle_exception('invalid_user_id', 'local_course_recommendations_ws');
        }

        // Validate other parameters
        if ($institution == '' || $function == '') {
            throw new \moodle_exception('invalid_parameters', 'local_course_recommendations_ws');
        }

        // Get the user context and validate it
        // $context = context_user::instance($USER->id);
        // self::validate_context($context);
        // require_capability('moodle/course:view', $context);

        // Create an empty array to store the recommendations
        $recommendations = array();

        // Get the recommendations
        $recommendations = self::send_request($institution, $function, $category_id);

        // TODO: Validate the recommendations using context

        return $recommendations;
    }

    /**
     * Helper function to send a request to the recommendation engine
     * @param string $institution the institution of the user
     * @param string $function the function of the user
     * @param int $category_id the id of the parent category
     * @return array the recommendations
     */
    private static function send_request($institution, $function, $category_id)
    {
        global $CFG;

        // Get the token
        $token = self::get_token($CFG->MOODLE_USERNAME, $CFG->MOODLE_PASSWORD);

        // Flask app endpoint
        $url = $CFG->RECOMMENDER_BASE_URL . '/recommendations';

        // Define the language
        $language = 'en';
        if ($category_id == 2) {
            $language = 'fr';
        }

        // Prepare the data to be sent
        $data = array(
            'institution' => $institution,
            'function' => $function,
            'lang' => $language,
        );  

        // Create a new cURL resource
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if ($response === false) {
            throw new \Exception('cURL Error: ' . curl_error($ch));
        }

        // Decode the JSON response
        $response = json_decode($response, true);
        $recommendations = $response['recommendations'];

        // Close the cURL resource
        curl_close($ch);

        return $recommendations;
    }


    /**
     * Helper function to login to the recommendation engine and get the token
     * @param string $username
     * @param string $password
     * @return string the token
     */
    private static function get_token($username, $password)
    {
        global $CFG;
        
        // Flask app endpoint
        $url = $CFG->RECOMMENDER_BASE_URL . '/auth/token';

        // Prepare the data to be sent
        $data = array(
            'username' => $username,
            'password' => $password,
        );

        // Create a new cURL resource
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if ($response === false) {
            throw new \Exception('cURL Error: ' . curl_error($ch));
        }

        // Decode the JSON response
        $response = json_decode($response, true);
        $token = $response['token'];

        // Close the cURL resource
        curl_close($ch);

        return $token;
    }
}
