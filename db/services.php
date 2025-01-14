<?php

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_course_recommendations_ws_get_recommendations' => [
        // The name of the namespaced class that the function is located in.
        'classname' => 'local_course_recommendations_ws\external\get_recommendations',

        // A brief, human-readable, description of the web service function.
        'description' => 'Get course recommendations for a user.',

        // The type of access control that the function uses.
        'type' => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax' => true,

        // The capabilities that a user must have to use the function.
        'capabilities' => '',
    ],
    'local_course_recommendations_ws_get_categories' => [
        'classname' => 'local_course_recommendations_ws\external\get_categories',
        'description' => 'Get course categories.',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => '',
    ],
    'local_course_recommendations_ws_get_courses' => [
        'classname' => 'local_course_recommendations_ws\external\get_courses',
        'description' => 'Get courses in a category.',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => '',
    ],
    'local_course_recommendations_ws_get_institutions' => [
        'classname' => 'local_course_recommendations_ws\external\get_institutions',
        'description' => 'Get institutions.',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => '',
    ],
    'local_course_recommendations_ws_get_functions' => [
        'classname' => 'local_course_recommendations_ws\external\get_functions',
        'description' => 'Get functions.',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => '',
    ],
    'local_course_recommendations_ws_get_course_completions' => [
        'classname' => 'local_course_recommendations_ws\external\get_course_completions',
        'description' => 'Get course completions.',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => '',
    ],
];

$services = [
    // The name of the service.
    'Course recommendations web service' => [

        // A list of external functions available in this service.
        'functions' => [
            'local_course_recommendations_ws_get_recommendations',
            'local_course_recommendations_ws_get_categories',
            'local_course_recommendations_ws_get_courses',
            'local_course_recommendations_ws_get_institutions',
            'local_course_recommendations_ws_get_functions',
            'local_course_recommendations_ws_get_course_completions',
        ],

        // The required capability of the user to access this service.
        'requiredcapability' => '',

        // If enabled, the Moodle administrator must link a user to this service from the Web UI.
        'restrictedusers' => 1,

        // Whether the service is enabled by default.
        'enabled' => 1,

        // The short name of the service.
        'shortname' => 'course_recommendations_ws',

        // Whether to allow file downloads.
        'downloadfiles' => 0,

        // Whether to allow file uploads.
        'uploadfiles'  => 0,
    ],
];
