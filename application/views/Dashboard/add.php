<div id="main-content" class="inlineBlock">
    <a href="<?= URL ?>dashboard">
        <div class="fa fa-2x fa-arrow-left back-btn"></div>
    </a>
    <div class="fa fa-2x fa-save save-btn"></div>
    <?php
    if (!$form) {
        foreach ($forms as $val) {
            $q = new $val();
            FormForThis($q, $bm);
        }
    } else if (in_array(strtolower($form), $this->forms)) {
        $q = new $form();
        FormForThis($q, $bm);
    } else {
        return;
    }
    ?>
</div>
</body>

</html>