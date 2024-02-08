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