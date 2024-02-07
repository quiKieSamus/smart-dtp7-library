<?php
declare(strict_types=1);
function getToken(string $fiscalIPAndPort, array $userAndPass)
{
    try {
        $headers = [
            "content-type" => "x-www-form-urlencoded"
        ];

        $body = http_build_query($userAndPass);

        $response = makeHTTPRequest("http://" . $fiscalIPAndPort . "/api/token", $headers,  $body, "POST");

        if (!$response) throw new Exception("Hubo un fallo al conectar con la impresora. Verifique la ip y el puerto.");

        $response = makeObjectConnectResponseFromJSON($response);

        if (!$response) throw new Exception("Petición no autorizada");

        return $response;
    } catch (Exception $error) {
        error_log($error->getMessage());
        return false;
    }
}

function getTabla(string $fiscalIpAndPort, array $userAndPass, string $tabla, int|null $id = null, string|null $codigo = null): array | object | string
{
    try {
        $validTables = ["usuarios", "vendedores", "proveedores", "clientes", "productos", "monedas"];
        if (!in_array($tabla, $validTables, true)) throw new Exception("Intenta acceder a un elemento de la impresora fiscal inexistente.");
        $getTokenRequest = getToken($fiscalIpAndPort, $userAndPass);

        if (!$getTokenRequest) throw new Exception("No se obtuvo el token, verifique ruta y datos de usuario admin");

        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36",
            "Authorization: Bearer " . $getTokenRequest->jwt
        ];

        $mainUrl = "http://" . $fiscalIpAndPort . "/api/tabla?t=" . $tabla;
        //url to make the request
        $finalUrl = "http://" . $fiscalIpAndPort . "/api/tabla?t=" . $tabla;

        if (!is_null($id)) $finalUrl = $mainUrl . "&id=" . $id; 
        
        $response = !is_null($codigo) ? getSingleResourceByCodigo($fiscalIpAndPort, $userAndPass, $tabla, $codigo) : makeHTTPRequest($finalUrl, $headers, null, "GET");

        if (!$response) throw new Exception("No se pudo conectar a la máquina fiscal. Verifique ip y puerto");

        $responseDecoded = json_decode($response);
        if (is_array($responseDecoded)) return $responseDecoded;
        if (is_object($responseDecoded)) {
            if (isset(get_object_vars($responseDecoded)["Error"])) throw new Exception("No se pudo autorizar su petición");
        }
        return $responseDecoded;
    } catch (Exception $error) {
        error_log($error->getMessage());
        return ["status" => false, "reason" => $error->getMessage()];
    }
}

function createData(string $fiscalIpAndPort, array $userAndPass, string $resource, object|array $data): string|array
{
    try {
        $authorization = getToken($fiscalIpAndPort, $userAndPass);
        if (!$authorization) throw new Exception("No se obtuvo el token, verifique ruta y datos de usuario admin");
        $token = $authorization->jwt;
        $url = "http://" . $fiscalIpAndPort . "/api/" . $resource;
        $body = json_encode($data);
        $contentLength = strlen($body);
        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36",
            "content-type: application/json; charset=utf-8",
            "Authorization: Bearer " . $token,
            "Content-Length: " . $contentLength
        ];
        $httpRequest = makeHTTPRequest($url, $headers, $body, "POST");
        if (!$httpRequest) throw new Exception("No se pudo cargar el elemento");
        return $httpRequest;
    } catch (Exception $error) {
        error_log($error->getMessage());
        return ["status" => false, "reason" => $error->getMessage()];
    }
}

function updateData(string $fiscalIpAndPort, array $userAndPass, string $resource, array|object $body): string|array
{
    try {
        $authorization = getToken($fiscalIpAndPort, $userAndPass);
        if (!$authorization) throw new Exception("No se obtuvo el token, verifique ruta y datos de usuario admin");
        $token = $authorization->jwt;
        $url = "http://" . $fiscalIpAndPort . "/api/" . $resource;
        $body = json_encode($body);
        $contentLength = strlen($body);
        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36",
            "content-type: application/json; charset=utf-8",
            "Authorization: Bearer " . $token,
            "Content-Length: " . $contentLength
        ];
        $httpRequest = makeHTTPRequest($url, $headers, $body, "PUT");
        return $httpRequest;
    } catch (Exception $error) {
        error_log($error->getMessage());
        return ["status" => false, "reason" => $error->getMessage()];
    }
}

function deleteData(string $fiscalIpAndPort, array $userAndPass, string $resource, int|string $id): string|array
{
    try {
        $authorization = getToken($fiscalIpAndPort, $userAndPass);
        if (!$authorization) throw new Exception("No se obtuvo el token, verifique ruta y datos de usuario admin");
        $token = $authorization->jwt;
        $url = "http://" . $fiscalIpAndPort . "/api/" . $resource . "?id=" . $id;
        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36",
            "Authorization: Bearer " . $token,
        ];
        $httpRequest = makeHTTPRequest($url, $headers, null, "DELETE");
        return $httpRequest;
    } catch (Exception $error) {
        error_log($error->getMessage());
        return ["status" => false, "reason" => $error->getMessage()];
    }
}

function recoverData(string $fiscalIpAndPort, array $userAndPass, string $resource, int|string $id): string|array
{
    try {
        $authorization = getToken($fiscalIpAndPort, $userAndPass);
        if (!$authorization) throw new Exception("No se obtuvo el token, verifique ruta y datos de usuario admin");
        $token = $authorization->jwt;
        $url = "http://" . $fiscalIpAndPort . "/api/recuperar?tabla=" . $resource . "&id=" . $id;
        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36",
            "Authorization: Bearer " . $token,
        ];
        $httpRequest = makeHTTPRequest($url, $headers, null, "GET");
        return $httpRequest;
    } catch (Exception $error) {
        error_log($error->getMessage());
        return ["status" => false, "reason" => $error->getMessage()];
    }
}

function getSingleResourceByCodigo(string $fiscalIpAndPort, array $userAndPass, string $tabla, string $codigo): string|array {
    try {
        $resources = getTabla($fiscalIpAndPort, $userAndPass, $tabla);
        
        $filter = array_filter($resources, function ($item) use ($codigo) {
            return $item->codigo === $codigo;
        });
        
        $filterValue = null;
        for ($i = 0; $i < count($resources); $i++) {
            if (isset($filter[$i])) {
                $filterValue = $filter[$i];
                break;
            }
        }
        return json_encode($filterValue);
    } catch (Exception $error) {
        logError($error);
        return ["status" => false, "reason" => $error->getMessage()];
    }
}