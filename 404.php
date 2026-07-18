<?php
/**
 * 404 页面（无站头版本）
 *
 * @package miku-cream
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Not Found - <?php $this->options->title(); ?></title>
    <link rel="shortcut icon" href="<?php $this->options->themeUrl('favicon.ico'); ?>" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <?php $themeStyleVersion = @filemtime(__DIR__ . '/style.css') ?: '1.1.13'; ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css?v=' . $themeStyleVersion); ?>">
    <?php $this->header(); ?>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .page-404 {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background-color: var(--canvas-cream);
        }

        .card-404 {
            width: 100%;
            max-width: 560px;
            background-color: var(--white);
            border-radius: 30px;
            padding: 80px 48px 72px;
            box-shadow: rgba(0, 0, 0, 0.04) 0px 4px 24px 0px;
            border: 1px solid rgba(20, 20, 19, 0.02);
            text-align: center;
        }

        .error-code {
            font-size: clamp(96px, 20vw, 180px);
            font-weight: 700;
            letter-spacing: -0.05em;
            line-height: 1;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--ink-black) 30%, var(--light-signal-orange) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 4px 24px rgba(207, 69, 0, 0.12));
            user-select: none;
        }

        .card-404 .post-eyebrow {
            justify-content: center;
            margin-bottom: 20px;
        }

        .card-404 h1 {
            font-size: 2rem;
            margin-bottom: 16px;
        }

        .card-404 .desc {
            font-size: 15px;
            color: var(--slate-gray);
            max-width: 340px;
            margin: 0 auto 44px;
            line-height: 1.7;
        }

        .btn-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card-404 .copyright {
            margin-top: 48px;
            font-size: 13px;
            color: var(--dust-taupe);
        }

        .card-404 .copyright a {
            color: var(--dust-taupe);
        }

        @media (max-width: 480px) {
            .card-404 {
                padding: 60px 28px 56px;
            }
        }
    </style>
</head>
<body>

<div class="page-404">
    <div class="card-404">
        <div class="error-code" aria-hidden="true">404</div>

        <span class="post-eyebrow">页面未找到</span>

        <h1><?php _e('迷路了？'); ?></h1>

        <p class="desc">
            <?php _e('您访问的页面不存在，可能已被删除、移动，或者链接有误。'); ?>
        </p>

        <div class="btn-group">
            <a href="<?php $this->options->siteUrl(); ?>" class="btn-primary">
                &larr; <?php _e('返回首页'); ?>
            </a>
            <a href="<?php $this->options->siteUrl(); ?>" class="btn-secondary">
                <?php _e('返回上一页'); ?>
            </a>
        </div>

        <p class="copyright">
            &copy; <?php echo date('Y'); ?>
            <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>
        </p>
    </div>
</div>

<?php $this->footer(); ?>
</body>
</html>
