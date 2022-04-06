<tr>
    <?php
    if (count($schemaClasses) > 0)
        foreach ($schemaClasses[0] as $property => $value) {
            if (endsWith($property, '_id')) {
                simpleLog("property $property is dumped ");
                //TODO:
            } else
                simpleLog("property $property is not dumped ");
            echo "<th>$property</th>";
        }
    ?>
</tr>