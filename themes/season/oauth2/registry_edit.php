<style>
    .card td {
        word-break: break-all;
    }

    .card th {
        max-width: 160px;
        word-break: break-all;
    }

    .card table {
        width: 100%!important;
        font-size: 13px;
    }

    .card table tr, .card table tbody {
        width: 100%;
    }

    .card input, .card textarea, .card select {
        width: 100% !important;
    }

    .card .button {
        max-width: 80px;
    }

    .card td {
        padding: 8px 4px;
    }
</style>

<?php
$form = (string) $this->data['form'];

$this->data['jquery'] = array('core' => TRUE, 'ui' => TRUE, 'css' => TRUE);
$this->includeAtTemplateBase('includes/header.php');

$moduleurlpath = '/' . $this->data['baseurlpath'].'/module.php/oauth2/';

$page = <<< EOD
    <div class="card mb-3">
        <div class="card-header">
            <h4 class="card-title">Oauth2 Client Registry</h4>
        </div>
        <div class="card-body">
            <p>Here you can edit an OAuth2 client.</p>
        </div>
        </div>

        <div class="card mb-3">
        <div class="card-body">
            {$form}
        </div>
    </div>
EOD;

echo($page);

$this->includeAtTemplateBase('includes/footer.php');
