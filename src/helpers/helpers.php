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
        logError($e);
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

function isValidJSON(string $json): bool
{
    return !json_encode($json) ? false : true;
}

function makeHTTPRequest(string $url, array|null $headers, string|null $body, string $method = "GET"): string
{
    try {
        validateMethod($method);
        $curl = setCURLOptions($url, $method, $headers, $body);
        $response = executeHTTPRequest($curl);
        return $response;
    } catch (Exception $e) {
        logError($e);
        return "Fallo en la petición: " . $e->getMessage();
    }
}

function setCURLOptions(string $url, string $method, array|null $headers, string|null $body): CurlHandle
{
    $curl = curl_init($url);
    $method = mb_strtoupper($method, "UTF-8");

    // configuring request
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, is_null($method) ? 'GET' : $method);

    // setting header connection close; check if $headers is null
    if (is_null($headers)) $headers = [];
    array_push($headers, "Connection: Close");

    if (!is_null($headers)) {
        if (!curl_setopt($curl, CURLOPT_HTTPHEADER, $headers)) throw new Exception("Fallo al añadir las cabeceras HTTP");
    }
    // setting body when there is a body and the method is different to get
    if (!is_null($body) && $method !== 'GET') {
        if (!curl_setopt($curl, CURLOPT_POSTFIELDS, $body)) throw new Exception("Fallo al añadir cuerpo a la petición HTTP");
    }

    if (!curl_setopt($curl, CURLOPT_RETURNTRANSFER, true)) throw new Exception("Fallo al querer establecer la petición como operación de retorno");

    return $curl;
}

function validateMethod(string $method): void
{
    $allowedMethods = ["GET", "POST", "DELETE", "PUT", "PATCH", "HEAD"];
    if (!in_array($method, $allowedMethods)) throw new Exception("Método no permitido");
}

function executeHTTPRequest(CurlHandle $curl): string
{
    $response = curl_exec($curl);
    if (!$response) throw new Exception("La petición falló");
    curl_close($curl);
    return $response;
}

// function createInstance(string $className, array $properties)
// {
//     if (!class_exists($className)) return false;
//     $instance = new $className(...$properties);
//     return $instance;
// }

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
    } catch (Exception $e) {
        logError($e);
        return false;
    }
}

function makeSampleTypeCorrectObject(string $className)
{
    try {
        $lowerCaseClassName = strtolower($className);
        switch ($lowerCaseClassName) {
            case 'usuario':
                $sampleDatosPersonales = new DatosPersonales();
                $samplePermission = new Permisos(false, true, false, true, false, true, false, true, false, true);
                return new Usuario(1, $sampleDatosPersonales, "", "", 1, 1, $samplePermission, "", 0);
            case 'cliente':
                $sampleDatosPersonales = new DatosPersonales();
                return new Cliente(0, 0, 0, $sampleDatosPersonales);
            case 'producto':
                return new Producto(0, 0, 0, 0, 0, 0, 0, "", 0, [100, 200, 300], 0);
            default:
                throw new Exception("Clase no existe");
        }
    } catch (Exception $e) {
        logError($e);
        return false;
    }
}

function logError(Exception $e): void
{
    error_log(sprintf(
        "Error in file: %s at line %s with message: %s - Stack Trace: %s",
        $e->getFile(),
        $e->getLine(),
        $e->getMessage(),
        $e->getTraceAsString()
    ));
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
//     } catch (Exception $e) {
//         throw new $e;
//     }
// }
