<?php
namespace Tests;

require_once(__DIR__ . '/../src/Models/checkRequest.php');

class GeoRankerTest extends BaseTestCase
{
    /**
     * @dataProvider dataProvider
     * @param $url
     */
    public function testRoutes($url) {
        $response = $this->runApp("POST", '/api/Stream/'.$url);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function dataProvider() {
        return [
            ['createActivity'],
            ['getActivities'],
            ['deleteActivity'],
            ['followFeed'],
            ['unFollowFeed'],
            ['getFeedFollower'],
            ['getFeedFollowings'],
            ['createMultipleFollows'],
            ['addActivityToFeeds']
        ];
    }
}