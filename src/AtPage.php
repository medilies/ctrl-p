<?php

namespace Medilies\CtrlP;

use RowBloom\RowBloom\Renderers\Sizing\BoxArea;
use RowBloom\RowBloom\Renderers\Sizing\BoxSize;
use RowBloom\RowBloom\Renderers\Sizing\Length;
use RowBloom\RowBloom\Renderers\Sizing\PageSizeResolver;
use RowBloom\RowBloom\Renderers\Sizing\PaperFormat;
use Stringable;

class AtPage implements Stringable
{
    public ?BoxArea $margin = null;

    public ?PaperFormat $format = null;

    public ?BoxSize $size = null;

    public bool $landscape = false;

    public string $pageSelectorList = '';

    public static function new(): static
    {
        return new static;
    }

    final public function __construct()
    {
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

    public function paperSize(?BoxSize $size = null, ?Length $width = null, ?Length $height = null): static
    {
        $this->size = PageSizeResolver::resolve(null, $size, $width, $height);

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

    public function pageSelectorList(string $pageSelectorList): static
    {
        // Comma separated list
        // Empty by default
        // * A selector is made of either a <page-selector/ident-token> or a <pseudo-page>, followed by zero or more additional <pseudo-page>.
        // * No whitespace is allowed between components of a selector.

        $pageSelectorList = ''; // TODO
        $this->pageSelectorList = $pageSelectorList;

        return $this;
    }

    public function get(): string
    {
        $css = $this->compilePageCss();

        return '';
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->compilePageCss();
    }

    /**
     * @see https://www.w3.org/TR/css-page-3/#syntax-page-selector
     * @see https://developer.mozilla.org/en-US/docs/Web/CSS/@page
     */
    protected function compilePageCss(): string
    {
        // @page <page-selector-list>? { <declaration-rule-list> }
        // <page-selector-list> = <page-selector>#
        // <page-selector> = [ <ident-token>? <pseudo-page>* ]!
        // <pseudo-page> = ':' [ left | right | first | blank ]
        // examples: https://www.w3.org/TR/css-page-3/#example-691ff5b9

        $resolvedSize = PageSizeResolver::resolve(
            $this->format,
            $this->size,
            landscape: $this->landscape,
        );

        $size = "size: {$resolvedSize->width} {$resolvedSize->height}; ";

        $margin = '';
        if (! is_null($this->margin)) {
            $margin .= "margin-top: {$this->margin->top}; ";
            $margin .= "margin-right: {$this->margin->right}; ";
            $margin .= "margin-bottom: {$this->margin->bottom}; ";
            $margin .= "margin-left: {$this->margin->left}; ";
        }

        $declarationRuleList = $size.$margin;

        return '@page '.$this->pageSelectorList.'{ '.$declarationRuleList.'}';
    }
}