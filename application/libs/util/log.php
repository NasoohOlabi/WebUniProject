<?php
// PHP code for logging error into a given file
// error message to be logged
// setting error logging to be active
ini_set("log_errors", TRUE);
function simpleLog($msg)
{
    // path of the log file where errors need to be logged
    $log_file = 'logs/' . date("Y-m-d") . ".log";
    // setting the logging file in php.ini
    ini_set('error_log', $log_file);
    // logging the error
    error_log($msg);
}
function pageHit($page)
{
    $log_file = 'logs/page/' . $page . ".log";
    // setting the logging file in php.ini
    ini_set('error_log', $log_file);
    // logging the error
    error_log("$page hit");
}
