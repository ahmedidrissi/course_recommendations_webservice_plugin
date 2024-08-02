<?php
// Write the external function description.
namespace local_course_recommendations_ws\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

class get_course_completions extends external_api
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
     * @return array of course completions
     */
    public static function execute_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'id' => new external_value(PARAM_INT, 'course completion id'),
                'course_id' => new external_value(PARAM_INT, 'course id'),
                'user_id' => new external_value(PARAM_INT, 'user id'),
                'timeenrolled' => new external_value(PARAM_INT, 'time enrolled'),
                'timestarted' => new external_value(PARAM_INT, 'time started'),
                'timecompleted' => new external_value(PARAM_INT, 'time completed')
            ])
        );
    }

    /**
     * The function that returns the course completions
     * @return array of course completions
     */
    public static function execute()
    {
        global $DB;

        // Get only course completions that have a start time
        $records = $DB->get_records_sql('SELECT * FROM {course_completions} WHERE timestarted > 0');

        $course_completions = array();
        foreach ($records as $record) {
            $course_completions[] = array(
                'id' => $record->id,
                'course_id' => $record->course,
                'user_id' => $record->userid,
                'timeenrolled' => $record->timeenrolled,
                'timestarted' => $record->timestarted,
                'timecompleted' => $record->timecompleted
            );
        }

        return $course_completions;
    }

}
