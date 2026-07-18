<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<!-- Independent pages intentionally keep their own HTML/CSS and native browser layout. -->
<?php
$pageSlug = strtolower(trim((string) $this->slug));
$pageTitle = trim((string) $this->title);
$isAboutPage = in_array($pageSlug, array('about', 'guanyu', 'guan-yu'), true) || $pageTitle === '关于';
?>
<article class="page-plain<?php echo $isAboutPage ? ' about-page' : ''; ?>" itemscope itemtype="http://schema.org/BlogPosting">
    <header>
        <h1 itemprop="name headline">
            <?php $this->title() ?>
        </h1>
    </header>

    <div itemprop="articleBody">
        <?php
        ob_start();
        $this->content();
        $pageContent = ob_get_clean();

        /*
         * Independent pages may contain a complete HTML layout inside a
         * Markdown code fence. Restore only blocks that clearly contain
         * HTML structure; ordinary code examples remain untouched.
         */
        $pageContent = preg_replace_callback(
            '#<pre(?:\s[^>]*)?>\s*<code(?:\s[^>]*)?>(.*?)</code>\s*</pre>#is',
            static function ($match) {
                $html = html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $hasHtmlTag = preg_match(
                    '/<\s*(?:p|div|section|article|span|h[1-6]|ul|ol|li|img|a|table|blockquote)\b/i',
                    $html
                );
                $hasClosingTag = preg_match('/<\s*\/\s*[a-z][^>]*>/i', $html);

                return ($hasHtmlTag && $hasClosingTag) ? $html : $match[0];
            },
            $pageContent
        );

        echo $pageContent;
        ?>
    </div>
</article>

<?php $this->need('comments.php'); ?>

<?php $this->need('footer.php'); ?>
