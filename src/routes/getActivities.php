<?php

$app->post('/api/Stream/getActivities', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Models\checkRequest $checkRequest */

    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'apiSecret', 'feedOwnerType', 'feedOwnerId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $offset = 0;
    $limit = 20;

    $activityOptions = [];

    if (isset($postData['args']['idGte']) && strlen($postData['args']['idGte']) > 0) {
        $activityOptions['id_gte'] = $postData['args']['idGte'];
    }
    if (isset($postData['args']['idGt']) && strlen($postData['args']['idGt']) > 0) {
        $activityOptions['id_gt'] = $postData['args']['idGt'];
    }
    if (isset($postData['args']['idLte']) && strlen($postData['args']['idLte']) > 0) {
        $activityOptions['id_lte'] = $postData['args']['idLte'];
    }
    if (isset($postData['args']['idLt']) && strlen($postData['args']['idLt']) > 0) {
        $activityOptions['id_lt'] = $postData['args']['id_lt'];
    }

    if (isset($postData['args']['offset']) && (int)$postData['args']['offset'] > 0) {
        $offset = (int)$postData['args']['offset'];
    }
    if (isset($postData['args']['limit']) && (int)$postData['args']['limit'] > 0) {
        $limit = (int)$postData['args']['offset'];
    }

    try {
        $client = new GetStream\Stream\Client($postData['args']['apiKey'], $postData['args']['apiSecret']);
        if (isset($postData['args']['location']) && strlen(isset($postData['args']['location']) > 0)) {
            $client->setLocation($postData['args']['location']);
        }
        $feed = $client->feed($postData['args']['feedOwnerType'], $postData['args']['feedOwnerId']);
        $responseFromVendor = $feed->getActivities($offset, $limit, $activityOptions);
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