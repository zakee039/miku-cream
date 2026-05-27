<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
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
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css?v=1.1.2'); ?>">

    <!-- Typecho Header Outputs -->
    <?php $this->header(); ?>
</head>
<body>

<!-- Mobile Left-Top Floating Toggle -->
<button id="mobileMenuBtn" class="mobile-menu-btn" aria-label="Toggle Menu">&gt;</button>

<!-- Mobile Off-Canvas Drawer -->
<div id="mobileDrawerOverlay" class="mobile-drawer-overlay"></div>
<div id="mobileDrawer" class="mobile-drawer">
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
                        <a class="nav-link" href="<?php echo $p['permalink']; ?>"><?php echo $p['title']; ?></a>
                    </li>
                    <?php endforeach; ?>
                    <?php foreach ($categoryTree as $cat): ?>
                    <li class="nav-item <?php if($this->is('category', $cat['slug'])): ?>active<?php endif; ?>">
                        <a class="nav-link" href="<?php echo $cat['permalink']; ?>">
                            <?php echo $cat['name']; ?>
                            <?php if (!empty($cat['children'])): ?>
                                <span style="font-size: 10px; margin-left: 2px;">▼</span>
                            <?php endif; ?>
                        </a>
                        <?php if (!empty($cat['children'])): ?>
                        <ul class="dropdown-menu">
                            <?php foreach ($cat['children'] as $child): ?>
                            <li class="dropdown-item">
                                <a href="<?php echo $child['permalink']; ?>"><?php echo $child['name']; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Search Form -->
                <form class="search-form" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                    <input type="text" name="s" class="search-input" placeholder="<?php _e('输入关键字搜索'); ?>" required/>
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
    const pages = <?php echo json_encode($pageList); ?>;
    const categoryTree = <?php echo json_encode($categoryTree); ?>;
    const siteUrl = "<?php $this->options->siteUrl(); ?>";

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

    function openMenu() {
        isOpen = true;
        mobileDrawer.classList.add('active');
        mobileDrawerOverlay.classList.add('active');
        mobileMenuBtn.innerHTML = '&lt;';
        renderFirstLevel();
    }

    function closeMenu() {
        isOpen = false;
        mobileDrawer.classList.remove('active');
        mobileDrawerOverlay.classList.remove('active');
        mobileMenuBtn.innerHTML = '&gt;';
    }

    // Render first level menu (Home, Pages, Top-level categories)
    function renderFirstLevel() {
        mobileMenuList.innerHTML = '';

        // 1. Home Link
        const homeLi = document.createElement('li');
        homeLi.className = 'mobile-list-item';
        homeLi.innerHTML = `<a href="${siteUrl}">首页</a>`;
        homeLi.querySelector('a').addEventListener('click', closeMenu);
        mobileMenuList.appendChild(homeLi);

        // 2. Static Pages
        pages.forEach(p => {
            const pageLi = document.createElement('li');
            pageLi.className = 'mobile-list-item';
            pageLi.innerHTML = `<a href="${p.permalink}">${p.title}</a>`;
            pageLi.querySelector('a').addEventListener('click', closeMenu);
            mobileMenuList.appendChild(pageLi);
        });

        // 3. First-Level Categories
        categoryTree.forEach(cat => {
            const catLi = document.createElement('li');
            catLi.className = 'mobile-list-item';

            if (cat.children && cat.children.length > 0) {
                // If it has children, clicking switches to second level
                const btn = document.createElement('button');
                btn.innerHTML = `<span>${cat.name}</span><span>&gt;</span>`;
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
        backBtn.innerHTML = '&lt; 返回一级分类';
        backBtn.addEventListener('click', renderFirstLevel);
        backBtnLi.appendChild(backBtn);
        mobileMenuList.appendChild(backBtnLi);

        // Include "View All" link for the parent category itself
        const allParentLi = document.createElement('li');
        allParentLi.className = 'mobile-list-item';
        allParentLi.innerHTML = `<a href="${parentCat.permalink}" style="font-weight: 700;">查看全部: ${parentCat.name}</a>`;
        allParentLi.querySelector('a').addEventListener('click', closeMenu);
        mobileMenuList.appendChild(allParentLi);

        // Subcategories
        parentCat.children.forEach(child => {
            const childLi = document.createElement('li');
            childLi.className = 'mobile-list-item';
            childLi.innerHTML = `<a href="${child.permalink}">${child.name}</a>`;
            childLi.querySelector('a').addEventListener('click', closeMenu);
            mobileMenuList.appendChild(childLi);
        });
    }
});
</script>

<div class="container main-content">
