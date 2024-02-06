<?php

declare(strict_types=1);
function makeObjectConnectResponseFromJSON(string $json): ConnectResponse | false
{
    try {
        if (!isValidJSON($json)) return false;
        $classProperties = getPropertiesOfClass(new ConnectResponse("", 1, "", 1, "", "", "", ""));
        $jsonAssoc = json_decode($json, true);

        if (!keysExistsInJson($classProperties, $jsonAssoc)["status"]) throw new Exception("El json esperado no tiene las keys esperadas");
    
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
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function getPropertiesOfClass(object $object)
{
    return array_keys(get_object_vars($object));
}

function keysExistsInJson(array $expected_keys, array $current_keys): array
{
    $missingKeys = [];
    for ($i = 0; $i < count($expected_keys); $i++) {
        $prop = $expected_keys[$i];
        if (!key_exists($prop, $current_keys)) array_push($missingKeys, $prop);
    }
    
    return count($missingKeys) > 0 ? ["status" => false, "missingKeys" => $missingKeys] : ["status" => true];
}

function isValidJSON(string $json)
{
    return !json_decode($json) ? false : true;
}

function makeHTTPRequest(string $url, string $method = "GET", array|null $headers, string|null $body): string
{
    try {
        $allowedMethods = ["GET", "POST", "DELETE", "PUT", "PATCH", "HEAD"];
        if (!in_array($method, $allowedMethods)) throw new Exception("Method not allowed");
        
        
        
        $curl = curl_init($url);
        $method = mb_strtoupper($method, "UTF-8");
        
        // configuring request
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, is_null($method) ? 'GET' : $method);
        
        // setting header connection close; check if $headers is null
        if (is_null($headers)) $headers = [];
        array_push($headers, "Connection: Close");

        if (!is_null($headers)) {
            if (!curl_setopt($curl, CURLOPT_HTTPHEADER, $headers)) throw new Exception("Failed appending headers to request");
        }

        if (!is_null($body) && $method !== 'GET') {
            if (!curl_setopt($curl, CURLOPT_POSTFIELDS, $body)) throw new Exception("Failed appending body to request");
        }
        
        if (!curl_setopt($curl, CURLOPT_RETURNTRANSFER, true)) throw new Exception("Failed setting the request as return");
        
        // getting response
        $response = curl_exec($curl);
        if (!$response) throw new Exception("Request failed");

        curl_close($curl); 
        
        return $response;
    } catch (Exception $error) {
        error_log($error->getMessage());
        return "Request failed " . $error->getMessage();
    } 
}

function createInstance(string $className, array $properties)
{
    if (!class_exists($className)) return false;
    $instance = new $className(...$properties);
    return $instance;
}

function makeTypesForTableResponse(array $values, string $className): array|false
{
    try {
        $typeCorrectData = makeSampleTypeCorrectObject($className);
        $correctProperties = getPropertiesOfClass($typeCorrectData);
        return array_map(function ($item) use ($typeCorrectData, $correctProperties) {
            $itemAssoc = json_decode(json_encode($item), true);
            if (!keysExistsInJson($correctProperties, $itemAssoc)["status"]) {
            }
        }, $values);
    } catch (Exception $error) {
        error_log($error->getMessage());
        return false;
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
