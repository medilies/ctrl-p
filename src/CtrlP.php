<?php

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_media_queries/Printing
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/page
 * @see https://www.w3.org/TR/1998/REC-CSS2-19980512/page.html
 * @see https://developer.mozilla.org/en-US/docs/Web/API/Window/print
 */

namespace Medilies\CtrlP;

use Exception;

class CtrlP
{
    /** @var array<string, AtPage> */
    protected array $atPageRules = [];

    /** @var list */
    protected array $atPageRuleLabelsOrderedList = [];

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

    public function atPageRule(string $label, callable|AtPage $setter): static
    {
        if (! array_key_exists($label, $this->atPageRules)) {
            $this->atPageRuleLabelsOrderedList[] = $label;
        }

        if ($setter instanceof AtPage) {
            $this->atPageRules[$label] = $setter;

            return $this;
        }

        $this->atPageRules[$label] ??= new AtPage;

        $setter($this->atPageRules[$label]);

        return $this;
    }

    public function get(): string
    {
        $css = '';

        foreach ($this->atPageRuleLabelsOrderedList as $label) {
            $css .= $this->atPageRules[$label]->toString();
        }

        $html = $css;

        return $html;
    }

    // ? Changing the value of a dropdown
    // ? Clicking on the page

    public function jsScript(): string
    {
        return file_get_contents(__DIR__.'/ctrl_p.js');
    }
}
