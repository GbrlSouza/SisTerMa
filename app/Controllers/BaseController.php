<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Response;

abstract class BaseController
{
    protected function view(string $name, array $data = []): Response
    {
        extract($data);
        ob_start();
        $path = base_path("views/{$name}.php");
        if (file_exists($path)) {
            require $path;
        }
        return new Response(ob_get_clean());
    }

    protected function redirect(string $url, int $status = 302): Response
    {
        return new Response('', $status, ['Location' => $url]);
    }
}
