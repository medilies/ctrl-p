<?php

// * php -S 0.0.0.0:8080 .\test.php

use Medilies\CtrlP\CtrlP;

require_once __DIR__.'/../vendor/autoload.php';

echo CtrlP::html('
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <main class="bg-gray-300">
        Let the browser do it
    </main>

    <script src="https://cdn.tailwindcss.com"></script>
    @CtrlP
</body>
</html>
    ')
    ->margins('2in')
    ->paperSize('130mm', '130mm')
    ->title('Chad PDF')
    ->urlPath('/drip-url')
    ->autoPrint()
    ->printButton()
    ->backUrl('/some-path')
    ->get();
