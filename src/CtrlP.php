<?php

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_media_queries/Printing
 * @see https://www.w3.org/TR/css-page-3
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/@page
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/page
 * @see https://www.w3.org/TR/1998/REC-CSS2-19980512/page.html
 * @see https://developer.mozilla.org/en-US/docs/Web/API/Window/print
 */

namespace Medilies\CtrlP;

use RowBloom\RowBloom\Renderers\Sizing\BoxArea;
use RowBloom\RowBloom\Renderers\Sizing\Length;
use RowBloom\RowBloom\Renderers\Sizing\PaperFormat;

class CtrlP
{
    // https://github.dev/spatie/browsershot
    // https://codepen.io/medilies/pen/VwgRyGb
    // https://spatie.be/docs/browsershot/v2/introduction
    // https://stackoverflow.com/questions/3338642/updating-address-bar-with-new-url-without-hash-or-reloading-the-page

    protected ?BoxArea $margin = null;

    protected ?PaperFormat $format = null;

    protected ?Length $width = null;

    protected ?Length $height = null;

    protected bool $landscape = false;

    protected string $html;

    public static function html(string $html): static
    {
        return (new static)->setHtml($html);
    }

    // TODO: ::url('https://example.com')
    // TODO: ::php()

    final public function __construct()
    {
    }

    public function setHtml(string $html): static
    {
        // ? validate HTML
        $this->html = $html;

        return $this;
    }

    public function landscape(bool $set = true): static
    {
        $this->landscape = $set;

        return $this;
    }

    public function portrait(bool $set = true): static
    {
        $this->landscape = ! $set;

        return $this;
    }

    public function format(PaperFormat|string $format): static
    {
        $this->format = $format instanceof PaperFormat ?
            $format :
            PaperFormat::from($format);

        return $this;
    }

    public function width(Length|string $width): static
    {
        $this->width = $width instanceof Length ?
            $width :
            Length::fromDimension($width);

        return $this;
    }

    public function height(Length|string $height): static
    {
        $this->height = $height instanceof Length ?
            $height :
            Length::fromDimension($height);

        return $this;
    }

    // TODO: ->margins($top, $right, $bottom, $left)
    public function margins(BoxArea|array|string $margin): static
    {
        $this->margin = $margin instanceof BoxArea ?
            $margin :
            BoxArea::new($margin);

        return $this;
    }

    public function get(): string
    {
        $css = $this->compileCss();

        return '';
    }

    private function compileCss(): string
    {
        return '';
    }

    // ! ->showBrowserHeaderAndFooter()
    // ! ->headerHtml($someHtml)
    // ! ->footerHtml($someHtml)
    // ! ->hideHeader()
    // ! ->hideFooter()
    // ! ->showBackground()
    // ! ->transparentBackground()
    // ! ->scale(0.5)
    // ! ->pages('1-5, 8, 11-13')
    // ! ->initialPageNumber(8)

    // ? Changing the value of a dropdown
    // ? Clicking on the page

    public function jsScript(): string
    {
        return file_get_contents(__DIR__.'/ctrl_p.js');
    }
}
