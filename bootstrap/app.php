<?php

declare(strict_types=1);

use Core\Application;

$app = new Application();

require dirname(__DIR__) . '/routes/web.php';

return $app;
