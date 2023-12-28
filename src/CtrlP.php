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
 * @method static paperSize(Length|string $width = null, Length|string $height = null)
 * @method static margins(BoxArea|array|string $margin)
 * @method static pageSelectorList(string $pageSelectorList)
 */
class CtrlP
{
    /** @var array<string, AtPage> */
    protected array $atPageRules = [];

    protected string $html;

    protected bool $autoPrint = true;

    protected ?string $title = null;

    protected ?string $urlPath = null;

    protected ?string $backUrl = null;

    protected bool $printButton = false;

    public static function html(string $html): static
    {
        return (new static)->setHtml($html);
    }

    /**
     * Be careful to not pass user given templates here.
     */
    public static function php(string $template, array $data): static
    {
        $html = (function (string $template, array $data) {
            ob_start();

            extract($data);
            eval(' ?>'.$template.'<?php ');

            return ob_get_clean();
        })($template, $data);

        if ($html === false) {
            throw new Exception("Couldn't render the given PHP template.");
        }

        return static::html($html);
    }

    // TODO: ::url('https://example.com')

    final public function __construct()
    {
    }

    public function setHtml(string $html): static
    {
        $this->html = $html;

        return $this;
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

        if (in_array($name, $atPageProxyMethods, true)) {
            $this->addAtPageRuleIfNotFound('', new AtPage);

            $this->atPageRules['']->$name(...$arguments);

            return $this;
        }
    }

    // ========================================================================
    // @page
    // ========================================================================

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

    // ========================================================================
    // ControlComponents
    // ========================================================================

    protected function compileControlComponents(): string
    {
        if (is_null($this->backUrl) && ! $this->printButton) {
            return '';
        }

        $id = 'ctrl-p-control';

        $backUrlAnchor = ! is_null($this->backUrl) ?
            "<a href='{$this->backUrl}'>
                <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-arrow-back-up' width='16' height='16' viewBox='0 0 24 24' stroke-width='3' stroke='#ffffff' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                  <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                  <path d='M9 14l-4 -4l4 -4' />
                  <path d='M5 10h11a4 4 0 1 1 0 8h-1' />
                </svg>
            </a>" :
            '';

        $printButton = $this->printButton ?
            '<button onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="16" height="16" viewBox="0 0 24 24" stroke-width="3" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                    <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                </svg>
            </button>' :
            '';

        return <<<EOT

        <div id='{$id}'>
            {$printButton}
            {$backUrlAnchor}
            <style>
                div#{$id} {
                    position: fixed;
                    bottom: 0;
                    right: 0;
                    z-index: 999999;
                    display: flex;
                    gap: 4px;
                    padding: 4px;
                }
                div#{$id} > a, div#{$id} > button {
                    display: block;
                    padding: 3px 6px;
                    border-radius: 4px;
                    background: #333;
                    color: #fff;
                    font-weight: bold;
                }
                div#{$id} > a:hover, div#{$id} > button:hover {
                    background: #444;
                }
                @media print { #{$id} { display: none !important; } }
            </style>
        </div>

        EOT;
    }

    // ========================================================================
    // JS
    // ========================================================================

    protected function compileJs(): string
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

    // ========================================================================
    // Enders
    // ========================================================================

    public function get(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        $css = '';

        foreach ($this->atPageRules as $atRule) {
            $css .= $atRule->toString();
        }

        $script = $this->compileJs();

        $html = $this->html;

        $html = str_replace(
            '@CtrlP',
            $this->compileControlComponents()."<style>\n{$css}</style>\n<script>\n{$script}</script>",
            $html
        );

        return $html;
    }
}
