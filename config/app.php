<?php

return [
    'name' => getenv('APP_NAME') ?: 'SisTerMa',
    'env' => getenv('APP_ENV') ?: 'development',
    'debug' => filter_var(getenv('APP_DEBUG') ?: true, FILTER_VALIDATE_BOOLEAN),
    'url' => rtrim(getenv('APP_URL') ?: 'http://localhost', '/'),
    'timezone' => 'America/Sao_Paulo',
];
