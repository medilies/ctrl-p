<?php

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_media_queries/Printing
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/page
 * @see https://www.w3.org/TR/1998/REC-CSS2-19980512/page.html
 * @see https://developer.mozilla.org/en-US/docs/Web/API/Window/print
 */

namespace Medilies\CtrlP;

/**
 * .
 *
 * Delegated to AtPage:
 * @method static landscape(bool $set = true)
 * @method static portrait(bool $set = true)
 * @method static format(PaperFormat|string $format)
 * @method static paperSize(?BoxSize $size = null, ?Length $width = null, ?Length $height = null)
 * @method static margins(BoxArea|array|string $margin)
 * @method static pageSelectorList(string $pageSelectorList)
 */
class CtrlP
{
    /** @var array<string, AtPage> */
    protected array $atPageRules;

    /** @var list */
    protected array $atPageRuleLabelsOrderedList = [];

    protected string $html;

    public static function html(string $html): static
    {
        return (new static)->setHtml($html);
    }

    // TODO: ::url('https://example.com')
    // TODO: ::php()

    final public function __construct(
        protected jsScript $jsScript = new jsScript,
        AtPage $defaultAtRule = new AtPage,
    ) {
        $this->atPageRules = ['' => $defaultAtRule];

        $this->atPageRuleLabelsOrderedList[] = '';
    }

    public function setHtml(string $html): static
    {
        // ? validate HTML
        $this->html = $html;

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
            $this->atPageRules['']->$name(...$arguments);

            return $this;
        }
    }

    // ========================================================================
    // @page
    // ========================================================================

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

    // ========================================================================
    //
    // ========================================================================

    public function get(): string
    {
        $css = '';

        foreach ($this->atPageRuleLabelsOrderedList as $label) {
            $css .= $this->atPageRules[$label]->toString();
        }

        $script = $this->jsScript->toString();

        $html = $this->html;
        $html = str_replace('@ctrl_p_css', "<style>{$css}</style>", $html);
        $html = str_replace('@ctrl_p_script', "<script>{$script}</script>", $html);

        return $html;
    }
}
