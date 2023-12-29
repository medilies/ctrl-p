# Ctrl P

[![Latest Version on Packagist](https://img.shields.io/packagist/v/medilies/ctrl-p.svg?style=flat-square)](https://packagist.org/packages/medilies/ctrl-p)
[![Pest Action](https://img.shields.io/github/actions/workflow/status/medilies/ctrl-p/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/medilies/ctrl-p/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Pint action](https://img.shields.io/github/actions/workflow/status/medilies/ctrl-p/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/medilies/ctrl-p/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/medilies/ctrl-p.svg?style=flat-square)](https://packagist.org/packages/medilies/ctrl-p)

## Setup

```bash
composer require medilies/ctrl-p
```

## Requirements

- PHP 8.1

## Usage

### Set the HTML

- Use `CtrlP::html('foo')` or `$ctrlP->setHtml('foo')` to set the HTML.
- Use `CtrlP::template('<?php echo "foo";', [])` or `$ctrlP->template('<?php echo "foo";', [])` to set the HTML from a PHP template.

### Set page size and orientation

- Use `format($paperFormat)` to set a standard paper format.
- Use `landscape()` or `portrait()` to direct the chosen paper format.
- Use `paperSize($width, $height)` to set an explicit size.

### Margin

Use `margins($margins)` to set the margins.

> Margins (header and footer) content cannot be be edited as of december 2023 because no browser supports it ([see](https://stackoverflow.com/a/77632288/17873304))

### Control

- Use `printButton($bool)` to add/remove a print button.
- Use `backUrl($url)` to add/remove a button with a link to a page of your choice.
- Use `autoPrint()` to automatically print the page after rendering it.

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
