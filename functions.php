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

/**
 * 自定义分页导航，支持文章归档和评论
 */
function miku_cream_pagination($archive)
{
    ob_start();
    // 使用大 splitPage 强制输出所有页码链接，方便我们提取所有页面的 URL
    $archive->pageNav('&laquo;', '&raquo;', 9999, '...', array('wrapTag' => 'ol', 'itemTag' => 'li'));
    $html = ob_get_clean();

    if (empty($html)) {
        return;
    }

    $page_urls = [];
    $current_page = 1;
    $prev_url = '';
    $next_url = '';

    if (preg_match_all('#<li([^>]*)>(.*?)</li>#is', $html, $li_matches)) {
        foreach ($li_matches[0] as $i => $li_full) {
            $li_attr = $li_matches[1][$i];
            $li_content = $li_matches[2][$i];
            
            // 提取上一页/下一页链接
            if (strpos($li_attr, 'prev') !== false && preg_match('#href="([^"]+)"#', $li_content, $m)) {
                $prev_url = $m[1];
            } elseif (strpos($li_attr, 'next') !== false && preg_match('#href="([^"]+)"#', $li_content, $m)) {
                $next_url = $m[1];
            } else {
                // 提取页码链接
                if (strpos($li_attr, 'current') !== false) {
                    if (preg_match('#([0-9]+)#', strip_tags($li_content), $m)) {
                        $page_num = intval($m[1]);
                        $current_page = $page_num;
                    }
                    if (preg_match('#href="([^"]+)"#', $li_content, $m)) {
                        $page_urls[$current_page] = $m[1];
                    }
                } else {
                    if (preg_match('#href="([^"]+)"[^>]*>([0-9]+)</a>#', $li_content, $m)) {
                        $page_urls[intval($m[2])] = $m[1];
                    }
                }
            }
        }
    }

    $total_pages = count($page_urls) > 0 ? max(max(array_keys($page_urls)), $current_page) : $current_page;

    if ($total_pages <= 1) {
        return;
    }

    $C = $current_page;
    $T = $total_pages;

    // 计算需要显示的页码
    $candidates = [];
    $candidates[] = 1; // 首页
    if ($C - 1 >= 1) {
        $candidates[] = $C - 1; // 前一页
    }
    $candidates[] = $C; // 当前页
    if ($C + 1 <= $T) {
        $candidates[] = $C + 1; // 后一位
    }
    if ($C + 2 <= $T) {
        $candidates[] = $C + 2; // 后两位
    }
    $candidates[] = $T; // 尾页

    $pages = array_unique($candidates);
    sort($pages);

    echo '<ol class="page-navigator">';

    // 上一页按钮
    if ($C > 1) {
        $prev_link = isset($page_urls[$C - 1]) ? $page_urls[$C - 1] : $prev_url;
        echo '<li class="prev"><a href="' . htmlspecialchars($prev_link) . '">&laquo; ' . _t('上一页') . '</a></li>';
    }

    // 循环输出页码
    for ($i = 0; $i < count($pages); $i++) {
        $p = $pages[$i];
        
        // 检查是否需要插入省略号
        if ($i > 0 && $p - $pages[$i - 1] > 1) {
            echo '<li><span>...</span></li>';
        }
        
        if ($p == $C) {
            echo '<li class="current"><span>' . $p . '</span></li>';
        } else {
            $link = isset($page_urls[$p]) ? $page_urls[$p] : '';
            echo '<li><a href="' . htmlspecialchars($link) . '">' . $p . '</a></li>';
        }
    }

    // 下一页按钮
    if ($C < $T) {
        $next_link = isset($page_urls[$C + 1]) ? $page_urls[$C + 1] : $next_url;
        echo '<li class="next"><a href="' . htmlspecialchars($next_link) . '">' . _t('下一页') . ' &raquo;</a></li>';
    }

    echo '</ol>';
}

