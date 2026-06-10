<?php

namespace App;

class Response
{
    public static function json($data, int $status = 200): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type');
        }

        http_response_code($status);

        if ($status !== 204) {
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        exit;
    }
}
