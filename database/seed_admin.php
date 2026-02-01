<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$config = require dirname(__DIR__) . '/config/database.php';
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $config['host'], $config['port'], $config['name'], $config['charset']);
$pdo = new PDO($dsn, $config['user'], $config['pass']);

$senha = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare('UPDATE usuarios SET senha = ? WHERE email = ?');
$stmt->execute([$senha, 'admin@sisterma.com.br']);

echo "Senha do admin atualizada para: admin123\n";
