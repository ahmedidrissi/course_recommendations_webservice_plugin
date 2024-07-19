<?php
// Write the external function description.
namespace local_course_recommendations_ws\external;

use core_course_category;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

class get_categories extends external_api
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
     * @return core_course_category[]
     */
    public static function execute_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'id' => new external_value(PARAM_INT, 'category id'),
                'name' => new external_value(PARAM_TEXT, 'category name'),
                'coursecount' => new external_value(PARAM_INT, 'number of courses in the category'),
                'parent' => new external_value(PARAM_INT, 'parent category id'),
                'depth' => new external_value(PARAM_INT, 'category depth'),
                'path' => new external_value(PARAM_TEXT, 'category path'),
            ])
        );
    }

    /**
     * The function that returns the categories
     * @param int $category_id the id of the parent category
     * @return array of categories
     */
    public static function execute($category_id)
    {
        if ($category_id != 0) {
            $category = core_course_category::get($category_id);
            $categories = $category->get_children();
            $result = array();
            foreach ($categories as $category) {
                $result[] = array(
                    'id' => $category->id,
                    'name' => $category->name,
                    'coursecount' => $category->coursecount,
                    'parent' => $category->parent,
                    'depth' => $category->depth,
                    'path' => $category->path,
                );
            }
            return $result;
        } else {
            return array();
        }
    }

}