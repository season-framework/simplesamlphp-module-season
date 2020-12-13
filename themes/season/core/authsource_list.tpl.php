<?php
$this->data['header'] = 'Test authentication sources';
$this->includeAtTemplateBase('includes/header.php');
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?php echo $this->data['header']; ?></h4>
    </div>
    <div class="card-body">
        <ul>
            <?php
            foreach ($this->data['sources'] as $id) {
                echo '<li><a href="?as=' . htmlspecialchars(urlencode($id)) . '">' . htmlspecialchars($id) . '</a></li>';
            }
            ?>
        </ul>
    </div>
</div>

<?php
$this->includeAtTemplateBase('includes/footer.php');
