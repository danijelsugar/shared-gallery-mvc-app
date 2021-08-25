<?php

namespace Gallery\Core;

class Response
{
    public function statusCode(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);
    }
}
