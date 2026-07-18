<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

</div><!-- end .container .main-content -->

<footer>
    <div class="footer-content">
        <!-- Site links and account actions -->
        <div class="footer-links">
            <span class="footer-prefix">站点链接：</span>
            <?php if ($this->user->hasLogin()): ?>
                <a href="<?php $this->options->adminUrl(); ?>">进入后台 (<?php $this->user->screenName(); ?>)</a>
                <a href="<?php $this->options->logoutUrl(); ?>">退出</a>
            <?php else: ?>
                <a href="<?php $this->options->adminUrl('login.php'); ?>">登录后台</a>
            <?php endif; ?>
        </div>

        <div class="copyright">
            &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>.
            <?php _e('由 <a href="https://typecho.org" target="_blank" rel="noopener noreferrer">Typecho</a> 强力驱动'); ?>.
        </div>
    </div>
</footer>

<?php $this->footer(); ?>
</body>
</html>
