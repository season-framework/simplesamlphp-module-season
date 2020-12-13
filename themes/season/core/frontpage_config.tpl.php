<?php

$this->data['header'] = $this->t('{core:frontpage:page_title}');
$this->includeAtTemplateBase('includes/header.php');

?>

<div class="alert alert-warning" role="alert">
    <?php
    if ($this->data['isadmin']) {
        echo '<p>' . $this->t('{core:frontpage:loggedin_as_admin}') . '</p>';
    } else {
        echo '<p><a href="' . $this->data['loginurl'] . '">' .
            $this->t('{core:frontpage:login_as_admin}') . '</a></p>';
    }
    ?>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div>
            <code style="background: white; background: #f5f5f5; border: 1px dotted #bbb; padding: 1em;  color: #555">
                <?php
                echo $this->data['directory'] . ' (' . $this->data['version'] . ')';
                ?>
            </code>
        </div>

        <div style="clear: both" class="enablebox mini">
            <table>

                <?php
                $icon_enabled  = '<img src="/' . $this->data['baseurlpath'] . 'resources/icons/silk/accept.png" alt="enabled" />';
                $icon_disabled = '<img src="/' . $this->data['baseurlpath'] . 'resources/icons/silk/delete.png" alt="disabled" />';
                echo '<tr class="' . ($this->data['enablematrix']['saml20idp'] ? 'enabled' : 'disabled') . '"><td>SAML 2.0 IdP</td>';
                echo '<td>' . ($this->data['enablematrix']['saml20idp'] ? $icon_enabled : $icon_disabled) . '</td></tr>';

                echo '<tr class="' . ($this->data['enablematrix']['shib13idp'] ? 'enabled' : 'disabled') . '"><td>Shib 1.3 IdP</td>';
                echo '<td>' . ($this->data['enablematrix']['shib13idp'] ? $icon_enabled : $icon_disabled) . '</td></tr>';
                ?>

            </table>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title"><?php echo $this->t('{core:frontpage:configuration}'); ?></h4>
    </div>
    <div class="card-body">
        <ul>
            <?php
            foreach ($this->data['links_config'] as $link) {
                echo '<li><a href="' . htmlspecialchars($link['href']) . '">' . $this->t($link['text']) . '</a></li>';
            }
            ?>
        </ul>
    </div>
</div>

<?php
if (array_key_exists('warnings', $this->data) && is_array($this->data['warnings']) && !empty($this->data['warnings'])) {
?>
    <div class="card mb-3">
        <div class="card-header">
            <h4 class="card-title"><?= $this->t('{core:frontpage:warnings}') ?></h4>;
        </div>

        <div class="card-body">
            <?php

            foreach ($this->data['warnings'] as $warning) {
                echo '<div class="caution">' . $warning . '</div>';
            }

            ?>
        </div>
    </div>
<?php
}

if ($this->data['isadmin']) {
?>
    <div class="card mb-3">
        <div class="card-header">
            <h4 class="card-title"><?= $this->t('{core:frontpage:checkphp}') ?></h4>;
        </div>
        <div class="card-body">
            <?php
            echo '<div class="enablebox"><table>';

            $icon_enabled = '<img src="/' . $this->data['baseurlpath'] . 'resources/icons/silk/accept.png" alt="enabled" />';
            $icon_disabled = '<img src="/' . $this->data['baseurlpath'] . 'resources/icons/silk/delete.png" alt="disabled" />';

            foreach ($this->data['funcmatrix'] as $func) {
                echo '<tr class="' . ($func['enabled'] ? 'enabled' : 'disabled') . '"><td>' .
                    ($func['enabled'] ? $icon_enabled : $icon_disabled) . '</td>';
                echo '<td>' . $this->t($this->data['requiredmap'][$func['required']]) . '</td><td>' . $func['descr'] . '</td></tr>';
            }
            echo '</table></div>';

            ?>
        </div>
    </div>
<?php
}

$this->includeAtTemplateBase('includes/footer.php');
