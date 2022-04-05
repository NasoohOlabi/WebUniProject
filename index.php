<?php


/**
 * A simple PHP MVC skeleton
 *
 * @package mnu
 * @author Panique
 * @link http://www.mnu.net
 * @link https://github.com/panique/mnu/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// load the (optional) Composer auto-loader
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
}

// load application config (error reporting etc.)
require 'application/config/config.php';

// load application class
require 'application/libs/application.php';
require 'application/libs/controller.php';

function endsWith(string $haystack, string $needle)
{
    $length = strlen($needle);
    return $length > 0 ? substr($haystack, -$length) === $needle : true;
}
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle)
    {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

// require 'application/model/permissions.php';
// long name so I don't pollute the global namespace
$___userHasPermissionConstantHierarchy = [
    'read_question' => [
        'read_choice'
    ],
    'read_subject' => [
        'read_topic',
    ],
    'read_role' => [
        'read_permission',
        'read_role_has_permission'
    ],
    'add_exam_center' => [
        'create_exam_center'
    ],
    'enroll_student' => [
        'create_student'
    ],
    'generate_exam' => [
        'create_exam',
        'create_student_exam'
    ],
    'change_role_permissions' => [
        'create_role_has_permission',
        'delete_role_has_permission',
        'read_role_has_permission'
    ],
    'grant_permission' => [
        'read_role_has_permission',
        'create_role_has_permission'
    ],
    'take_exam' => [
        'read_student_exam',
        'read_exam',
        'read_choice',
        'read_question',
        'read_subject',
        'read_topic',
        'read_exam_center',
        'read_student',
        'read_user'
    ],
    'reassign_role' => [
        'write_user' // hacky and not ideal
    ]
];

function sessionUserHasPermissions(array $required_permissions)
{
    require_once 'application/models/core/AccessDeniedException.php';

    if ($required_permissions === []) {
        return true;
    }
    if (in_array('delete_permission', $required_permissions)) {
        throw new AccessDeniedException("Sorry, We don't delete Permissions!");
    }

    global $___userHasPermissionConstantHierarchy;
    $hierarchy = $___userHasPermissionConstantHierarchy;

    $hierarchy_keys = array_keys($hierarchy);

    require_once 'application/libs/util/log.php';
    
    $user_permissions = (session_status() === PHP_SESSION_NONE || !isset($_SESSION['user']))
        ? ['read_role'] // <---- basic permissions for the public goes here 
        : array_map(function ($elem) {
            return $elem->name;
        }, $_SESSION['user']->permissions);

        foreach ($user_permissions as $permission) {
            if (in_array($permission, $hierarchy_keys)) {
                foreach ($hierarchy[$permission] as  $subsequent_permission) {
                    $user_permissions[] = $subsequent_permission;
                }
            }
        }
        
        // simpleLog("----->>>>user_permissions: " . json_encode($user_permissions));
        
        foreach ($required_permissions as $required_permission) {
            // A required permission isn't in the user permission list
            if (!in_array($required_permission, $user_permissions)) {
                simpleLog("----->>>>required_permissions: " . json_encode($required_permissions));
                simpleLog("this one is missing " . $required_permission);
            return false;
        }
    }

    return true; // stupid bug!
}
function sessionUserHasRole($arg1)
{
    $arr = (is_string($arg1)) ? [$arg1] : $arg1;
    return  in_array($_SESSION['user']->role->name, $arr);
}

// start the application
$app = new Application();
