<?php

$app->post('/api/Stream/addActivityToFeeds', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Models\checkRequest $checkRequest */

    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'apiSecret', 'feedList', 'activity']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $toJson = new \Models\normilizeJson();
    $data = $toJson->normalizeJson(file_get_contents($postData['args']['activity']));
    $data = str_replace('\"', '"', $data);
    $activityJson = json_decode($data, true);

    if (is_array($postData['args']['feedList'])) {
        $feedList = $postData['args']['feedList'];
    }
    else {
        $feedList = explode(',', $postData['args']['feedList']);
    }

    try {
        $client = new GetStream\Stream\Client($postData['args']['apiKey'], $postData['args']['apiSecret']);
        $batcher = $client->batcher();

        $responseFromVendor = $batcher->addToMany($activityJson, $feedList);
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