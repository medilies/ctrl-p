<?php

namespace Medilies\CtrlP;

use Stringable;

class ControlComponents implements Stringable
{
    protected ?string $backUrl = null;

    protected bool $printButton = false;

    public static function new(): static
    {
        return new static;
    }

    final public function __construct()
    {
    }

    public function backUrl(?string $backUrl): static
    {
        $this->backUrl = $backUrl;

        return $this;
    }

    public function printButton(bool $printButton = true): static
    {
        $this->printButton = $printButton;

        return $this;
    }

    protected function printButtonComponent(): string
    {
        if (! $this->printButton) {
            return '';
        }

        $id = 'ctrl-p-print-button';

        return <<<EOT

            <button onclick='window.print()' id='{$id}'>Print</button>
            <style>
            #{$id} {
                display: block;
            }
            </style>
        EOT;
    }

    protected function backUrlComponent(): string
    {
        if (is_null($this->backUrl)) {
            return '';
        }

        $id = 'ctrl-p-back-url';

        return <<<EOT

            <a href='{$this->backUrl}' id='{$id}'>Back</a>
            <style>
                #{$id} {
                    display: block;
                }
            </style>
        EOT;
    }

    protected function compileComponents(): string
    {
        if (is_null($this->backUrl) && ! $this->printButton) {
            return '';
        }

        $id = 'ctrl-p-control';
        $backUrlComponent = $this->backUrlComponent();
        $printButtonComponent = $this->printButtonComponent();

        return <<<EOT

        <div id='{$id}'>
            {$printButtonComponent}
            {$backUrlComponent}
        </div>
        <style>
            #{$id} {
                display: flex;
                position: fixed;
                bottom: 0;
                right: 0;
                z-index: 999999;
            }
            @media print { #{$id} { display: none; } }
        </style>

        EOT;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->compileComponents();
    }
}
