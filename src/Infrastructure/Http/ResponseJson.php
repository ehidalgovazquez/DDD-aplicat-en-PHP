<?php
namespace App\Infrastructure\Http;

class ResponseJson {
    public function __construct(
        private int $status = 200,
        private string $message = '',
        private array|object|null $data = null
    ) {}

    public function send(): void {
        header('Content-Type: application/json');
        http_response_code($this->status);
        echo json_encode([
            'status'  => $this->status,
            'message' => $this->message,
            'data'    => $this->data
        ]);
        exit;
    }
}