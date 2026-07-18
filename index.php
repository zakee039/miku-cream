<?php
/**
 * 基于 Mastercard 设计系统风格的极简响应式主题。
 * 
 * @package miku-cream
 * @author zakee
 * @version 1.2.0
 * @link https://github.com/zakee/miku-cream
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

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
            <p style="color: var(--slate-gray); margin-top: 8px;"><?php _e('抱歉，这里还没有发布任何文章。'); ?></p>
        </article>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php miku_cream_pagination($this); ?>
</div>

<?php $this->need('footer.php'); ?>
