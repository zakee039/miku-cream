<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * 主题自定义设置
 */
function themeConfig($form)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('站点 LOGO 地址'),
        _t('在这里填入一个图片 URL 地址, 以在网站标题前加上一个 LOGO')
    );
    $form->addInput($logoUrl->addRule('url', _t('请填写一个合法的URL地址')));
}

/**
 * 辅助函数：输出文章元数据
 */
function postMeta(\Widget\Archive $archive)
{
    ?>
    <ul class="post-meta">
        <li itemprop="author" itemscope itemtype="http://schema.org/Person">
            <?php _e('作者'); ?>: <a itemprop="name" href="<?php $archive->author->permalink(); ?>" rel="author"><?php $archive->author(); ?></a>
        </li>
        <li><?php _e('时间'); ?>:
            <time datetime="<?php $archive->date('c'); ?>" itemprop="datePublished"><?php $archive->date(); ?></time>
        </li>
        <li><?php _e('分类'); ?>: <?php $archive->category(','); ?></li>
        <li itemprop="interactionCount">
            <a itemprop="discussionUrl" href="<?php $archive->permalink() ?>#comments"><?php $archive->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></a>
        </li>
    </ul>
    <?php
}

/**
 * 辅助函数：获取分类层次结构树
 * 返回一个包含一级分类及其子分类的数组
 */
function getCategoryTree()
{
    // 获取分类行数据组件 (使用别名避免与全局共享实例冲突导致数据重复)
    $categories = \Widget\Metas\Category\Rows::allocWithAlias('miku_cream_menu');
    $allCats = [];

    // 执行并加载数据 (Typecho 在 alloc 实例化时已自动执行，无需手动调用以防数据重复)

    while ($categories->next()) {
        $allCats[] = [
            'mid'       => $categories->mid,
            'parent'    => $categories->parent,
            'name'      => $categories->name,
            'slug'      => $categories->slug,
            'permalink' => $categories->permalink,
            'count'     => $categories->count
        ];
    }

    $byParent = [];
    foreach ($allCats as $cat) {
        $byParent[$cat['parent']][] = $cat;
    }

    $tree = [];
    if (isset($byParent[0])) {
        foreach ($byParent[0] as $parentCat) {
            $parentCat['children'] = isset($byParent[$parentCat['mid']]) ? $byParent[$parentCat['mid']] : [];
            $tree[] = $parentCat;
        }
    }

    return $tree;
}
