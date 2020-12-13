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
        <p><?php echo $this->t('{core:frontpage:intro}'); ?></p>

        <ul>
            <?php
            foreach ($this->data['links_welcome'] as $link) {
                echo '<li><a href="' . htmlspecialchars($link['href']) . '">' . $this->t($link['text']) . '</a></li>';
            }
            ?>
        </ul>

    </div>
</div>

<?php $this->includeAtTemplateBase('includes/footer.php');
