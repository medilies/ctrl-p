<?php

// * php -S 0.0.0.0:8080 .\test.php

use Medilies\CtrlP\CtrlP;
use RowBloom\RowBloom\Renderers\Sizing\Length;

require_once __DIR__.'/../vendor/autoload.php';

$html = CtrlP::html('
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @ctrl_p_css
</head>
<body>
    <main class="bg-gray-300">
        yoo
    </main>

    <script src="https://cdn.tailwindcss.com"></script>
    @ctrl_p_script
</body>
</html>
        ')
        ->margins('2in')
        ->paperSize(
            null,
            Length::fromDimension('130mm'),
            Length::fromDimension('130mm')
        )
    ->get();
?>

<?= $html ?>
