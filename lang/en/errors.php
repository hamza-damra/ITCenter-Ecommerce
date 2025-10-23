<?php

return [
    // Database Connection Error
    'db_connection_failed' => 'Database connection failed',
    
    // Database Down Page
    'db_down' => [
        'title' => 'Service Temporarily Unavailable',
        'status' => 'Database Connection Lost',
        'heading' => 'We\'re Having Technical Difficulties',
        'subtitle' => 'Our database server is currently unavailable',
        'message' => 'We apologize for the inconvenience. Our technical team has been notified and is working to restore service as quickly as possible. Please try again in a few minutes.',
        
        'info_title' => 'What You Can Do',
        'info_1' => 'Wait a few moments and refresh this page',
        'info_2' => 'Check back later - we\'re working to fix this',
        'info_3' => 'Contact our support team if the issue persists',
        'info_4' => 'Your data is safe and will be available when service is restored',
        
        'retry' => 'Try Again',
        'contact' => 'Contact Support',
        'footer' => '© ' . date('Y') . ' IT Center. All rights reserved.',
    ],

    // 404 Error
    '404' => [
        'title' => 'Page Not Found',
        'heading' => 'Oops! Page Not Found',
        'message' => 'The page you\'re looking for doesn\'t exist or has been moved.',
    ],

    // 500 Error
    '500' => [
        'title' => 'Server Error',
        'heading' => 'Internal Server Error',
        'message' => 'Something went wrong on our end. Please try again later.',
    ],

    // 503 Error
    '503' => [
        'title' => 'Service Unavailable',
        'heading' => 'Service Temporarily Unavailable',
        'message' => 'We\'re performing scheduled maintenance. Please check back soon.',
    ],
];
