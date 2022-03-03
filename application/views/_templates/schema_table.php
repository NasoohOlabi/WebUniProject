<?php
function schema_table(array $schemaClasses)
{
    if (!function_exists('endsWith')) {
        function endsWith(string $haystack, string $needle)
        {
            $length = strlen($needle);
            return $length > 0 ? substr($haystack, -$length) === $needle : true;
        }
    }
    require 'component/__schema_table.php';
}
