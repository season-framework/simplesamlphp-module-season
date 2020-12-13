<?php
$this->data['header'] = $this->t($this->data['dictTitle']);

$this->data['head'] = <<<EOF
<meta name="robots" content="noindex, nofollow" />
<meta name="googlebot" content="noarchive, nofollow" />
EOF;

$this->includeAtTemplateBase('includes/header.php');
?>

<div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title"><?php echo $this->t($this->data['dictTitle']); ?></h4>
    </div>
    <div class="card-body">
        <?php
        echo htmlspecialchars($this->t($this->data['dictDescr'], $this->data['parameters']));

        // include optional information for error
        if (isset($this->data['includeTemplate'])) {
            $this->includeAtTemplateBase($this->data['includeTemplate']);
        }
        ?>

    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <p><?php echo $this->t('report_trackid'); ?></p>
        <pre id="trackid" class="input-left"><?php echo $this->data['error']['trackId']; ?></pre>
    </div>
</div>



<?php
if ($this->data['showerrors']) {
?>


    <div class="card mb-3">
        <div class="card-header">
            <h4 class="card-title"><?php echo $this->t('debuginfo_header'); ?></h4>
        </div>
        <div class="card-body">
            <p><?php echo $this->t('debuginfo_text'); ?></p>
            <pre><?php echo htmlspecialchars($this->data['error']['exceptionMsg']); ?></pre>
            <pre style="font-family: monospace;"><?php echo htmlspecialchars($this->data['error']['exceptionTrace']); ?></pre>
        </div>
    </div>
<?php
}

if (isset($this->data['errorReportAddress'])) {
?>
    <div class="card mb-3">
        <div class="card-header">
            <h4 class="card-title"><?php echo $this->t('report_header'); ?></h4>
        </div>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($this->data['errorReportAddress']); ?>" method="post">
                <p><?php echo $this->t('report_text'); ?></p>
                <p><?php echo $this->t('report_email'); ?>
                    <input type="email" size="25" name="email" value="<?php echo htmlspecialchars($this->data['email']); ?>" />
                </p>
                <p>
                    <textarea class="metadatabox" name="text" rows="6" cols="50" style="width: 100%; padding: 0.5em;"><?php
                                                                                                                        echo $this->t('report_explain'); ?></textarea>
                </p>
                <p>
                    <input type="hidden" name="reportId" value="<?php echo $this->data['error']['reportId']; ?>" />
                    <button type="submit" name="send" class="btn"><?php echo $this->t('report_submit'); ?></button>
                </p>
            </form>

        </div>
    </div>
<?php
}
?>


<div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title"><?php echo $this->t('howto_header'); ?></h4>
    </div>
    <div class="card-body">
        <p><?php echo $this->t('howto_text'); ?></p>
    </div>
</div>

<script type="text/javascript">
    var clipboard = new ClipboardJS('#btntrackid');
</script>

<?php
$this->includeAtTemplateBase('includes/footer.php');
