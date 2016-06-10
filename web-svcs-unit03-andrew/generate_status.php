<?php

use Zend\Math\Rand;

include __DIR__ . '/../vendor/autoload.php';

$dbFile = realpath(__DIR__) . '/data/db/status.db';
if (file_exists($dbFile)) {
    unlink($dbFile);
}
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec(file_get_contents(__DIR__ . '/schema.sqlite.sql'));

$users = array(
    1 => 'ezimuel',
    2 => 'mwop',
    3 => 'ralphschindler',
);

$stmt = $pdo->prepare('INSERT INTO status (id, text, user) VALUES (:id, :text, :user)');

for ($i = 0; $i < 100; $i += 1) {
    $id     = Rand::getString(32, 'abcdef0123456789');
    $text   = sprintf('This is the text for status id %s', $id);
    $user   = $users[Rand::getInteger(1, 3)];

    $status = array(
        ':id'   => $id,
        ':text' => $text,
        ':user' => $user,
    );
    $stmt->execute($status);
}
