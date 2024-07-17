<?php
// Write the external function description.
namespace local_course_recommendations_ws\external;

use core_course_category;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;
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
            'lang' => new external_value(PARAM_TEXT, 'the language of the user'),
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
                'timemodified' => new external_value(PARAM_INT, 'course last modified time'),
                'category' => new external_value(PARAM_TEXT, 'course category'),
                'image' => new external_value(PARAM_TEXT, 'course image'),
            ])
        );
    }

    /**
     * The function that returns the recommendations
     * @param int $user_id the id of the user
     * @param string $institution the institution of the user
     * @param string $function the function of the user
     * @param string $lang the language of the user
     * @return array of course recommendations
     */
    public static function execute(
        $user_id,
        $institution,
        $function,
        $lang,
        $category_id
    )
    {
        global $USER, $DB;

        // Validate the user_id
        if ($user_id != $USER->id) {
            throw new \moodle_exception('invalid_user_id', 'local_course_recommendations_ws');
        }

        // Validate the institution
        if ($institution == '') {
            throw new \moodle_exception('invalid_institution', 'local_course_recommendations_ws');
        }

        // Validate the function
        if ($function == '') {
            throw new \moodle_exception('invalid_function', 'local_course_recommendations_ws');
        }

        // Validate the lang
        if ($lang != 'en' && $lang != 'fr') {
            throw new \moodle_exception('invalid_lang', 'local_course_recommendations_ws');
        }

        // Get the user context and validate it
        // $context = context_user::instance($USER->id);
        // self::validate_context($context);
        // require_capability('moodle/course:view', $context);

        // Create an empty array to store the recommendations
        $recommendations = array();

        // Check if the category_id is set
        if($category_id) {
            // Get the top category
            $category = core_course_category::get($category_id);

            // Get the categories under the top category
            $categories = $category->get_children();

            foreach ($categories as $category) {
                // Get the subcategories of the category
                $subcategories = $category->get_children();

                foreach ($subcategories as $subcategory) {
                    // Get the courses under the subcategory
                    $courses = $subcategory->get_courses();

                    // Get top 3 courses
                    $courses = array_slice($courses, 0, 3);

                    // Add the courses to the recommendations array
                    foreach ($courses as $course) {
                        $recommendations[] = [
                            'id' => $course->id,
                            'fullname' => $course->get_formatted_fullname(),
                            'shortname' => $course->get_formatted_shortname(),
                            'timemodified' => userdate($course->timemodified, '%d %B %Y'),
                            'category' => $category->get_formatted_name(),
                            'url' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(),
                            'image' => (new moodle_url('/theme/edash/pix/category.jpg'))->out(),
                        ];
                    }
                }

            }
        }

        // TODO: Send a request to the recommendation engine to get the recommendations

        // TODO: Validate the recommendations using context

        return $recommendations;
    }
}
