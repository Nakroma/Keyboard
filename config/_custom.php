<?php

return [

    /*
     * Pagination limits (items pro page).
     */
    'threadPagination' => 15,
    'postPagination' => 15,

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
     * The highest group is the one you get assigned with the ADMIN_KEY.
     * The lowest group is assigned when you ban a user.
     * Everything under 0 automatically counts as restricted as some sort (some functions may not be available).
     */
    'groups' => [
        -1 => 'Banned',
        0 => 'User',
        1 => 'Moderator',
        2 => 'Admin',
    ],
    'permissions' => [
        'deleteThread' => 1,    // Can delete threads
        'deletePost' => 1,      // Can delete posts
        'banUser' => 1,         // Can permanently ban a user (Set his group to banned)
        'pinnedThread' => 1,    // Can create a pinned thread
        'revealModStatus' => 1, // Can reveal as a moderator in threads
        'promoteUser' => 2,     // Can promote or demote a user into a different group (<= that of the assigner)
        'createKey' => 2,       // Can generate new keys
    ]

];