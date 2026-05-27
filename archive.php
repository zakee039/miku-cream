<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="archive-header">
    <h1 class="archive-title">
        <?php $this->archiveTitle([
            'category' => _t('分类【%s】下的文章'),
            'search'   => _t('包含关键字【%s】的文章'),
            'tag'      => _t('标签【%s】下的文章'),
            'author'   => _t('作者【%s】发布的文章')
        ], '', ''); ?>
    </h1>
    <?php if ($this->getDescription()): ?>
        <p class="archive-description"><?php echo $this->getDescription(); ?></p>
    <?php endif; ?>
</div>

<div class="post-list" role="main">
    <?php if ($this->have()): ?>
        <?php while ($this->next()): ?>
            <article class="post-card" itemscope itemtype="http://schema.org/BlogPosting">
                <div class="post-header">
                    <!-- Eyebrow Category -->
                    <span class="post-eyebrow">
                        <?php $this->category(',', false); ?>
                    </span>
                    
                    <h2 class="post-title" itemprop="name headline">
                        <a itemprop="url" href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
                    </h2>
                    
                    <?php postMeta($this); ?>
                </div>
                
                <div class="post-excerpt" itemprop="articleBody">
                    <?php $this->excerpt(150, '...'); ?>
                </div>
                
                <div class="post-footer">
                    <a href="<?php $this->permalink() ?>" class="btn-primary" title="<?php $this->title() ?>">
                        <?php _e('阅读全文'); ?> &rarr;
                    </a>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <article class="post-card" style="grid-column: 1 / -1; min-height: 200px; text-align: center; justify-content: center;">
            <h2 class="post-title"><?php _e('没有找到内容'); ?></h2>
            <p style="color: var(--slate-gray); margin-top: 8px;"><?php _e('抱歉，该筛选条件下没有找到任何文章。'); ?></p>
        </article>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php $this->pageNav('&laquo; ' . _t('前一页'), _t('后一页') . ' &raquo;', 3, '', array('wrapTag' => 'ol', 'itemTag' => 'li')); ?>
</div>

<?php $this->need('footer.php'); ?>
