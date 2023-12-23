<?php

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_media_queries/Printing
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/page
 * @see https://www.w3.org/TR/1998/REC-CSS2-19980512/page.html
 * @see https://developer.mozilla.org/en-US/docs/Web/API/Window/print
 */

namespace Medilies\CtrlP;

use Exception;

/**
 * .
 *
 * Delegated to AtPage:
 *
 * @method static landscape(bool $set = true)
 * @method static portrait(bool $set = true)
 * @method static format(PaperFormat|string $format)
 * @method static paperSize(?BoxSize $size = null, ?Length $width = null, ?Length $height = null)
 * @method static margins(BoxArea|array|string $margin)
 * @method static pageSelectorList(string $pageSelectorList)
 *
 * Delegated to JsScript:
 * @method static autoPrint(bool $autoPrint = true)
 * @method static title(?string $title)
 * @method static urlPath(?string $urlPath)
 */
class CtrlP
{
    /** @var array<string, AtPage> */
    protected array $atPageRules = [];

    protected string $html;

    protected ?string $backUrl = null;

    protected bool $printButton = false;

    public static function html(string $html): static
    {
        return (new static)->setHtml($html);
    }

    // TODO: ::url('https://example.com')
    // TODO: ::php()

    final public function __construct(protected JsScript $JsScript = new JsScript)
    {
    }

    public function setHtml(string $html): static
    {
        // ? validate HTML
        $this->html = $html;

        return $this;
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

    /** @phpstan-ignore-next-line */
    public function __call(string $name, $arguments)
    {
        $atPageProxyMethods = [
            'landscape',
            'portrait',
            'format',
            'paperSize',
            'margins',
            'pageSelectorList',
        ];

        $JsScriptProxyMethods = [
            'autoPrint',
            'title',
            'urlPath',
        ];

        if (in_array($name, $atPageProxyMethods, true)) {
            $this->addAtPageRuleIfNotFound('', new AtPage);

            $this->atPageRules['']->$name(...$arguments);

            return $this;
        }

        if (in_array($name, $JsScriptProxyMethods, true)) {
            $this->JsScript->$name(...$arguments);

            return $this;
        }
    }

    // ========================================================================
    // @page
    // ========================================================================

    protected function addAtPageRuleIfNotFound(string $label, AtPage $atPage): static
    {
        if (array_key_exists($label, $this->atPageRules)) {
            return $this;
        }

        return $this->setAtPageRule($label, $atPage);
    }

    protected function setAtPageRule(string $label, AtPage $atPage): static
    {
        if (is_numeric($label)) {
            throw new Exception('Label cannot be a numeric value');
        }

        $this->atPageRules[$label] = $atPage;

        return $this;
    }

    public function removeAtPageRuleIfFound(string $label): static
    {
        if (array_key_exists($label, $this->atPageRules)) {
            unset($this->atPageRules[$label]);
        }

        return $this;
    }

    public function atPageRule(string $label, callable|AtPage $setter): static
    {
        if ($label === '') {
            throw new Exception('Label cannot be empty');
        }

        if ($setter instanceof AtPage) {
            return $this->setAtPageRule($label, $setter);
        }

        $this->addAtPageRuleIfNotFound($label, new AtPage);

        $setter($this->atPageRules[$label]);

        return $this;
    }

    // ========================================================================
    //
    // ========================================================================

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

    protected function controlComponents(): string
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

    public function get(): string
    {
        $css = '';

        foreach ($this->atPageRules as $atRule) {
            $css .= $atRule->toString();
        }

        $script = $this->JsScript->toString();

        $html = $this->html;

        $html = str_replace(
            '@CtrlP',
            $this->controlComponents()."<style>\n{$css}</style>\n<script>\n{$script}</script>",
            $html
        );

        return $html;
    }
}
