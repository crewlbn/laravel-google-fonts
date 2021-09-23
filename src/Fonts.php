<?php

namespace Spatie\GoogleFonts;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Fonts implements Htmlable
{
    protected string $googleFontsUrl;
    protected ?string $localizedUrl = null;
    protected ?string $localizedCss = null;
    protected bool $preferInline = false;

    public function __construct(
        string $googleFontsUrl,
        ?string $localizedUrl = null,
        ?string $localizedCss = null,
        bool $preferInline = false
    ) {
        $this->preferInline = $preferInline;
        $this->localizedCss = $localizedCss;
        $this->localizedUrl = $localizedUrl;
        $this->googleFontsUrl = $googleFontsUrl;
    }

    public function inline(): HtmlString
    {
        if (! $this->localizedCss) {
            return $this->fallback();
        }

        return new HtmlString(<<<HTML
            <style>{$this->localizedCss}</style>
        HTML);
    }

    public function link(): HtmlString
    {
        if (! $this->localizedUrl) {
            return $this->fallback();
        }

        return new HtmlString(<<<HTML
            <link href="{$this->localizedUrl}" rel="stylesheet" type="text/css">
        HTML);
    }

    public function fallback(): HtmlString
    {
        return new HtmlString(<<<HTML
            <link href="{$this->googleFontsUrl}" rel="stylesheet" type="text/css">
        HTML);
    }

    public function toHtml(): HtmlString
    {
        return $this->preferInline ? $this->inline() : $this->link();
    }
}
