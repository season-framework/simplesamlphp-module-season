<?php
$this->data['htmlinject'] = [
    'htmlContentPre' => [],
    'htmlContentPost' => [],
    'htmlContentHead' => [],
];

$jquery = [];
if (array_key_exists('jquery', $this->data)) {
    $jquery = $this->data['jquery'];
}

if (array_key_exists('pageid', $this->data)) {
    $hookinfo = [
        'pre' => &$this->data['htmlinject']['htmlContentPre'],
        'post' => &$this->data['htmlinject']['htmlContentPost'],
        'head' => &$this->data['htmlinject']['htmlContentHead'],
        'jquery' => &$jquery,
        'page' => $this->data['pageid']
    ];

    SimpleSAML\Module::callHooks('seasoninject', $hookinfo);
}

header('X-Frame-Options: SAMEORIGIN');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    <meta name="robots" content="noindex, nofollow" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="initial-scale=1.0" />

    <script type="text/javascript" src="/<?php echo $this->data['baseurlpath']; ?>resources/script.js"></script>

    <title>SEASON</title>

    <link rel="shortcut icon" href="<?= SimpleSAML\Module::getModuleURL('season/res/icon.ico'); ?>">
    <link href="<?= SimpleSAML\Module::getModuleURL('season/libs/tabler/dist/css/tabler.min.css'); ?>" rel="stylesheet" />
    <link href="<?= SimpleSAML\Module::getModuleURL('season/libs/tabler/dist/css/demo.min.css'); ?>" rel="stylesheet" />
    <link href="<?= SimpleSAML\Module::getModuleURL('season/css/core.css'); ?>?time=<?= microtime() ?>" rel="stylesheet" />

    <script src="<?= SimpleSAML\Module::getModuleURL('season/libs/jquery-3.5.1.min.js'); ?>"></script>
    <script src="<?= SimpleSAML\Module::getModuleURL('season/libs/tabler/dist/js/tabler.min.js'); ?>"></script>
    <script src="<?= SimpleSAML\Module::getModuleURL('season/libs/tabler/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>

    <?php
    if (isset($this->data['clipboard.js'])) {
        echo '<script type="text/javascript" src="/' . $this->data['baseurlpath'] . 'resources/clipboard.min.js"></script>' . "\n";
    }
    ?>
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
                        <img src="<?= SimpleSAML\Module::getModuleURL('season/res/logo.png'); ?>" height="32" class="navbar-brand-image">
                    </a>
                    <?= (isset($this->data['header']) ? $this->data['header'] : 'SimpleSAMLphp'); ?>
                </h1>
            </div>
        </header>

        <div class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar navbar-light">
                    <div class="container">
                        <?php
                        if (!empty($this->data['htmlinject']['htmlContentPre'])) {
                            foreach ($this->data['htmlinject']['htmlContentPre'] as $c) {
                                echo $c;
                            }
                        } else {
                        ?>
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="/<?= $this->data['baseurlpath'] ?>module.php/core/frontpage_welcome.php">Welcome</a></li>
                                <li class="nav-item"><a class="nav-link" href="/<?= $this->data['baseurlpath'] ?>module.php/core/frontpage_config.php">Configuration</a></li>
                                <li class="nav-item"><a class="nav-link" href="/<?= $this->data['baseurlpath'] ?>module.php/core/frontpage_auth.php">Authentication</a></li>
                                <li class="nav-item"><a class="nav-link" href="/<?= $this->data['baseurlpath'] ?>module.php/core/frontpage_federation.php">Federation</a></li>
                            </ul>
                        <?php
                        }
                        ?>

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
                $config = \SimpleSAML\Configuration::getInstance();

                if (!$config->getBoolean('production', true)) {
                    echo '<div class="caution">' . $this->t('{preprodwarning:warning:warning}') . '</div>';
                }

                ?>