<?php

namespace Medilies\CtrlP;

use RowBloom\CssSizing\BoxArea;
use RowBloom\CssSizing\BoxSize;
use RowBloom\CssSizing\Length;
use RowBloom\CssSizing\PaperFormat;
use Stringable;

class AtPage implements Stringable
{
    public ?BoxArea $margin = null;

    public ?PaperFormat $format = null;

    public ?BoxSize $size = null;

    public bool $landscape = false;

    public string $pageSelectorList = '';

    protected bool $sizeIsFormat;

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

        $this->sizeIsFormat = true;

        return $this;
    }

    // ! size under 10cm X 10cm causes ignoring all rules
    public function paperSize(Length|string $width, Length|string $height): static
    {
        $this->size = new BoxSize($width, $height);

        $this->sizeIsFormat = false;

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

        $resolvedSize = $this->resolveSize();

        $size = "size: {$resolvedSize->width} {$resolvedSize->height}; ";

        $margin = '';
        if (! is_null($this->margin)) {
            $margin .= "margin-top: {$this->margin->top}; ";
            $margin .= "margin-right: {$this->margin->right}; ";
            $margin .= "margin-bottom: {$this->margin->bottom}; ";
            $margin .= "margin-left: {$this->margin->left}; ";
        }

        $declarationRuleList = $size.$margin;

        return '@page '.$this->pageSelectorList.'{ '.$declarationRuleList.'}'."\n";
    }

    protected function resolveSize(): BoxSize
    {
        if (! $this->sizeIsFormat) {
            return $this->size;
        }

        $size = $this->format->size();

        return $this->landscape ? $size->toLandscape() : $size->toPortrait();
    }
}
