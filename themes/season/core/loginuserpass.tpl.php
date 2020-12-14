<?php
$config = \SimpleSAML\Configuration::getInstance();
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta http-equiv="Content-Language" content="en" />
  <meta name="msapplication-TileColor" content="#2d89ef">
  <meta name="theme-color" content="#4188c9">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <title>season Login</title>

  <link rel="shortcut icon" href="<?= $config->getString('theme.icon', SimpleSAML\Module::getModuleURL('season/res/icon.ico')) ?>">
  <link href="<?= SimpleSAML\Module::getModuleURL('season/libs/tabler/dist/css/tabler.min.css'); ?>" rel="stylesheet" />
  <link href="<?= SimpleSAML\Module::getModuleURL('season/css/dashboard.css'); ?>" rel="stylesheet" />
</head>

<body>
  <div class="page">
    <div class="page-single">
      <div class="container">
        <div class="row">
          <div class="col col-login mx-auto">
            <div class="text-center mb-6">
              <img src="<?= $config->getString('theme.logo', SimpleSAML\Module::getModuleURL('season/res/logo.png')) ?>" class="h-7" alt="">
            </div>
            <?php
            if ($this->data['errorcode'] !== null) {
            ?>
              <div class="text-center mb-4">
                <div class="alert alert-danger" style="margin: 0;">
                  <?php
                  echo htmlspecialchars(
                    $this->t(
                      $this->data['errorcodes']['title'][$this->data['errorcode']],
                      $this->data['errorparams']
                    )
                  );
                  ?>
                </div>
              </div>
            <?php
            }
            ?>
            <form class="card" action="?" method="post" name="f" style="margin-bottom: 128px;">
              <div class="card-body p-6">
                <div class="form-group">
                  <label class="form-label">ID</label>
                  <input name="username" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="ID" value="<?php echo htmlspecialchars($this->data['username']); ?>" tabindex="1">
                </div>
                <div class="form-group">
                  <label class="form-label">
                    Password
                  </label>
                  <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" tabindex="2" autocomplete="current-password">
                </div>
                <div class="form-footer">
                  <button type="submit" class="btn btn-orange btn-block">Login</button>
                </div>
              </div>

              <?php
              foreach ($this->data['stateparams'] as $name => $value) {
                echo '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" />';
              }
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>