<?php
try {
    session_start(); //Session start
    require("registry/registry.php");
    $registry = new Registry();

    //$_SESSION['sn_auth_session_uid'] = '';
    //setup our core registry objects
    $registry->setObject('template', 'template');
    $registry->setObject('mysqldb', 'db');
    $registry->setObject('authentication', 'authenticate');
    $registry->setObject('urlProcessor', 'url');
    $registry->getObject('url')->getURLData();

    //Settings
    include('config.php');
    //create a database connection
    $registry->getObject('db')->newConnection($configs['db_host_sn'], $configs['db_user_sn'], $configs['db_pass_sn'], $configs['db_name_sn']);

    // store settings in our registry
    $settingsSQL = "SELECT `key`, `value` FROM settings";
    $registry->getObject('db')->executeQuery($settingsSQL);
    while ($setting = $registry->getObject('db')->getRows()) {
        $registry->setSetting($setting['value'], $setting['key']);
    }


    //var_dump(session_status());
    //header('location: /geoboxx/social2/index.php');

    //Check if logged in
    $registry->getObject('authenticate')->checkForAuthentication();


    //The homepage for the network
    $registry->getObject('template')->getPage()->addTag('sitename', $registry->getSetting('sitename'));
    $registry->getObject('template')->getPage()->addTag('baseurl', $registry->getSetting('baseurl'));
    $registry->getObject('template')->getPage()->addTag('siteurl', $registry->getSetting('siteurl'));
    $registry->getObject('template')->buildFromTemplate('header.php', 'main.php', 'footer.php');

    $controllers = array();
    $controllerSQL = "SELECT * FROM controllers WHERE active=1";
    $registry->getObject('db')->executeQuery($controllerSQL);
    while ($control = $registry->getObject('db')->getRows()) {
        $controllers[] = $control['controller'];
    }
    $controller = $registry->getObject('url')->getURLBit(0);

    if (in_array($controller, $controllers)) {
        control($controller, $registry);
    } else {
        control('', $registry);
    }

    if ($registry->getObject('authenticate')->isLoggedIn() == true) {
        $registry->getObject('template')->addTemplateBit('userbar', 'userbar_loggedin.php');
        $registry->getObject('template')->getPage()->addTag('username', $registry->getObject('authenticate')->getUser()->getUsername());
    } else {
        $registry->getObject('template')->addTemplateBit('userbar', 'userbar-guest.php');
    }

    $registry->getObject('template')->parseOutput();
    print $registry->getObject('template')->getPage()->getContentToPrint();
} catch (Exception $e) {
    require_once('errorHandler.php');
    $handle = new ErrorHandler($e->getMessage());
}

function control($bit, $reg)
{
    switch ($bit) {
        case 'authenticate':
            require_once('controllers/authenticate.php');
            $controller = new Authenticate($reg, true);
            break;
        case 'relationships':
            require_once('controllers/relateController.php');
            $controller = new RelateController($reg, true);
            break;
        case 'profile':
            require_once('controllers/profile.php');
            $controller = new Profile($reg, true);
            break;
        case 'stream':
            require_once('controllers/streamController.php');
            $controller = new StreamController($reg, true);
            break;
        case 'messages':
            require_once('controllers/messages.php');
            $controller = new Messages($reg, true);
        default:
            require_once('controllers/streamController.php');
            $controller = new StreamController($reg, true);
            break;
    }
}

?>