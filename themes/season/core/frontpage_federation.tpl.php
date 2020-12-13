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

<?php

if (is_array($this->data['metaentries']['hosted']) && count($this->data['metaentries']['hosted']) > 0) {
    foreach ($this->data['metaentries']['hosted'] as $hm) {
        echo '<div class="card mb-3">';
        echo '<div class="card-header"><h4 class="card-title">' . $this->t($this->data['mtype'][$hm['metadata-set']]) . '</h4></div>';
        echo '<div class="card-body">';
        echo '<p>Entity ID: ' . $hm['entityid'];
        if (isset($hm['deprecated']) && $hm['deprecated']) {
            echo '<br /><b>Deprecated</b>';
        }
        if ($hm['entityid'] !== $hm['metadata-index']) {
            echo '<br />Index: ' . $hm['metadata-index'];
        }
        if (!empty($hm['name'])) {
            echo '<br /><strong>' .
                $this->getTranslator()->getPreferredTranslation(SimpleSAML\Utils\Arrays::arrayize($hm['name'], 'en')) .
                '</strong>';
        }
        if (!empty($hm['descr'])) {
            echo '<br /><strong>' .
                $this->getTranslator()->getPreferredTranslation(SimpleSAML\Utils\Arrays::arrayize($hm['descr'], 'en')) .
                '</strong>';
        }

        echo '<br  />[ <a href="' . $hm['metadata-url'] . '">' . $this->t('{core:frontpage:show_metadata}') . '</a> ]';

        echo '</p></div>';
        echo '</div>';
    }
}

if (is_array($this->data['metaentries']['remote']) && count($this->data['metaentries']['remote']) > 0) {
    $now = time();
    foreach ($this->data['metaentries']['remote'] as $setkey => $set) {
        echo '<div class="card mb-3"><div class="card-header"><h4 class="card-title">' . $this->t($this->data['mtype'][$setkey]) . ' (Trusted)</h4></div>';
        echo '<div class="card-body"><ul>';
        foreach ($set as $entry) {
            echo '<li>';
            echo ('<a href="' .
                htmlspecialchars(
                    SimpleSAML\Module::getModuleURL(
                        'core/show_metadata.php',
                        ['entityid' => $entry['entityid'], 'set' => $setkey]
                    )
                ) . '">');
            if (!empty($entry['name'])) {
                echo htmlspecialchars($this->getTranslator()->getPreferredTranslation(
                    SimpleSAML\Utils\Arrays::arrayize($entry['name'], 'en')
                ));
            } elseif (!empty($entry['OrganizationDisplayName'])) {
                echo htmlspecialchars($this->getTranslator()->getPreferredTranslation(
                    SimpleSAML\Utils\Arrays::arrayize($entry['OrganizationDisplayName'], 'en')
                ));
            } else {
                echo htmlspecialchars($entry['entityid']);
            }
            echo '</a>';
            if (array_key_exists('expire', $entry)) {
                if ($entry['expire'] < $now) {
                    echo '<span style="color: #500; font-weight: bold"> (expired ' .
                        number_format(($now - $entry['expire']) / 3600, 1) . ' hours ago)</span>';
                } else {
                    echo ' (expires in ' . number_format(($entry['expire'] - $now) / 3600, 1) . ' hours)';
                }
            }
            echo '</li>';
        }
        echo '</ul></div>';
        echo '</div>';
    }
}

?>

<div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title"><?= $this->t('{core:frontpage:tools}') ?></h4>
    </div>
    <div class="card-body">
        <?php
        echo '<ul>';
        foreach ($this->data['links_federation'] as $link) {
            echo '<li><a href="' . htmlspecialchars($link['href']) . '">' . $this->t($link['text']) . '</a></li>';
        }
        echo '</ul>';
        ?>
    </div>
</div>

<?php
if ($this->data['isadmin']) {
?>
    <div class="card mb-3">
        <div class="card-header">
            <h4 class="card-title">Lookup metadata</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo SimpleSAML\Module::getModuleURL('core/show_metadata.php'); ?>" method="get">
                <p style="margin: 1em 2em ">Look up metadata for entity:
                    <select name="set">
                        <?php
                        if (is_array($this->data['metaentries']['remote']) && count($this->data['metaentries']['remote']) > 0) {
                            foreach ($this->data['metaentries']['remote'] as $setkey => $set) {
                                echo '<option value="' . htmlspecialchars($setkey) . '">' . $this->t($this->data['mtype'][$setkey]) . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <input type="text" name="entityid" />
                    <button class="btn" type="submit">Lookup </button>
                </p>
            </form>
        </div>
    </div>
<?php
}
$this->includeAtTemplateBase('includes/footer.php');
