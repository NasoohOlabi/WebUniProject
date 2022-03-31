<?php
// PHP code for logging error into a given file
// error message to be logged
// setting error logging to be active
ini_set("log_errors", TRUE);
function simpleLog($msg, $directory = null, $filename = null)
{
    // path of the log file where errors need to be logged
    if (substr($directory, -1) !== '/') $directory = $directory . '/';
    $log_file = 'logs/' . $directory . (($filename === null) ? date("Y-m-d") : $filename) . ".log";
    // setting the logging file in php.ini
    ini_set('error_log', $log_file);
    // logging the error
    if (I_AM_DEBUGGING)
        error_log($msg);
}
function pageHit(string $page)
{
    $directoryName = '\..\..\..\logs\page';
    //Check if the directory already exists.
    if (!is_dir(__DIR__ . $directoryName)) {
        //Directory does not exist, so lets create it.
        mkdir(__DIR__ . $directoryName, 0755);
    }
    $filename = '\..\..\..\logs\page\\' . $page . ".txt";
    $file = fopen(__DIR__ . $filename, 'a');
    fwrite($file, 'a');
    fclose($file);
    // if (fsize($page) > 1000000) {
    if (rand(1, 5000000) === 10) {
        $fileSize = fsize($page);
        if ($fileSize > 1000000) {
            compressHits($page);
        }
    }
}
function fsize(string $page)
{
    $filename = '\..\..\..\logs\page\\' . $page . ".txt";
    return filesize(__DIR__ . $filename);
}
function compressHits(string $page)
{
    $filename = '\..\..\..\logs\page\\' . $page . ".txt";
    if (!file_exists(__DIR__ . '\..\..\..\logs\page\\' . $page . "_hits.txt")) {
        rename(__DIR__ . $filename, __DIR__ . '\..\..\..\logs\page\\' . $page . "_hits.txt");
        $size = fsize($page . '_hits');
        $file = fopen(__DIR__ . '\..\..\..\logs\page\\' . $page . "_hits.txt", 'w');
        fwrite($file, $size);
        fclose($file);
    } else {
        rename(__DIR__ . $filename, __DIR__ . '\..\..\..\logs\page\\' . $page . "_tmp_hits.txt");
        $size = fsize($page . '_tmp_hits');
        unlink(__DIR__ . '\..\..\..\logs\page\\' . $page . "_tmp_hits.txt");
        $file = fopen(__DIR__ . '\..\..\..\logs\page\\' . $page . "_hits.txt", 'r');
        $number = fgets($file);
        fclose($file);
        $file = fopen(__DIR__ . '\..\..\..\logs\page\\' . $page . "_hits.txt", 'w');
        fwrite($file, $size + $number);
        fclose($file);
    }
}
function readHits(string $page)
{
    $file = fopen(__DIR__ . '\..\..\..\logs\page\\' . $page . "_hits.txt", 'r');
    $number = fgets($file);
    fclose($file);
    return $number * 1;
}
function hitStats()
{
    $files = scandir(__DIR__ . '\..\..\..\logs\page\\');
    $hit_files = [];
    $non_hit_files = [];
    foreach ($files as $file) {
        if (endsWith($file, '_hits.txt')) {
            $hit_files[] = substr($file, 0, strlen($file) - strlen('_hits.txt'));
        } elseif (endsWith($file, '.txt')) {
            $non_hit_files[] = substr($file, 0, strlen($file) - 4);
        }
    }
    $non_hit_cnt = [];
    foreach ($non_hit_files as $non_hit_file) {
        $non_hit_cnt[$non_hit_file] = fsize($non_hit_file);
    }
    $hit_cnt = [];
    foreach ($hit_files as $hit_file) {
        $hit_cnt[$hit_file] = readHits($hit_file);
    }

    foreach ($non_hit_cnt as $page => $hits) {
        if (isset($hit_cnt[$page])) {
            $hit_cnt[$page] += $hits;
        } else {
            $hit_cnt[$page] = $hits;
        }
    }

    return $hit_cnt;
}
