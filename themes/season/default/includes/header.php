<?php

header('X-Frame-Options: SAMEORIGIN');

$basepath = \SimpleSAML\Utils\HTTP::getSelfURLHost();
$current = \SimpleSAML\Utils\HTTP::getSelfURLNoQuery();
$current = str_replace($basepath, "", $current);

$config = \SimpleSAML\Configuration::getInstance();
$seasonConfig = \SimpleSAML\Configuration::getOptionalConfig('module_season.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    <meta name="robots" content="noindex, nofollow" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="initial-scale=1.0" />

    <script type="text/javascript" src="/<?php echo $this->data['baseurlpath']; ?>resources/script.js"></script>

    <title><?= $config->getString('theme.title', "SEASON FRAMEWORK") ?></title>

    <link rel="shortcut icon" href="<?= $config->getString('theme.icon', SimpleSAML\Module::getModuleURL('season/res/icon.ico')) ?>">
    <link href="<?= SimpleSAML\Module::getModuleURL('season/libs/tabler/dist/css/tabler.min.css'); ?>" rel="stylesheet" />
    <link href="<?= SimpleSAML\Module::getModuleURL('season/libs/tabler/dist/css/demo.min.css'); ?>" rel="stylesheet" />
    <link href="<?= SimpleSAML\Module::getModuleURL('season/css/core.css'); ?>?time=<?= microtime() ?>" rel="stylesheet" />

    <script src="<?= SimpleSAML\Module::getModuleURL('season/libs/jquery-3.5.1.min.js'); ?>"></script>
    <script src="<?= SimpleSAML\Module::getModuleURL('season/libs/tabler/dist/js/tabler.min.js'); ?>"></script>
    <script src="<?= SimpleSAML\Module::getModuleURL('season/libs/bootstrap/popper.min.js'); ?>"></script>
    <script src="<?= SimpleSAML\Module::getModuleURL('season/libs/bootstrap/bootstrap.min.js'); ?>"></script>
    <script src="<?= SimpleSAML\Module::getModuleURL('season/libs/moment.min.js'); ?>"></script>

    <?php
    if (isset($this->data['clipboard.js'])) {
        echo '<script type="text/javascript" src="/' . $this->data['baseurlpath'] . 'resources/clipboard.min.js"></script>' . "\n";
    }
    ?>

    <style>
        .nav-item.active:after {
            border-color: #FF7F11 !important;
        }
    </style>
</head>

<body class="antialiased">
    <div id="wrap">
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="/<?= $this->data['baseurlpath']; ?>" style="margin-right: 12px;">
                        <img src="<?= $config->getString('theme.logo', SimpleSAML\Module::getModuleURL('season/res/logo.png')) ?>" height="32" class="navbar-brand-image">
                    </a>
                    <?= $seasonConfig->getString("title", "simpleSAMLphp") ?>
                </h1>
            </div>
        </header>

        <div class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar navbar-light">
                    <div class="container">
                        <ul class="navbar-nav">
                            <?php
                            $menu = $seasonConfig->getValue('menu', array());
                            foreach ($menu as $m) {
                                $href = "/" . $this->data['baseurlpath'] . "module.php/" . $m["href"];
                                if(isset($m["pattern"])) $pattern = "/" . $this->data['baseurlpath'] . "module.php/" . $m["pattern"];
                                else $pattern = "/" . $this->data['baseurlpath'] . "module.php/" . $m["href"];
                                $active = strrpos($current, $pattern, -strlen($current)) !== false ? "active" : "";
                            ?>
                                <li class="nav-item <?= $active ?>">
                                    <a class="nav-link" href="<?= $href ?>" thref="<?= $current ?>"><?= $m['title'] ?></a>
                                </li>
                            <?php
                            }
                            ?>

                        </ul>

                        <?php
                        if (isset($this->data['logouturl'])) {
                            echo '<a class="btn btn-white" href="' . htmlspecialchars($this->data['logouturl']) . '">' . $this->t('{status:logout}') . '</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container">
                <?php
                if (!$config->getBoolean('production', true)) {
                    echo '<div class="caution">' . $this->t('{preprodwarning:warning:warning}') . '</div>';
                }

                ?>