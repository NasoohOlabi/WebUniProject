<tr>
    <?php
    foreach ($schemaClass as $property => $value) {
        if (endsWith($property, '_id')) {
            // TODO:
        } else {
            echo "<td>" . json_encode($value) . "</td>";
        }
    }
    ?>
</tr>