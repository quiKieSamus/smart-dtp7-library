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
        if (!key_exists($prop, $current_keys)) {
            return false;
        }
    }
    return true;
}

function isValidJSON(string $json)
{
    return !json_decode($json) ? false : true;
}

function makeHTTPRequest(string $url, string $method, array $headers, string|null $body)
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

function createInstance(string $className, array $properties)
{
    if (!class_exists($className)) return false;
    $instance = new $className(...$properties);
    return $instance;
}

function makeTypesForTableResponse(array $values, string $className): array
{
    try {
        $typeCorrectData = makeSampleTypeCorrectObject($className);
        $correctProperties = getPropertiesOfClass($typeCorrectData);
        return array_map(function ($item) use ($typeCorrectData, $correctProperties) {
            $itemAssoc = json_decode(json_encode($item), true);
            if (!keysExistsInJson($correctProperties, $itemAssoc)) {
            }
        }, $values);
    } catch (Exception $error) {
        throw $error;
    }
}

function makeSampleTypeCorrectObject(string $className)
{
    $lowerCaseClassName = strtolower($className);
    switch ($lowerCaseClassName) {
        case 'usuario':
            $sampleDatosPersonales = new DatosPersonales();
            $samplePermission = new Permisos(false, true, false, true, false, true, false, true, false, true);
            return new Usuario(1, 123, $sampleDatosPersonales, "", "", "", "", $samplePermission, "", 0, "");
        case 'cliente':
            $sampleDatosPersonales = new DatosPersonales();
            return new Cliente("", 0, 0, 0, "", "", $sampleDatosPersonales);
    }
}

// function fillMissingProperties(array $correctProperties, object $currentObject, string $className)
// {
//     try {
//         $sampleTypeCorrectObject = makeSampleTypeCorrectObject($className);
//         $currentProps = json_decode(json_encode($currentObject), true);
//         $arrayOfCurrentProps = getPropertiesOfClass($currentObject);
//         for ($i = 0; $i < count($correctProperties); $i++) {
//             $correctProp = $correctProperties[$i];
//             if (!in_array($correctProp, $arrayOfCurrentProps)) {

//             }
//         }
//     } catch (Exception $error) {
//         throw new $error;
//     }
// }
