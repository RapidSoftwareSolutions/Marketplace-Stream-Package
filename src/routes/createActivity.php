<?php

$app->post('/api/Stream/createActivity', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Models\checkRequest $checkRequest */

    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'apiSecret', 'feedOwnerType', 'feedOwnerId', 'activity']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $toJson = new \Models\normilizeJson();
    $data = $toJson->normalizeJson(file_get_contents($postData['args']['activity']));
    $data = str_replace('\"', '"', $data);
    $json = json_decode($data, true);

    if (json_last_error() != 0) {
        $json_error[] = json_last_error_msg() . '. Incorrect input JSON. Please, check fields with JSON input.';
    }
    if (!empty($json_error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'JSON_VALIDATION';
        $result['contextWrites']['to']['status_msg'] = implode(',', $json_error);
    }
    else {
        try {
            $client = new GetStream\Stream\Client($postData['args']['apiKey'], $postData['args']['apiSecret']);
            if (isset($postData['args']['location']) && strlen(isset($postData['args']['location']) > 0)) {
                $client->setLocation($postData['args']['location']);
            }
            $feed = $client->feed($postData['args']['feedOwnerType'], $postData['args']['feedOwnerId']);
            $responseFromVendor = $feed->addActivity($json);
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

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});