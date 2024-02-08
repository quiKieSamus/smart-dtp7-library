<?php
declare(strict_types=1);
// this class defines the kind of return the functions on lib will have
namespace ReturnTypes;
class Response {
    public bool $status;
    public array $response;
    public string $error;

    public function __construct(bool $status, array $response = [], string $error = "") {
        $this->status = $status;
        $this->response = $response;
        $this->error = $error;
    }
}

class PingResponse {
    public int $code;
    public string $output;
    public array $outputParts;
    public function __construct(int $code, array $outputParts) {
        $this->code = $code;
        $this->outputParts = $outputParts;
        $this->output = mb_convert_encoding(join("\n", $this->outputParts), "UTF-8");
    }
}