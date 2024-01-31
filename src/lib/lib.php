<?php

declare(strict_types=1);
function getToken(string $fiscalIPAndPort, array $userAndPass)
{
    try {
        $headers = [
            "content-type" => "x-www-form-urlencoded"
        ];

        $body = http_build_query($userAndPass);

        $response = makeHTTPRequest("http://" . $fiscalIPAndPort . "/api/token", "POST", $headers, $body);

        $response = makeObjectConnectResponseFromJSON($response);

        if (!$response) throw new Exception("Petición no autorizada");

        return $response;
    } catch (Exception $error) {
        throw $error;
    }
}

function getTabla(string $fiscalIpAndPort, array $userAndPass, string $tabla, int|null $id = null, string|null $codigo = null): false | array | object
{
    try {
        $validTables = ["usuarios", "vendedores", "proveedores"];
        if (!in_array($tabla, $validTables, true)) return false;
        $getTokenRequest = getToken($fiscalIpAndPort, $userAndPass);

        if (!$getTokenRequest) return false;

        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36",
            "Authorization: Bearer " . $getTokenRequest->jwt
        ];

        $mainUrl = "http://" . $fiscalIpAndPort . "/api/tabla?t=" . $tabla;
        //url to make the request
        $finalUrl = "http://" . $fiscalIpAndPort . "/api/tabla?t=" . $tabla;

        if (!is_null($id)) $finalUrl = $mainUrl . "&id=" . $id;
        if (!is_null($codigo)) $finalUrl = $mainUrl . "&codigo=" . $codigo;

        $response = makeHTTPRequest($finalUrl, "GET", $headers, null);

        if (!$response) return false;

        $responseDecoded = json_decode($response);
        if (is_array($responseDecoded)) return $responseDecoded;
        if (is_object($responseDecoded)) {
            if (isset(get_object_vars($responseDecoded)["Error"])) throw new Exception("No se pudo autorizar su petición");
        }
        return $responseDecoded;
    } catch (Exception $error) {
        throw $error;
    }
}
