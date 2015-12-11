<?php

class Authenticate
{
    public function __construct(Registry $registry, $directCall)
    {
        $this->registry = $registry;
        $urlBits = $this->registry->getObject('url')->getURLBits();
        $urlNo = 1;
        /*for($i = 1; $i < count($urlBits); $i++)
        {
            if($urlBits[$i] == $urlBits[$i-1])
            {
                $urlNo = $i+1;
            }
            else
            {
                break;
            }
        }*/
        if (isset($urlBits[$urlNo])) {
            switch ($urlBits[$urlNo]) {
                case 'logout':
                    $this->logout();
                    break;
                case 'login':
                    $this->login();
                    break;
                case 'username':
                    require_once('mailout.php');
                    $this->mail = new Mailout();
                    $this->forgotUsername();
                    break;
                case 'password':
                    require_once('mailout.php');
                    $this->mail = new Mailout();
                    $this->forgotPassword();
                    break;
                case 'reset-password':
                    $this->resetPassword(intval($urlBits[$urlNo + 1]), $this->registry->getObject('db')->sanitizeData($urlBits[$urlNo + 2]));
                    break;
                case 'register':
                    $this->registrationDelegator();
                    break;
            }
        }
    }

    private function forgotUsername()
    {
        if (isset($_POST['forgot_email']) && $_POST['forgot_email']) {
            $email = $this->registry->getObject('db')->sanitizeData($_POST['forgot_email']);
            $sql = "SELECT * FROM users WHERE email='" . $email . "'";
            $this->registry->getObject('db')->executeQuery($sql);
            if ($this->registry->getObject('db')->numRows() == 1) {
                include('config.php');
                $data = $this->registry->getObject('db')->getRows(); //Get the details
                $this->mail->startFresh();
                $this->mail->setTo($email); //$_POST['forgot_email']
                $this->mail->setSender($configs['admin_email']);
                $this->mail->setFromName($configs['admin_name']);
                $this->mail->setSubject('Username details for GeoboxX');
                $this->mail->buildFromTemplate('forgot_username.php');
                $tags = array();
                $tags['sitename'] = $configs['sitename'];
                $tags['username'] = $data['username'];
                $tags['siteURL'] = $configs['siteURL'];
                $tags['email'] = $_POST['forgot_email'];
                $this->mail->replaceTags($tags);
                $this->mail->setMethod('sendmail');
                $this->mail->send();
                //Inform about email sent condition
                $this->registry->errorPage('Username reminder sent', 'We have sent you a reminder of your username, to the email address we have on database');
            } else {
                //So, where's the user?
                $this->registry->getObject('template')->buildFromTemplate('header.php', 'forgot_username_main.php', 'footer.php');
                $this->registry->getObject('template')->addTemplateBit('error_message', 'forgot_username_error.php');
                $this->registry->getObject('template')->getPage()->addTag('email', $_POST['forgot_email']);
            }
        } else {
            $this->registry->getObject('template')->buildFromTemplate('header.php', 'forgot_username_main.php', 'footer.php');
            $this->registry->getObject('template')->getPage()->addTag('error_message', '');
        }
    }

    private function forgotPassword()
    {
        if (isset($_POST['forgot_username']) && $_POST['forgot_username'] != '') {
            $u = $this->registry->getObject('db')->sanitizeData($_POST['forgot_username']);
            $sql = "SELECT * FROM users WHERE username='" . $u . "'";
            $this->registry->getObject('db')->executeQuery($sql);
            if ($this->registry->getObject('db')->numRows() == 1) {
                $data = $this->registry->getObject('db')->getRows();
                // have they requested a new password recently?
                if ($data['reset_expires'] > date('Y-m-d h:i:s')) {
                    // inform them
                    $this->registry->errorPage('Error sending password request', 'You have recently requested a password reset link, and as such you must wait a short while before requesting one again.  This is for security reasons.');
                } else {
                    // update their row
                    $changes = array();
                    $rk = $this->generateKey();
                    //echo $rk;
                    $changes['reset_key'] = $rk;
                    $changes['reset_expires'] = date('Y-m-d h:i:s', time() + 86400);
                    $this->registry->getObject('db')->updateRecords('users', $changes, 'ID=' . $data['ID']);
                    include('config.php');
                    // email the user
                    $this->mail->startFresh();
                    $this->mail->setTo($_POST['forgot_email']);
                    $this->mail->setSender($configs['admin_email']);
                    $this->mail->setFromName($configs['admin_name']);
                    $this->mail->setSubject('Password reset request for ' . $this->registry->getSetting('sitename'));
                    $this->mail->buildFromTemplate('forgot_password.php');
                    $tags = $this->values;
                    $tags['sitename'] = $configs['sitename'];
                    $tags['username'] = $data['username'];
                    $url = $this->registry->buildURL(array('authenticate', 'reset-password', $data['ID'], $rk), '', 1);
                    $tags['url'] = $url;
                    $tags['siteURL'] = $configs['baseURL'];
                    $tags['email'] = $_POST['forgot_email'];
                    $tags['username'] = $_POST['forgot_username'];
                    $this->mail->replaceTags($tags);
                    $this->mail->setMethod('sendmail');
                    $this->mail->send();

                    // tell them that we emailed them
                    $this->registry->errorPage('Password reset link sent', 'We have sent you a link which will allow you to reset your account password');
                }
            } else {
                // no user found
                $this->registry->getObject('template')->buildFromTemplate('header.php', 'forgot_password_main.php', 'footer.php');
                $this->registry->getObject('template')->addTemplateBit('error_message', 'forgot_password_error.php');
            }
        } else {
            // form template
            $this->registry->getObject('template')->buildFromTemplate('header.php', 'forgot_password_main.php', 'footer.php');
            $this->registry->getObject('template')->getPage()->addTag('error_message', '');
        }
    }

    private function resetPassword($user, $key)
    {
        $this->registry->getObject('template')->getPage()->addTag('user', $user);
        $this->registry->getObject('template')->getPage()->addTag('key', $key);
        $sql = "SELECT * FROM users WHERE ID={$user} AND reset_key='{$key}'";
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() == 1) {
            $data = $this->registry->getObject('db')->getRows();
            if ($data['reset_expiry'] > date('Y-m-d h:i:s')) {
                $this->registry->errorPage('Reset link expired', 'Password reset links are only valid for 24 hours.  This link is out of date and has expired.');
            } else {
                if (isset($_POST['reset_password'])) {
                    if (strlen($_POST['reset_password']) < 6) {
                        $this->registry->errorPage('Password too short', 'Sorry, your password was too short, passwords must be greater than 6 characters');
                    } else {
                        if ($_POST['reset_password'] != $_POST['reset_password_confirm']) {
                            $this->registry->errorPage('Passwords do not match', 'Your password and password confirmation do not match, please try again.');
                        } else {
                            // reset the password
                            $changes = array();
                            $saltPass = $this->registry->getObject('reg')->saltPass();
                            $changes['password_hash'] = hash('md5', $_POST['reset_passowrd']) . $saltPass;
                            $changes['password_salt'] = $saltPass;
                            $this->registry->getObject('db')->updateRecords('users', $changes, 'ID=' . $user);
                            $this->registry->errorPage('Password reset', 'Your password has been reset to the one you entered');
                        }
                    }
                } else {
                    // show the form
                    $this->registry->getObject('template')->buildFromTemplate('header.php', 'reset_password.php', 'footer.php');
                }
            }
        } else {
            $this->registry->errorPage('Invalid details', 'The password reset link was invalid');
        }
    }

    public function generateKey($len = 7)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        // 36 chars
        $tor = '';
        for ($i = 0; $i < $len; $i++) {
            $tor .= $chars[rand() % 35];
        }
        return $tor;
    }

    private function login()
    {
        if ($this->registry->getObject('authenticate')->isJustProcessed()) //Template
        {
            if (isset($_POST['login']) && $this->registry->getObject('authenticate')->isLoggedIn() == false) {
                $this->registry->getObject('template')->addTemplateBit('error_message', 'login_error.php');// invalid details
            } else //for redirection
            {
                if ($_POST['referer'] == '') {
                    $referer = $this->registry->getSetting('siteurl');
                    echo '<script>window.location="'.$_POST['referer'].'";</script>';
                    //$this->registry->redirectUser($referer, 'Logged in', 'Thanks, you are now logged in, you are now being redirected to the page you were previously on', false);
                } else {
                    $this->registry->redirectUser($_POST['referer'], 'Logged in', 'Thanks, you are now logged in, you are now being redirected to the page you were previously on', false);
                }
            }
        } else {
            if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
                $this->registry->errorPage('Already logged in', 'You cannot login as you are already logged in as <strong>' . $this->registry->getObject('authenticate')->getUser()->getUsername() . '</strong>');
            } else {
                $this->registry->getObject('template')->buildFromTemplate('header.php', 'login_main.php', 'footer.php');
                $this->registry->getObject('template')->getPage()->addTag('referer', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));
            }
        }

    }

    private function logout()
    {
        $this->registry->getObject('authenticate')->logout();
        //$this->registry->getObject('template')->addTemplateBit('userbar', 'userbar-guest.php');
        if ($this->registry->getObject('authenticate')->isLoggedIn() == false) {
            $logFail = $this->registry->getObject('authenticate')->getLoginFailureReason();
            //$this->registry->errorPage($logFail, "You ain't logged in yet!");
            $this->registry->ajaxReply(array('status' => 'Could Not Log You Out', 'content' => ''));
        }
        //$this->registry->ajaxReply(array('status'=>'Logged You Out', 'content'=>''));
        echo '<script>window.location="' . $this->registry->getSetting("baseurl") . '";</script>';
        //$this->registry->getObject('template')->buildFromTemplate('header.php', 'login.php', 'footer.php');
        //$this->login();
    }

    //Delegate control to the registration controller
    private function registrationDelegator()
    {
        require_once('registration.php');
        $rc = new Registration($this->registry);
    }
}

?>