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

        if (!$response) return false;

        return $response;
    } catch (Exception $error) {
        throw $error;
    }
}
