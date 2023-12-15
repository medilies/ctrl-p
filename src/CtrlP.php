<?php

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_media_queries/Printing
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/page
 * @see https://www.w3.org/TR/1998/REC-CSS2-19980512/page.html
 * @see https://developer.mozilla.org/en-US/docs/Web/API/Window/print
 */

namespace Medilies\CtrlP;

use RowBloom\RowBloom\Renderers\Sizing\BoxArea;
use RowBloom\RowBloom\Renderers\Sizing\BoxSize;
use RowBloom\RowBloom\Renderers\Sizing\Length;
use RowBloom\RowBloom\Renderers\Sizing\PaperFormat;

class CtrlP
{
    protected AtPage $atPage;

    protected string $html;

    public static function html(string $html): static
    {
        return (new static)->setHtml($html);
    }

    // TODO: ::url('https://example.com')
    // TODO: ::php()

    final public function __construct()
    {
        $this->atPage = new AtPage;
    }

    public function setHtml(string $html): static
    {
        // ? validate HTML
        $this->html = $html;

        return $this;
    }

    public function landscape(bool $set = true): static
    {
        $this->atPage->landscape($set);

        return $this;
    }

    public function portrait(bool $set = true): static
    {
        $this->atPage->portrait($set);

        return $this;
    }

    public function format(PaperFormat|string $format): static
    {
        $this->atPage->format($format);

        return $this;
    }

    public function paperSize(?BoxSize $size = null, ?Length $width = null, ?Length $height = null): static
    {
        $this->atPage->paperSize($size, $width, $height);

        return $this;
    }

    public function margins(BoxArea|array|string $margin): static
    {
        $this->atPage->margins($margin);

        return $this;
    }

    public function get(): string
    {
        $css = $this->atPage->toString();

        return '';
    }

    // ? Changing the value of a dropdown
    // ? Clicking on the page

    public function jsScript(): string
    {
        return file_get_contents(__DIR__.'/ctrl_p.js');
    }
}
