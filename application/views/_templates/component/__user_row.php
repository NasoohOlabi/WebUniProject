<tr>
    <?php
    foreach ($schemaClass as $property => $value) {
        echo "<td>" . json_encode($value) . "</td>";
    }
    ?>
</tr>