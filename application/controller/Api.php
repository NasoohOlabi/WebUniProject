<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';


/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Home extends Controller
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index()
    {

        simpleLog("Api/index called");

        // admin session check!

        echo "APIs are great, make sure to use to be the admin to use this api for now";
    }
    public function Create($var = null)
    {
        # code...
    }
    public function Read($var = null)
    {
        # code...
    }
    public function Update($var = null)
    {
        # code...
    }
    public function Delete($var = null)
    {
        # code...
    }
}
