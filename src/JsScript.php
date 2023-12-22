<?php

namespace Medilies\CtrlP;

use Stringable;

class jsScript implements Stringable
{
    public static function new(): static
    {
        return new static;
    }

    final public function __construct()
    {
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->compilePageJs();
    }

    protected function compilePageJs(): string
    {
        // TODO: options
        // - Auto print?
        // - Print button
        // - Edit title
        // - Edit url
        // - Back to app button

        // window.history.pushState("object or string", "ignored title", "/new-url");
        // window.history.replaceState('data to be passed', 'Title of the page', '/test');
        // window.print();
        // document.title = "Title"

        return '
            window.history.pushState("object or string", "ignored title", "/new-url");
            document.title = "Title";
            window.print();
        ';
    }
}
