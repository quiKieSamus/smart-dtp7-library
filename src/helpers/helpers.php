<?php

declare(strict_types=1);
function makeObjectConnectResponseFromJSON(string $json): ConnectResponse | false
{
    if (!isValidJSON($json)) return false;
    $classProperties = getPropertiesOfClass(new ConnectResponse("", 1, "", 1, "", "", "", ""));
    $jsonAssoc = json_decode($json, true);
    if (!keysExistsInJson($classProperties, $jsonAssoc)) return false;

    return new ConnectResponse(
        $jsonAssoc['Estado'],
        $jsonAssoc['expires_in'],
        $jsonAssoc['firmware_version'],
        $jsonAssoc['id'],
        $jsonAssoc['jwt'],
        $jsonAssoc['rif'],
        $jsonAssoc['sn'],
        $jsonAssoc['token_type']
    );
}

function getPropertiesOfClass(object $object)
{
    return array_keys(get_object_vars($object));
}

function keysExistsInJson(array $expected_keys, array $current_keys): bool
{
    for ($i = 0; $i < count($expected_keys); $i++) {
        $prop = $expected_keys[$i];
        if (!key_exists($prop, $current_keys)) return false;
    }
    return true;
}

function isValidJSON(string $json)
{
    return !json_decode($json) ? false : true;
}

function makeHTTPRequest(string $url, string $method, array $headers, string $body)
{
    try {
        $curl = curl_init($url);

        // configuring request
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, is_null($method) ? 'GET' : $method);

        if (!is_null($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        
        if (!is_null($body)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // getting response
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    } catch (Exception $error) {
        throw $error;
    }
}
