<?php

class Authentication
{
    private $registry;
    private $justProcessed = false;
    private $loggedIn = false;
    private $loginFailureReason = '';

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function checkForAuthentication()
    {
        //$this->registry->getObject('template')->getPage()->addTag('error','');
        //print_r(array_values($_SESSION));
        //session_start();
        if (isset($_SESSION['sn_auth_session_uid']) && intval($_SESSION['sn_auth_session_uid']) > 0) {
            //echo 'session authorisation: '.$_SESSION['sn_auth_session_uid'];
            $this->sessionAuthenticate(intval($_SESSION['sn_auth_session_uid']));
            if ($this->loggedIn == true) {
                $this->registry->getObject('template')->getPage()->addTag('error', '');
            } else {
                $this->registry->getObject('template')->getPage()->addTag('error', 'Your username or password was incorrect. Please try again');
            }
        } elseif (isset($_POST['log_user']) && $_POST['log_user'] != '' && isset($_POST['log_pass']) && $_POST['log_pass'] != '') {
            //echo 'post authorisation: '.$_POST['log_user'];
            $this->postAuthenticate($_POST['log_user'], $_POST['log_pass']);
            if ($this->loggedIn == true) {
                $this->registry->getObject('template')->getPage()->addTag('error', '');
            } else {
                $this->registry->getObject('template')->getPage()->addTag('error', 'Username and passwords do not match');
            }
        } elseif (isset($_POST['login'])) {
            $this->registry->getObject('template')->getPage()->addTag('error', 'Enter a username and/or password');
        } else {
            $this->registry->getObject('template')->getPage()->addTag('error', '');
            //echo 'no authorisation';
        }
    }

    //POST authenticate
    private function postAuthenticate($u, $p)
    {
        $this->justProcessed = true;
        require_once('registry/user.php');
        $this->user = new User($this->registry, 0, $u, $p);
        if ($this->user->isValid()) {
            if ($this->user->isActive() == false) {
                $this->loggedIn = false;
                $this->loginFailureReason = 'inactive';
            } elseif ($this->user->isBanned() == true) {
                $this->loggedIn = false;
                $this->loginFailureReason = 'banned';
            } else {
                $this->loggedIn = true;
                //$SID = session_id(); if(empty($SID)){session_start();} $_SESSION['sn_auth_session_uid'] = $this->user->getUserID();
                //if(isset($_SESSION['sn_auth_session_uid'])) {unset($_SESSION['sn_auth_session_uid']); session_destroy();}
                //setcookie('sn_auth_session_uid', $this->user->getUserID(),time()+3600*49,'/'); //time()+3600*7
                @session_start();
                $_SESSION['sn_auth_session_uid'] = $this->user->getUserID();
                $this->registry->errorPage('Logged in', 'Thank you for logging in');
            }
        } else {
            $this->loggedIn = false;
            $this->loginFailureReason = 'InvalidCredentials';
        }
    }

    //SESSION Authentication
    public function sessionAuthenticate($uid)
    {
        require_once('registry/user.php');
        $this->user = new User($this->registry, intval($_SESSION['sn_auth_session_uid']), '', '');
        //echo 'user is valid '.$this->user->isValid();
        if ($this->user->isValid()) {
            //echo 'user is active '.$this->user->isActive();
            //echo 'user is banned '.$this->user->isBanned();
            if ($this->user->isActive() == false) {
                $this->loggedIn = false;
                $this->loginFailureReason = 'inactive';
            } elseif ($this->user->isBanned() == true) {
                $this->loggedIn = false;
                $this->loginFailureReason = 'banned';
            } else {
                $this->loggedIn = true;
                //echo 'user is logged in '.$this->loggedIn;
            }
        } else {
            $this->loggedIn = false;
            $this->loginFailureReason = 'nouser';
        }
        if ($this->loggedIn == false) {
            $this->logout();
        }
    }

    public function forceLogin($user, $pass)
    {
        $this->postAuthenticate($user, $pass);
    }

    public function logout()
    {
        //$_SESSION['sn_session_auth_uid'] = null;
        unset($_SESSION['sn_session_auth_uid']);
        //setcookie('sn_session_auth_uid', 0, time()-3600);
        session_destroy();
    }

    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    public function isJustProcessed()
    {
        return $this->justProcessed;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getLoginFailureReason()
    {
        return $this->loginFailureReason;
    }
}

?>