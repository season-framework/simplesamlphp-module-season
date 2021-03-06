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
        <ul>
            <?php
            foreach ($this->data['links_auth'] as $link) {
                echo '<li><a href="' . htmlspecialchars($link['href']) . '">' . $this->t($link['text']) . '</a>';
                if (isset($link['deprecated']) && $link['deprecated']) {
                    echo ' <b>' . $this->t('{core:frontpage:deprecated}') . '</b>';
                }
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</div>

<?php $this->includeAtTemplateBase('includes/footer.php');
