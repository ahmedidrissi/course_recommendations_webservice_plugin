<?php
// Write the external function description.
namespace local_course_recommendations_ws\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

class get_functions extends external_api
{
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters()
    {
        return new external_function_parameters([]);
    }

    /**
     * Returns description of method result value
     * @return 
     */
    public static function execute_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'name' => new external_value(PARAM_TEXT, 'function name')
            ])
        );
    }

    /**
     * The function that returns the functions
     * @return array of functions
     */
    public static function execute()
    {
        global $DB;

        // Get the field ID from the shortname 'FONCTION'
        $fieldid = $DB->get_field('user_info_field', 'id', ['shortname' => 'FONCTION'], MUST_EXIST);

        // Get the unique values of the field from the user_info_data table
        $fieldvalues = $DB->get_records_sql('SELECT DISTINCT data FROM {user_info_data} WHERE fieldid = ?', [$fieldid]);

        $functions = array();
        foreach ($fieldvalues as $fieldvalue) {
            $name = format_string($fieldvalue->data);
            if ($name != '') {
                $functions[] = array('name' => $name);
            }
        }

        return $functions;
    }
}
