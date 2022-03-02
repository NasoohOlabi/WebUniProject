<tr>
    <?php
    function endsWith(string $haystack, string $needle)
    {
        $length = strlen($needle);
        return $length > 0 ? substr($haystack, -$length) === $needle : true;
    }
    foreach ($schemaClass as $property => $value) {
        if (endsWith($property, '_id')) {
            // TODO:
        } else {
            echo "<td>" . json_encode($value) . "</td>";
        }
    }
    ?>
</tr>