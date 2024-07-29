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
                'category_id' => new external_value(PARAM_INT, 'course category id'),
                'category_name' => new external_value(PARAM_TEXT, 'course category name'),
                'subcategory_id' => new external_value(PARAM_INT, 'course subcategory id'),
                'subcategory_name' => new external_value(PARAM_TEXT, 'course subcategory name'),
                'fullname' => new external_value(PARAM_TEXT, 'course name'),
                'shortname' => new external_value(PARAM_TEXT, 'course short name'),
                'lang' => new external_value(PARAM_TEXT, 'course language'),
                'summary' => new external_value(PARAM_TEXT, 'course summary'),
                'timemodified' => new external_value(PARAM_TEXT, 'course last modified time'),
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
            $lang = $category_id == 1 ? 'en' : 'fr';
            $category = core_course_category::get($category_id);
            $result = array();
            $categories = $category->get_children();
            foreach ($categories as $category) {
                $subcategories = $category->get_children();
                foreach ($subcategories as $subcategory) {
                    $courses = $subcategory->get_courses();
                    foreach ($courses as $course) {
                        $result[] = array(
                            'id' => $course->id,
                            'category_id' => $category->id,
                            'category_name' => $category->get_formatted_name(),
                            'subcategory_id' => $subcategory->id,
                            'subcategory_name' => $subcategory->get_formatted_name(),
                            'fullname' => $course->get_formatted_fullname(),
                            'shortname' => $course->get_formatted_shortname(),
                            'lang' => $lang,
                            'summary' => '',
                            'timemodified' => userdate($course->timemodified, '%d %B %Y')
                        );
                    }
                }
            }
            return $result;
        } else {
            return array();
        }
    }

}
