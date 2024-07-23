<?php
// Write the external function description.
namespace local_course_recommendations_ws\external;

use core\plugininfo\format;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

class get_institutions extends external_api
{
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters()
    {
        return new external_function_parameters([
        ]);
    }

    /**
     * Returns description of method result value
     * @return array of institutions
     */
    public static function execute_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'name' => new external_value(PARAM_TEXT, 'institution name')
            ])
        );
    }

    /**
     * The function that returns the institutions
     * @return array of institutions
     */
    public static function execute()
    {
        global $DB;

        // Get unique institutions from the user table
        $records = $DB->get_records_sql('SELECT DISTINCT institution FROM {user} WHERE institution IS NOT NULL AND institution != ""');

        $institutions = array();
        foreach ($records as $record) {
            $institutions[] = array('name' => format_string($record->institution));
        }

        return $institutions;
    }

}