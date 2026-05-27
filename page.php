<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<article class="post-container" itemscope itemtype="http://schema.org/BlogPosting">
    <header class="post-header">
        <h1 class="post-title" itemprop="name headline">
            <?php $this->title() ?>
        </h1>
    </header>

    <div class="post-content" itemprop="articleBody">
        <?php $this->content(); ?>
    </div>
</article>

<?php $this->need('comments.php'); ?>

<?php $this->need('footer.php'); ?>
