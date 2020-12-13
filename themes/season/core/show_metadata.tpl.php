<?php
$this->includeAtTemplateBase('includes/header.php');
?>

<div class="card">
    <div class="card-body">
        <div style="position: relative;">
            <button data-clipboard-target="#metadata" id="btncp" class="btn topright" style="position: absolute; right: 4px; top: 4px;">
                <img style="height: 16px;" src="/<?php echo $this->data['baseurlpath'] . 'resources/icons/clipboard.svg'; ?>" alt="Copy to clipboard" />
            </button>

            <pre id="metadata">$metadata['<?php echo $this->data['m']['metadata-index'];
                                            unset($this->data['m']['metadata-index']) ?>'] = <?php
                                                                                                echo htmlspecialchars(var_export($this->data['m'], true));
                                                                                                ?>;</pre>
        </div>
    </div>
    <div class="card-footer" style="text-align: right;">
        <a href="<?php echo $this->data['backlink']; ?>"><span class="btn">Back</span></a>
    </div>
</div>

<script type="text/javascript">
    var clipboard = new ClipboardJS('#btncp');
</script>
<?php
$this->includeAtTemplateBase('includes/footer.php');
