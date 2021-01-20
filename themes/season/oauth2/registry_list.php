<style>
    .card td {
        word-break: break-all;
    }

    .card table {
        width: 100%!important;
        font-size: 13px;
    }

    .card table tr, .card table tbody {
        width: 100%;
    }

    .card input {
        width: 100% !important;
    }
</style>
<?php

$this->data['jquery'] = array('core' => TRUE, 'ui' => TRUE, 'css' => TRUE);
$this->includeAtTemplateBase('includes/header.php');

$moduleurlpath = '/' . $this->data['baseurlpath'] . '/module.php/oauth2/';

if (!empty($this->data['clients'])) {
    $clients = [];

    foreach ($this->data['clients'] as $client) {
        $urls = array_reduce($client['redirect_uri'], function ($initial, $item) {
            return sprintf('%s <li>%s</li>', $initial, $item);
        }, '');

        $clients[] = <<< EOD
        <div class="col-sm-6">
            <div class="card mb-3">
                <div class="card-status-top bg-orange"></div>
                <div class="card-header">
                    <h4 class="card-title">{$client['name']}</h4>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <tr>
                            <th>Name</td>
                            <td>{$client['name']}</td>
                        </tr>
                        <tr>
                            <th>Description</td>
                            <td>{$client['description']}</td>
                        </tr>
                        <tr>
                            <th>Auth Source</td>
                            <td>{$client['auth_source']}</td>
                        </tr>
                        <tr>
                            <th>Client ID</td>
                            <td>{$client['id']}</td>
                        </tr>
                        <tr>
                            <th>Client Secret</td>
                            <td>{$client['secret']}</td>
                        </tr>
                        <tr>
                            <th>Redirect URLs</td>
                            <td>
                                <ul>
                                    {$urls}
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer" style="text-align: right;">
                    <a href="{$moduleurlpath}registry.edit.php?id={$client['id']}" class="btn btn-sm ms-2">Edit config</a>
                    <a href="{$moduleurlpath}registry.php?restore={$client['id']}" class="btn btn-sm ms-2">Restore secret</a>
                    <a href="{$moduleurlpath}registry.php?delete={$client['id']}" class="btn btn-sm ms-2">Delete</a>
                </div>
            </div>
        </div>
EOD;
    }

    $clients = implode(' ', $clients);
} else {

    $clients = <<< EOD
        <tr>
            <td colspan=7 style="text-align: center;">No clients registered</td>
        </tr>
EOD;
}

$page = <<< EOD
    <div class="card mb-3">
        <div class="card-header">
            <h4 class="card-title">Oauth2 Client Registry</h4>
        </div>
        <div class="card-body">
            <p>Here you can register new OAuth2 Clients.</p>
        </div>
    </div>
    

    <div class="row row-deck row-cards">
        {$clients}


        <div class="col-sm-6">
            <div class="card mb-3">
                <div class="card-status-top bg-orange"></div>
                <div class="empty">
                    <p class="empty-title">Create Client</p>
                    <p class="empty-subtitle text-muted">
                        Add new Oauth client
                    </p>
                    <div class="empty-action">
                        <a href="{$moduleurlpath}registry.new.php" class="btn btn-orange">Add new client</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
EOD;

echo ($page);

$this->includeAtTemplateBase('includes/footer.php');
