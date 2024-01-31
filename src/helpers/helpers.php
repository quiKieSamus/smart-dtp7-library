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

function createInstance(string $className, array $properties) {
    if (!class_exists($className)) return false;
    $instance = new $className(...$properties);
    return $instance;
}

function makeResponseArrayItemsUsuario(array $values): array {
    try {
        $firstItem = $values[0];
        !property_exists($firstItem, "codigo") ? $firstItem->codigo = "" : true;
        $permissions = new Permissions(true, true, true, true, true, true, true, true, true, true);
        $sampleTypeCorrectInstance = new Usuario(0, 1, new DatosPersonales(), "ruben", "V123", "1", "1", $permissions, "a", 1);
        $currentProps = json_decode(json_encode($firstItem), true);
        $correctProps = getPropertiesOfClass($sampleTypeCorrectInstance);

        if (!keysExistsInJson($correctProps, $currentProps)) throw new Exception("Los datos obtenidos no son los esperadas para la tabla");

        return array_map(function ($item) {
            $datosPersonales = new DatosPersonales(
                property_exists($item->datosPersonales, "Celular") ? (is_null($item->datosPersonales->Celular) ? "" : $item->datosPersonales->Celular) : "",
                property_exists($item->datosPersonales, "Direccion") ? (is_null($item->datosPersonales->Direccion) ? "" : $item->datosPersonales->Direccion) : "",
                property_exists($item->datosPersonales, "Email") ? (is_null($item->datosPersonales->Email) ? "" : $item->datosPersonales->Email) : "",
                property_exists($item->datosPersonales, "Facebook") ? (is_null($item->datosPersonales->Facebook) ? "" : $item->datosPersonales->Facebook) : "",
                property_exists($item->datosPersonales, "Instagram") ? (is_null($item->datosPersonales->Instagram) ? "" : $item->datosPersonales->Instagram) : "",
                property_exists($item->datosPersonales, "PaginaWeb") ? (is_null($item->datosPersonales->PaginaWeb) ? "" : $item->datosPersonales->PaginaWeb) : "",
                property_exists($item->datosPersonales, "Telefono") ? (is_null($item->datosPersonales->Telefono) ? "" : $item->datosPersonales->Telefono) : "",
                property_exists($item->datosPersonales, "Twitter") ? (is_null($item->datosPersonales->Twitter) ? "" : $item->datosPersonales->Twitter) : ""
            );

            $permisos = new Permissions(
                $item->permisos->Anulacion,
                $item->permisos->Correccion,
                $item->permisos->CorreccionPago,
                $item->permisos->CrearProducto,
                $item->permisos->Descuentos,
                $item->permisos->FondoCaja,
                $item->permisos->ModificarFactura, 
                $item->permisos->Recargos,
                $item->permisos->ReportesZ,
                $item->permisos->RetiroCaja
            );
            return new Usuario(
                $item->borrado, 
                $item->id, 
                $datosPersonales, 
                $item->nombre, 
                $item->rif, 
                $item->fechaCre, 
                $item->fechaMo, 
                $permisos, 
                $item->rsocial, 
                $item->tipo, 
                $item->codigo);
        }, $values);
    } catch (Exception $error) {
        throw $error;
    }
}