<?php

$app->post('/api/Stream/unFollowFeed', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Models\checkRequest $checkRequest */

    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'apiSecret', 'feedOwnerType', 'feedOwnerId', 'feedTargetType', 'feedTargetId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    try {
        $client = new GetStream\Stream\Client($postData['args']['apiKey'], $postData['args']['apiSecret']);
        if (isset($postData['args']['location']) && strlen(isset($postData['args']['location']) > 0)) {
            $client->setLocation($postData['args']['location']);
        }
        $feed = $client->feed($postData['args']['feedOwnerType'], $postData['args']['feedOwnerId']);
        $responseFromVendor = $feed->unfollowFeed($postData['args']['feedTargetType'], $postData['args']['feedTargetId']);
        $result['callback'] = 'success';
        $result['contextWrites']['to'] = $responseFromVendor;
    } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = json_decode($exception->getResponse()->getBody());
    } catch (\GetStream\Stream\StreamFeedException $exception) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = json_decode($exception->getMessage());
    }


    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});