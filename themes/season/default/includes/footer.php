<?php
$config = \SimpleSAML\Configuration::getInstance();
?>

            </div><!-- .container -->
        </div><!-- .content -->
    </div><!-- #wrap -->
    <footer class="footer footer-transparent d-print-none">
        <div class="container">

            <img src="<?= $config->getString('theme.logo', SimpleSAML\Module::getModuleURL('season/res/logo.png')) ?>" height="24" style="float: right" />

            <?= $config->getString('theme.footer', 'Copyright &copy; 2020 <a href="https://www.season.co.kr">Season Inc.</a> All Rights Reserved.') ?>
            
        </div><!-- #footer -->
    </footer>
</body>

</html>