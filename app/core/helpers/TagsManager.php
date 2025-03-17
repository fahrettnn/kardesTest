<?php 

namespace App\Core\Helpers;

class TagsManager
{
    /**
     * @var array Store added HTML tags / Eklenen HTML etiketlerini saklar
     */
    private $tags = [];

    /**
     * @var array Store added script content / Eklenen script içeriğini saklar
     */
    private $scripts = [];

    /**
     * Add an HTML tag with attributes and optional content.
     * Özellikler ve isteğe bağlı içerikle bir HTML etiketi ekler.
     *
     * @param string $tag HTML tag name / HTML etiket adı
     * @param array $attributes Associative array of attributes / Özelliklerin ilişkisel dizisi
     * @param string|null $content Tag content / Etiket içeriği
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addTag($tag, array $attributes = [], $content = null)
    {
        // Validate tag name
        if (!is_string($tag) || empty($tag)) {
            throw new \InvalidArgumentException('Invalid tag name');
        }

        // Build attributes string
        $tagAttributes = '';
        foreach ($attributes as $key => $value) {
            $escapedValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); // Güvenlik için özellik değerlerini escape et
            $tagAttributes .= " $key=\"$escapedValue\"";
        }

        // Build full tag and add to tags array
        $tagContent = ($content !== null) ? htmlspecialchars($content, ENT_QUOTES, 'UTF-8') : '';
        $selfClosingTags = ['img', 'input', 'br', 'hr', 'meta', 'link'];
        if (in_array($tag, $selfClosingTags)) {
            $this->tags[] = "<$tag$tagAttributes />";
        } else {
            $this->tags[] = "<$tag$tagAttributes>$tagContent</$tag>";
        }

        return $this;
    }

    /**
     * Add a script tag with inline JavaScript or a source URL.
     * Harici bir script URL'si veya inline JavaScript ile script etiketi ekler.
     *
     * @param string $script Inline JavaScript or script URL / Inline JavaScript veya script URL
     * @param bool $isExternal Set to true if script is an external source / Script harici bir kaynaksa true yap
     * @return $this
     */
    public function addScript($script, $isExternal = false)
    {
        if ($isExternal) {
            $this->scripts[] = '<script src="' . htmlspecialchars($script, ENT_QUOTES, 'UTF-8') . '"></script>';
        } else {
            $this->scripts[] = "<script>" . PHP_EOL . $script . PHP_EOL . "</script>";
        }

        return $this;
    }

    /**
     * Add a style tag for inline CSS or a stylesheet link.
     * Inline CSS veya bir stil dosyası bağlantısı için style etiketi ekler.
     *
     * @param string $style Inline CSS or stylesheet URL / Inline CSS veya stil dosyası URL
     * @param bool $isExternal Set to true if style is an external source / Harici bir kaynaksa true yap
     * @return $this
     */
    public function addStyle($style, $isExternal = false)
    {
        if ($isExternal) {
            $this->tags[] = '<link rel="stylesheet" href="' . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') . '" />';
        } else {
            $this->tags[] = "<style>" . PHP_EOL . $style . PHP_EOL . "</style>";
        }

        return $this;
    }

    /**
     * Render all tags and scripts.
     * Tüm etiketleri ve scriptleri render eder.
     *
     * @return void
     */
    public function render()
    {
        echo implode(PHP_EOL, $this->tags) . PHP_EOL;
        echo implode(PHP_EOL, $this->scripts) . PHP_EOL;
    }

    /**
     * Clear all stored tags.
     * Tüm saklanan etiketleri temizler.
     *
     * @return $this
     */
    public function clearTags()
    {
        $this->tags = [];
        return $this;
    }

    /**
     * Clear all stored scripts.
     * Tüm saklanan scriptleri temizler.
     *
     * @return $this
     */
    public function clearScripts()
    {
        $this->scripts = [];
        return $this;
    }

    /**
     * Get all stored tags.
     * Saklanan tüm etiketleri döndürür.
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get all stored scripts.
     * Saklanan tüm scriptleri döndürür.
     *
     * @return array
     */
    public function getScripts()
    {
        return $this->scripts;
    }
}
