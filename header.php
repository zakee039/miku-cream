<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <link rel="shortcut icon" href="<?php $this->options->themeUrl('favicon.ico'); ?>" type="image/x-icon" />
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Title Tag -->
    <title><?php $this->archiveTitle([
            'category' => _t('分类 %s 下的文章'),
            'search'   => _t('包含关键字 %s 的文章'),
            'tag'      => _t('标签 %s 下的文章'),
            'author'   => _t('%s 发布的文章')
        ], '', ' - '); ?><?php $this->options->title(); ?></title>

    <!-- Google Fonts & Stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/github-dark.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.17.0/dist/katex.min.css" integrity="sha384-vlBdW0r3AcZO/HboRPznQNowvexd3fY8qHOWkBi5q7KGgqJ+F48+DceybYmrVbmB" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css?v=1.1.13'); ?>">

    <!-- Built-in code and formula rendering (falls back to readable source if a CDN is unavailable) -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.17.0/dist/katex.min.js" integrity="sha384-AtrdNsnxl/75rvBneBVH7DtOvCxSVahR2zWqle1coBKd8DEmLoviqNeJSx64gNAs" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.17.0/dist/contrib/auto-render.min.js" integrity="sha384-bjyGPfbij8/NDKJhSGZNP/khQVgtHUE5exjm4Ydllo42FwIgYsdLO2lXGmRBf5Mz" crossorigin="anonymous"></script>

    <!-- Typecho Header Outputs -->
    <?php $this->header(); ?>
</head>
<body>

<!-- Mobile Left-Top Floating Toggle -->
<button id="mobileMenuBtn" class="mobile-menu-btn" aria-label="打开菜单" aria-expanded="false" aria-controls="mobileDrawer">&gt;</button>

<!-- Mobile Off-Canvas Drawer -->
<div id="mobileDrawerOverlay" class="mobile-drawer-overlay"></div>
<div id="mobileDrawer" class="mobile-drawer" aria-hidden="true">
    <div class="mobile-nav-content">
        <div id="mobileMenuPane" class="mobile-menu-pane">
            <ul class="mobile-list" id="mobileMenuList">
                <!-- Populated dynamically via JS -->
            </ul>
        </div>
    </div>
</div>

<!-- Main Site Header -->
<header id="header">
    <div class="container">
        <div class="nav-pill">
            <div class="nav-top-row">
                <!-- Branding / Logo -->
                <div class="site-identity">
                    <?php if ($this->options->logoUrl): ?>
                        <a class="site-logo" href="<?php $this->options->siteUrl(); ?>">
                            <img src="<?php $this->options->logoUrl() ?>" alt="<?php $this->options->title() ?>"/>
                        </a>
                    <?php else: ?>
                        <a class="site-logo" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title() ?></a>
                        <span class="site-description"><?php $this->options->description() ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Onion Divider -->
            <div class="onion-divider">
                <div class="onion-left"></div>
                <div class="onion-middle"></div>
                <div class="onion-right"></div>
            </div>

            <!-- Desktop Horizontal Menu with Hover Dropdowns -->
            <?php 
            $categoryTree = getCategoryTree();
            \Widget\Contents\Page\Rows::alloc()->to($pages);
            
            // Format pages for JS and local iterations
            $pageList = [];
            while ($pages->next()) {
                $pageList[] = [
                    'title' => $pages->title,
                    'permalink' => $pages->permalink,
                    'slug' => $pages->slug
                ];
            }
            ?>
            <div class="nav-bottom-row">
                <ul class="nav-menu">
                    <li class="nav-item <?php if($this->is('index')): ?>active<?php endif; ?>">
                        <a class="nav-link" href="<?php $this->options->siteUrl(); ?>">首页</a>
                    </li>
                    <?php foreach ($pageList as $p): ?>
                    <li class="nav-item <?php if($this->is('page', $p['slug'])): ?>active<?php endif; ?>">
                        <a class="nav-link" href="<?php echo htmlspecialchars($p['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                    </li>
                    <?php endforeach; ?>
                    <?php foreach ($categoryTree as $cat): ?>
                    <li class="nav-item <?php if($this->is('category', $cat['slug'])): ?>active<?php endif; ?>">
                        <a class="nav-link" href="<?php echo htmlspecialchars($cat['permalink'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>
                            <?php if (!empty($cat['children'])): ?>
                                <span style="font-size: 10px; margin-left: 2px;">▼</span>
                            <?php endif; ?>
                        </a>
                        <?php if (!empty($cat['children'])): ?>
                        <ul class="dropdown-menu">
                            <?php foreach ($cat['children'] as $child): ?>
                            <li class="dropdown-item">
                                <a href="<?php echo htmlspecialchars($child['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($child['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Search Form -->
                <form class="search-form" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                    <input type="text" name="s" class="search-input" placeholder="<?php _e('输入关键字搜索'); ?>" autocomplete="off"/>
                    <button type="submit" class="search-btn" aria-label="Search">
                        <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const mobileDrawerOverlay = document.getElementById('mobileDrawerOverlay');
    const mobileMenuList = document.getElementById('mobileMenuList');

    // Data passed from PHP
    const pages = <?php echo json_encode($pageList, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    const categoryTree = <?php echo json_encode($categoryTree, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    const siteUrl = <?php echo json_encode((string) $this->options->siteUrl, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

    let isOpen = false;

    // Toggle menu open/close
    mobileMenuBtn.addEventListener('click', function() {
        if (isOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    mobileDrawerOverlay.addEventListener('click', closeMenu);

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && isOpen) {
            closeMenu();
        }
    });

    function createLink(label, href) {
        const link = document.createElement('a');
        link.href = href;
        link.textContent = label;
        link.addEventListener('click', closeMenu);
        return link;
    }

    function openMenu() {
        isOpen = true;
        mobileDrawer.classList.add('active');
        mobileDrawerOverlay.classList.add('active');
        mobileMenuBtn.textContent = '<';
        mobileMenuBtn.setAttribute('aria-label', '关闭菜单');
        mobileMenuBtn.setAttribute('aria-expanded', 'true');
        mobileDrawer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('menu-open');
        renderFirstLevel();
        const firstFocusable = mobileMenuList.querySelector('a, button');
        if (firstFocusable) firstFocusable.focus();
    }

    function closeMenu() {
        isOpen = false;
        mobileDrawer.classList.remove('active');
        mobileDrawerOverlay.classList.remove('active');
        mobileMenuBtn.textContent = '>';
        mobileMenuBtn.setAttribute('aria-label', '打开菜单');
        mobileMenuBtn.setAttribute('aria-expanded', 'false');
        mobileDrawer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('menu-open');
        mobileMenuBtn.focus();
    }

    // Render first level menu (Home, Pages, Top-level categories)
    function renderFirstLevel() {
        mobileMenuList.innerHTML = '';

        // 1. Home Link
        const homeLi = document.createElement('li');
        homeLi.className = 'mobile-list-item';
        homeLi.appendChild(createLink('首页', siteUrl));
        mobileMenuList.appendChild(homeLi);

        // 2. Static Pages
        pages.forEach(p => {
            const pageLi = document.createElement('li');
            pageLi.className = 'mobile-list-item';
            pageLi.appendChild(createLink(p.title, p.permalink));
            mobileMenuList.appendChild(pageLi);
        });

        // 3. First-Level Categories
        categoryTree.forEach(cat => {
            const catLi = document.createElement('li');
            catLi.className = 'mobile-list-item';

            if (cat.children && cat.children.length > 0) {
                // If it has children, clicking switches to second level
                const btn = document.createElement('button');
                const label = document.createElement('span');
                label.textContent = cat.name;
                const indicator = document.createElement('span');
                indicator.textContent = '>';
                btn.appendChild(label);
                btn.appendChild(indicator);
                btn.addEventListener('click', function() {
                    renderSecondLevel(cat);
                });
                catLi.appendChild(btn);
            } else {
                // If no children, direct navigation link
                const link = document.createElement('a');
                link.href = cat.permalink;
                link.textContent = cat.name;
                link.addEventListener('click', closeMenu);
                catLi.appendChild(link);
            }
            mobileMenuList.appendChild(catLi);
        });
    }

    // Render second level menu (Subcategories) for a given parent category
    function renderSecondLevel(parentCat) {
        mobileMenuList.innerHTML = '';

        // Back button
        const backBtnLi = document.createElement('li');
        const backBtn = document.createElement('button');
        backBtn.className = 'mobile-back-btn';
        backBtn.textContent = '< 返回一级分类';
        backBtn.addEventListener('click', renderFirstLevel);
        backBtnLi.appendChild(backBtn);
        mobileMenuList.appendChild(backBtnLi);

        // Include "View All" link for the parent category itself
        const allParentLi = document.createElement('li');
        allParentLi.className = 'mobile-list-item';
        const allParentLink = createLink('查看全部: ' + parentCat.name, parentCat.permalink);
        allParentLink.style.fontWeight = '700';
        allParentLi.appendChild(allParentLink);
        mobileMenuList.appendChild(allParentLi);

        // Subcategories
        parentCat.children.forEach(child => {
            const childLi = document.createElement('li');
            childLi.className = 'mobile-list-item';
            childLi.appendChild(createLink(child.name, child.permalink));
            mobileMenuList.appendChild(childLi);
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.post-content').forEach(function(content) {
        if (window.hljs) {
            content.querySelectorAll('pre code').forEach(function(block) {
                window.hljs.highlightElement(block);
            });
        }

        if (window.renderMathInElement) {
            window.renderMathInElement(content, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError: false,
                ignoredClasses: ['no-math']
            });
        }
    });
});
</script>

<div class="container main-content">
