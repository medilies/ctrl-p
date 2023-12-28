# Ctrl P

## Setup

```bash
composer require medilies/ctrl-p
```

## Usage

### Set the HTML

- Use `CtrlP::html('foo')` or `$ctrlP->setHtml('foo')` to set the HTML.
- Use `CtrlP::template('<?php echo "foo";', [])` or `$ctrlP->template('<?php echo "foo";', [])` to set the HTML from a PHP template.

> The template must contain `@CtrlP` string to indicate where to inject the package code.

### Set page size and orientation

- Use `format($paperFormat)` to set a standard paper format.
- Use `landscape()` or `portrait()` to direct the chosen page format.
- Use `paperSize($width, $height)` to set an explicit size.

### Margin

Use `margins($margins)` to set the margins.

### Control button

- Use `printButton($bool)` to add/remove a print button.
- Use `backUrl($url)` to add/remove a button with a link to a page of your choice.

### Overrides

- Use `title($title)` to override the title.
- Use `urlPath($url)` to override the url path.

## Example

```php
<?php

// php -S 0.0.0.0:8080 .\this_file.php
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
