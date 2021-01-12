<?php

return [

    'conversions' => [
        'local' => [
            'format' => 'jpg',
            'thumb' => true,
            'preview' => true,
            'square' => true,
            '4:3' => false,
            '16:9' => false,
        ],
        'staging' => [
            'format' => 'webp',
            'thumb' => true,
            'preview' => true,
            'square' => true,
            '4:3' => false,
            '16:9' => false,
        ],
        'production' => [
            'format' => 'webp',
            'thumb' => true,
            'preview' => true,
            'square' => true,
            '4:3' => true,
            '16:9' => true,
        ],
    ],

    /*
     * Permissions
     */
    'permissions' => [
        'media'
    ],

    /*
    * Roles
    */
    'roles' => [
        //
    ],

    /*
    * Roles permissions
    */
    'roles_permissions' => [
       ['admin' => 'media']
    ],

    /*
     * Package architecture
     */
    'archi' => [
        // Archi
    ],

    /*
     * Package CRUDs
     */
    'cruds' => [
        // Cruds
    ],

    /*
     * Package Seeds
     */
    'seeds' => [
        //
    ],

];
