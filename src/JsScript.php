<?php

namespace Medilies\CtrlP;

use Stringable;

class JsScript implements Stringable
{
    protected bool $autoPrint = true;

    protected ?string $title = null;

    protected ?string $urlPath = null;

    public static function new(): static
    {
        return new static;
    }

    final public function __construct()
    {
    }

    public function autoPrint(bool $autoPrint = true): static
    {
        $this->autoPrint = $autoPrint;

        return $this;
    }

    public function title(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function urlPath(?string $urlPath): static
    {
        $this->urlPath = $urlPath;

        return $this;
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
        $js = '';

        if ($this->title) {
            $js .= "document.title = `{$this->title}`;\n";
        }

        if ($this->urlPath) {
            $js .= "window.history.pushState('object or string', 'ignored title', `{$this->urlPath}`);\n";
            // ? window.history.replaceState or window.history.pushState
        }

        if ($this->autoPrint) {
            $js .= "window.print();\n";
        }

        return $js;
    }
}
