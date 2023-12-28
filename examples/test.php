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
        yoo
    </main>

    <script src="https://cdn.tailwindcss.com"></script>
    @CtrlP
</body>
</html>
    ')
    ->margins('2in')
    ->paperSize('130mm', '130mm')
    ->title('yoyo')
    ->urlPath('/yoyo')
    ->autoPrint()
    ->printButton()
    ->backUrl('/')
    ->get();
