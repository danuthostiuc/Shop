<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-03-22
 * Time: 6:50 PM
 */

return array(
    'email' => array(
        // The system default sender (Group A)
        'default' => array(
            'address' => 'shop@email.com',
            'name' => 'Shop', // this can be left out
        ),
        // The individual senders (Group B)
        // Form block:
        'form_block' => array(
            'address' => 'shop@email.com',
        ),
        // User registration email validation messages
        'validate_registration' => array(
            'address' => 'shop@email.com',
            'name' => 'Site Email Validation', // this can be left out
        ),
        // Forgot password messages
        'forgot_password' => array(
            'address' => 'shop@email.com',
        ),
    ),
);
