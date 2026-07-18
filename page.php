<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<!-- Independent pages intentionally keep their own HTML/CSS and native browser layout. -->
<article class="page-plain" itemscope itemtype="http://schema.org/BlogPosting">
    <header>
        <h1 itemprop="name headline">
            <?php $this->title() ?>
        </h1>
    </header>

    <div itemprop="articleBody">
        <?php $this->content(); ?>
    </div>
</article>

<?php $this->need('comments.php'); ?>

<?php $this->need('footer.php'); ?>
