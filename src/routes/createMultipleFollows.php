<?php

$app->post('/api/Stream/createMultipleFollows', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Models\checkRequest $checkRequest */

    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'apiSecret', 'list']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    if (is_array($postData['args']['list'])) {
        $data = $postData['args']['list'];
    }
    else {
        $toJson = new \Models\normilizeJson();
        $data = $toJson->normalizeJson(file_get_contents($postData['args']['list']));
        $data = str_replace('\"', '"', $data);
        $data = json_decode($data, true);
    }


    if (!empty($data)) {
        try {
            $client = new GetStream\Stream\Client($postData['args']['apiKey'], $postData['args']['apiSecret']);
            $batcher = $client->batcher();
            $responseFromVendor = $batcher->followMany($postData['args']['list']);
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
    }
    else {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'List cannot be empty';
    }


    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});