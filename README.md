# Ctrl P

## Setup

```bash
composer require medilies/ctrl-p
```

## Usage

- Instantiate `CtrlP` and provide and an HTML or PHP template.
- The template should contain this string `@CtrlP` to indicate where to inject the package code.
- Visit the rendered page on the browser.
- Print or save the PDF.

## Example

```php
<?php

// * php -S 0.0.0.0:8080 .\this_file.php
// Visit http://127.0.0.1:8080/

use Medilies\CtrlP\CtrlP;

require_once __DIR__.'/vendor/autoload.php';

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
```

![screenshot](./screenshot.png)
