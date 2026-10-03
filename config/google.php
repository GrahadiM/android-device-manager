<?php

return [

    'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),

    'credentials' => env(
        'GOOGLE_APPLICATION_CREDENTIALS'
    ),

    'android_management' => [

        'scope' => env(
            'ANDROID_MANAGEMENT_API_SCOPE',
            'https://www.googleapis.com/auth/androidmanagement'
        ),

    ],

];
