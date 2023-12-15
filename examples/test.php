<?php

// * php -S 0.0.0.0:8080 .\test.php

use Medilies\CtrlP\AtPage;
use RowBloom\RowBloom\Renderers\Sizing\Length;

require_once __DIR__. '/../vendor/autoload.php';

$page = AtPage::new()
    ->margins('2in')
    ->paperSize(
        null,
        Length::fromDimension('210mm'),
        Length::fromDimension('297mm')
    ) // A4
    ->toString();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        <?= $page ?>
    </style>
</head>
<body>
    <main class="bg-gray-300">
        yoo
    </main>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.history.pushState("object or string", "ignored title", "/new-url");
        document.title = "Title";
        window.print();
    </script>
</body>
</html>
