<?php
$routes = [
    'createActivity',
    'getActivities',
    'deleteActivity',
    'followFeed',
    'unFollowFeed',
    'getFeedFollower',
    'getFeedFollowings',
    'createMultipleFollows',
    'addActivityToFeeds',
    'metadata'
];
foreach($routes as $file) {
    require __DIR__ . '/../src/routes/'.$file.'.php';
}

