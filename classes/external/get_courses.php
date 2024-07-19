<?php
// Write the external function description.
namespace local_course_recommendations_ws\external;

use core_course_category;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

class get_courses extends external_api
{
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters()
    {
        return new external_function_parameters([
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
                'category' => new external_value(PARAM_TEXT, 'course category'),
                'fullname' => new external_value(PARAM_TEXT, 'course name'),
                'shortname' => new external_value(PARAM_TEXT, 'course short name'),
                'lang' => new external_value(PARAM_TEXT, 'course language'),
                'summary' => new external_value(PARAM_TEXT, 'course summary'),
                'timemodified' => new external_value(PARAM_INT, 'course last modified time'),
            ])
        );
    }

    /**
     * The function that returns the courses
     * @param int $category_id the id of the parent category
     * @return array of courses
     */
    public static function execute($category_id)
    {
        if ($category_id != 0) {
            $category = core_course_category::get($category_id);
            $courses = $category->get_courses(
                array(
                    'recursive' => true),
                    'summary',
                );
            $result = array();
            foreach ($courses as $course) {
                $result[] = array(
                    'id' => $course->id,
                    'category' => $category->get_formatted_name(),
                    'fullname' => $course->fullname,
                    'shortname' => $course->shortname,
                    'lang' => $course->lang,
                    'summary' => $course->summary,
                    'timemodified' => $course->timemodified,
                );
            }
            return $result;
        } else {
            return array();
        }
    }

}
