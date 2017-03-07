<?php

return [

    /*
     * Callnames used for each thread.
     * In each thread, each poster gets assigned a random callname used here.
     */
    'callnames' => [
        // NATO phonetic alphabet
        'Alpha', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India',
        'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oscar', 'Papa', 'Quebec', 'Romeo',
        'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'Xray', 'Yankee', 'Zulu', 'Dash',
    ],

    /*
     * Permissions and groups.
     * Each of these values is greater or equal (>=).
     */
    'groups' => [
        -1 => 'Banned',
        0 => 'User',
        1 => 'Moderator',
        2 => 'Admin',
    ],
    'permissions' => [
        'deleteThread' => 1,
        'deletePost' => 1,
        'banUser' => 1,
        'promoteUser' => 2,
        'createKey' => 2,
    ]

];