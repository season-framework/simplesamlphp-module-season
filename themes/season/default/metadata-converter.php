<?php
$this->data['header'] = $this->t('metaconv_title');
$this->includeAtTemplateBase('includes/header.php');
?>

<div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title"><?php echo $this->t('metaconv_title'); ?></h4>
    </div>


    <form action="?" method="post" enctype="multipart/form-data">
        <div class="card-body">
            <p><?php echo ($this->t('{admin:metaconv_xmlmetadata}')); ?></p>
            <p>
                <textarea rows="20" style="width: 100%" name="xmldata"><?php echo htmlspecialchars($this->data['xmldata']); ?></textarea>
            </p>
            <p>
                <?php echo $this->t('metaconv_selectfile'); ?>
                <input type="file" name="xmlfile" />
            </p>
        </div>
        <div class="card-footer" style="text-align: right;">
            <button type="submit" class="btn"><?php echo $this->t('metaconv_parse'); ?></button>
        </div>
    </form>
</div>

<?php
$output = $this->data['output'];

if (!empty($output)) {
?>
    <div class="card mb-3">
        <div class="card-header">
            <h4 class="card-title"><?php echo $this->t('metaconv_converted'); ?></h4>
        </div>

        <?php
        $i = 1;
        foreach ($output as $type => $text) {
            if ($text === '') {
                continue;
            }
        ?>

            <div class="card-body">
                <h3><?php echo htmlspecialchars($type); ?></h3>
                <div style="position: relative;">
                    <button data-clipboard-target="#metadata<?php echo $i; ?>" id="btn<?php echo $i; ?>" class="btn topright" style="position: absolute; right: 4px; top: 4px;">
                        <img style="height: 16px;" src="/<?php echo $this->data['baseurlpath'] . 'resources/icons/clipboard.svg'; ?>" alt="Copy to clipboard" />
                    </button>
                    <pre id="metadata<?php echo $i; ?>"><?php
                                                        echo htmlspecialchars($text);
                                                        ?></pre>
                </div>
            </div>
        <?php
            $i++;
        }
        ?>
        <script type="text/javascript">
            <?php
            for ($j = 1; $j <= $i; $j++) {
            ?>
                var clipboard<?php echo $j; ?> = new ClipboardJS('#btn<?php echo $j; ?>');
            <?php
            }
            ?>
        </script>
    </div>
<?php
}
$this->includeAtTemplateBase('includes/footer.php');
