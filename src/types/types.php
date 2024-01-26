<?php
declare(strict_types=1);

class ConnectResponse
{
    public string $Estado;
    public int $expires_in;
    public string $firmware_version;
    public int $id;
    public string $jwt;
    public string $rif;
    public string $sn;
    public string $token_type;

    public function __construct(string $Estado, int $expires_in, string $firmware_version, int $id, string $jwt, string $rif, string $sn, string $token_type)
    {
        $this->Estado = $Estado;
        $this->expires_in = $expires_in;
        $this->firmware_version = $firmware_version;
        $this->id = $id;
        $this->jwt = $jwt;
        $this->rif = $rif;
        $this->sn = $sn;
        $this->token_type = $token_type;
    }
}