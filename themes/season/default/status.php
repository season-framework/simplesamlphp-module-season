<?php
if (array_key_exists('header', $this->data)) {
    if ($this->getTranslator()->getTag($this->data['header']) !== null) {
        $this->data['header'] = $this->t($this->data['header']);
    }
}

$this->includeAtTemplateBase('includes/header.php');
$this->includeAtTemplateBase('includes/attributes.php');

?>

<div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title">
            <?php if (isset($this->data['header'])) {
                echo $this->data['header'];
            } else {
                echo $this->t('{status:some_error_occurred}');
            } ?>
        </h4>
    </div>

    <div class="card-body">
        <p><?php echo $this->t('{status:intro}'); ?></p>
    </div>

    <div class="card-body">
        <?php
        if (isset($this->data['remaining'])) {
            echo '<p>' . $this->t('{status:validfor}', ['%SECONDS%' => $this->data['remaining']]) . '</p>';
        }

        if (isset($this->data['sessionsize'])) {
            echo '<p>' . $this->t('{status:sessionsize}', ['%SIZE%' => $this->data['sessionsize']]) . '</p>';
        }
        ?>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title">
            <?php echo $this->t('{status:attributes_header}'); ?>
        </h4>
    </div>

    <div class="table-responsive">
        <?php
        $attributes = $this->data['attributes'];
        echo present_attributes($this, $attributes, '');

        $nameid = $this->data['nameid'];
        if ($nameid !== false) {
            echo "<h2>" . $this->t('{status:subject_header}') . "</h2>";
            if ($nameid->getValue() === null) {
                $list = ["NameID" => [$this->t('{status:subject_notset}')]];
                echo "<p>NameID: <span class=\"notset\">" . $this->t('{status:subject_notset}') . "</span></p>";
            } else {
                $list = [
                    "NameId" => [$nameid->getValue()],
                ];
                if ($nameid->getFormat() !== null) {
                    $list[$this->t('{status:subject_format}')] = [$nameid->getFormat()];
                }
                if ($nameid->getNameQualifier() !== null) {
                    $list['NameQualifier'] = [$nameid->getNameQualifier()];
                }
                if ($nameid->getSPNameQualifier() !== null) {
                    $list['SPNameQualifier'] = [$nameid->getSPNameQualifier()];
                }
                if ($nameid->getSPProvidedID() !== null) {
                    $list['SPProvidedID'] = [$nameid->getSPProvidedID()];
                }
            }
            echo present_attributes($this, $list, '');
        }

        ?>
    </div>
</div>

<div class="card">

    <?php
    $authData = $this->data['authData'];
    if (!empty($authData)) {
        echo '<div class="card-header"><h4 class="card-title">' . $this->t('{status:authData_header}') . '</h4></div>';
        echo '<div class="card-body"><details><summary>' . $this->t('{status:authData_summary}') . '</summary>';
        echo '<pre>' . htmlspecialchars(json_encode($this->data['authData'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</pre>';
        echo '</details></div>';
    }
    if (isset($this->data['logout'])) {
        echo '<div class="card-header"><h4 class="card-title">' . $this->t('{status:logout}') . '</h4></div>';
        echo '<div class="card-body"><p>' . $this->data['logout'] . '</p></div>';
    }
    ?>

    <div class="card-footer">
        <?php
        if (isset($this->data['logouturl'])) {
            echo '<a href="' . htmlspecialchars($this->data['logouturl']) . '">' . $this->t('{status:logout}') . '</a>';
        }
        ?>
    </div>
</div>

<?php
$this->includeAtTemplateBase('includes/footer.php');
