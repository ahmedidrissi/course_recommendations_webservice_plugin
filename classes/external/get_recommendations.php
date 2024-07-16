<?php
// Write the external function description.
namespace local_course_recommendations_ws\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

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
            'lang' => new external_value(PARAM_TEXT, 'the language of the user')
        ]);
    }

    /**
     * Returns description of method result value
     * @return external_single_structure
     */
    public static function execute_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'course_id' => new external_value(PARAM_INT, 'the id of the course'),
                'course_name' => new external_value(PARAM_TEXT, 'the name of the course'),
                'course_description' => new external_value(PARAM_TEXT, 'the description of the course'),
                'course_image' => new external_value(PARAM_TEXT, 'the image of the course'),
                'course_url' => new external_value(PARAM_TEXT, 'the url of the course'),
            ])
        );
    }

    /**
     * The function that returns the recommendations
     * @param object $user the user object
     * @return array of course recommendations
     */
    public static function execute(
        // $user
    )
    {
        global $USER;

        // Validate the user object
        // $params = self::validate_parameters(self::execute_parameters(), ['user' => $user]);

        // Get the user information
        // $user_id = $params['user']['user_id'];
        // $institution = $params['user']['institution'];
        // $function = $params['user']['function'];
        // $lang = $params['user']['lang'];

        // Get the user context and validate it
        // $context = context_user::instance($USER->id);
        // self::validate_context($context);
        // require_capability('moodle/course:view', $context);

        // Get the course recommendations. For now, we will return dummy data.
        $recommendations = array();
        for ($i = 0; $i < 5; $i++) {
            $course_id = $i + 1;
            $course_name = 'Course ' . $course_id;
            $course_description = 'This is the description of course ' . $course_id;
            $course_image = 'https://www.example.com/course' . $course_id . '.png';
            $course_url = 'https://www.example.com/course' . $course_id;

            $recommendations[] = [
                'course_id' => $course_id,
                'course_name' => $course_name,
                'course_description' => $course_description,
                'course_image' => $course_image,
                'course_url' => $course_url,
            ];
        }

        return $recommendations;
    }
}
