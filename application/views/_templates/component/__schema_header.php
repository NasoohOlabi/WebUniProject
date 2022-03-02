<tr>
    <?php
    if (count($schemaClasses) > 0)
        foreach ($schemaClasses[0] as $property => $value) {
            echo "<th>$property</th>";
        }
    ?>
</tr>