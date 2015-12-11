<?php

class Registration
{
    //Properties of this class
    private $fields = array('user' => 'username', 'fName' => 'first name', 'lName' => 'last name', 'pass' => 'password', 'pass_confirm' => 'password confirmation', 'email' => 'email address'); //
    private $regError = array(); //Values entered that were wrong
    private $regErrorLabel = array(); //Labels of the fields whose values were entered wrong
    private $uSubVal = array(); //Values submitted by user
    private $uSanVal = array(); //Sanitized values of the submitted values by user
    private $active = 0; //Value shows state of user
    private $extras; //For the additional fields

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        $this->extras = new AdditionalFields($this->registry);
        $urlBits = $this->registry->getObject('url')->getURLBits();
        if (isset($_POST['process_registration'])) {
            if ($this->checkReg() == true) {
                $user_id = $this->processReg();
                if ($this->active == 1) {
                    $this->registry->getObject('authenticate')->forceLogin($this->uSubVal['reg_user'], hash('md5', $this->uSubVal['reg_pass']));
                }
                $this->uiRegProcessed();
            } else {
                $this->uiRegister(true);
            }
        } elseif (isset($urlBits[2]) && $urlBits[2] = 'activate' && isset($urlBits[4])) {
            $this->activateReg($urlBits[3], $urlBits[4]);
        } else {
            $this->uiRegister(false);
        }
    }

    private function saltPass()
    {
        //$string = openssl_random_pseudo_bytes(5, $cstrong);
        //if($cstrong == true) {return $string;}
        //else {$this->saltPass();}
        return mt_rand(0, 99999);
    }

    private function checkReg()
    {
        $allClear = true; //Bool shows if the entries are good to go
        //Check if all fields are set
        foreach ($this->fields as $field => $name) {
            if (!isset($_POST['reg_' . $field]) || $_POST['reg_' . $field] == '') {
                $allClear = false;
                $this->regError[] = "You must enter a " . $name;
                $this->regErrorLabel['reg_' . $field . '_label'] = "error";
            }
        }
        //Check if passwords match
        if ($_POST['reg_pass'] != $_POST['reg_pass_confirm']) {
            $allClear = false;
            $this->regError[] = "The entered passwords don't match";
            $this->regErrorLabel['reg_pass_label'] = "error";
            $this->regErrorLabel['reg_pass_confirm_label'] = "error";
        }
        //Check length of password
        if (strlen($_POST['reg_pass']) < 8) {
            $allClear = false;
            $this->regError[] = "Password entered is too short. Enter one between 8 and 15 characters";
            $this->regErrorLabel['reg_pass_label'] = "error";
        }
        if (strlen($_POST['reg_pass']) > 15) {
            $allClear = false;
            $this->regError[] = "Password entered is long. Enter one between 8 and 15 characters";
            $this->regErrorLabel['reg_pass_label'] = "error";
        }
        //Check for headers email address
        if (strpos((urldecode($_POST['reg_email'])), "\r") === true || strpos((urldecode($_POST['reg_email'])), "\n") === true) {
            $allClear = false;
            $this->regError[] = "Please enter a valid email address";
            $this->regErrorLabel['reg_email_label'] = "error";
        }
        //Check for validity of email address
        if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})^", $_POST['reg_email'])) {
            $allClear = false;
            $this->regError[] = "Please enter a valid email address";
            $this->regErrorLabel['reg_email_label'] = "error";
        }
        //terms accepted
        if (!isset($_POST['reg_terms']) || $_POST['reg_terms'] != 1) {
            $allClear = false;
            $this->regError[] = "Registration Terms and Conditions not accepted!";
            $this->regErrorLabel['reg_terms_label'] = "error";
        }
        //Check if username and/or email ID is used
        $u = '';
        $e = '';
        if (isset($_POST['reg_user']) && isset($_POST['reg_email'])) {
            $u = $this->registry->getObject('db')->sanitizeData($_POST['reg_user']);
            $e = $this->registry->getObject('db')->sanitizeData($_POST['reg_email']);
            $sql = "SELECT * FROM users WHERE username='" . $u . "' OR email='" . $e . "'";
            $this->registry->getObject('db')->executeQuery($sql);
            if ($this->registry->getObject('db')->numRows() == 2) {
                $allClear = false;
                $this->regError[] = "Both the username and the email address are already in use";
                $this->regErrorLabel['reg_user_label'] = "error";
                $this->regErrorLabel['reg_email_label'] = "error";
            } elseif ($this->registry->getObject('db')->numRows() == 1) {
                //One or both match
                $u = $this->registry->getObject('db')->sanitizeData($_POST['reg_user']);
                $e = $this->registry->getObject('db')->sanitizeData($_POST['reg_email']);
                $data = $this->registry->getObject('db')->getRows();
                if ($data['username'] == $u && $data['email'] == $e) {
                    $allClear = false;
                    $this->regError[] = "Both the username and the email address are already in use";
                    $this->regErrorLabel['reg_user_label'] = "error";
                    $this->regErrorLabel['reg_email_label'] = "error";
                } elseif ($data['username'] == $u && $data['email'] != $e) {
                    $allClear = false;
                    $this->regError[] = "The username is already in use";
                    $this->regErrorLabel['reg_user_label'] = "error";
                } else {
                    $allClear = false;
                    $this->regError[] = "The email is already in use";
                    $this->regErrorLabel['reg_email_label'] = "error";
                }
            }
        }

        //captcha
        if ($this->registry->getSetting('captcha.enabled') == 1) {
            //captcha check
        }

        //hook
        if ($this->extras->checkRegExtra() == false) {
            $allClear = false;
        }
        if ($allClear) {
            $a1 = $this->generateKey(5);
            $this->uSanVal['username'] = $u;
            $this->uSanVal['email'] = $e;
            $this->uSanVal['password_hash'] = hash('md5', $_POST['reg_pass']) . $a1; //Store salted hashed password.$a1
            $this->uSanVal['password_salt'] = $a1;
            $this->uSanVal['active'] = $this->active;
            $this->uSanVal['admin'] = 0;
            $this->uSanVal['banned'] = 0;
            //$auth = new Authenticate($this->registry, true);
            //$this->uSanVal['reset_key'] = $this->auth->generateKey();
            $this->uSubVal['name'] = $this->registry->getObject('db')->sanitizeData($_POST['reg_fName']) . ' ' . $this->registry->getObject('db')->sanitizeData($_POST['reg_lName']);
            $this->uSubVal['reg_user'] = $_POST['reg_user'];
            $this->uSubVal['reg_pass'] = $_POST['reg_pass'];
            return true;
        } else {
            $this->uSubVal['reg_user'] = $_POST['reg_user'];
            $this->uSubVal['reg_email'] = $_POST['reg_email'];
            $this->uSubVal['reg_pass'] = $_POST['reg_pass'];
            $this->uSubVal['reg_pass_confirm'] = $_POST['reg_pass_confirm'];
            $this->uSubVal['reg_captcha'] = (isset($_POST['reg_captcha']) ? $_POST['reg_captcha'] : '');
            return false;
        }
    }

    //Process the user registration and create the user and user profile
    private function processReg()
    {
        $this->registry->getObject('db')->insertRecords('users', $this->uSanVal); //inserting
        $this->uid = $this->registry->getObject('db')->lastInsertID(); //Get ID
        $this->createProfile($this->uid); //Call extension to insert the profile
        return $this->uid; //Return the id for framework reference
    }

    //Create the user profile
    private function createProfile($uid)
    {
        $tables = array(); //Group the profile fields by table, so we can insert one at a time
        $tableData = array();
        foreach ($this->extras->extraFields as $field => $value) {
            if (!(in_array($value['table'], $tables))) {
                $tables[] = $value['table'];
                if (isset($tableData[$value['table']])) {
                    $ar = array($value['field'] => $this->extras->uSanVal['reg_' . $field]);
                    $tableData[$value['table']] = array_merge($tableData[$value['table']], $ar);
                } else {
                    $tableData[$value['table']] = array('user_id' => $uid, $value['field'] => $this->extras->uSanVal['reg_' . $field]);
                }
            } else {
                if (isset($tableData[$value['table']])) {
                    $ar = array($value['field'] => $this->extras->uSanVal['reg_' . $field]);
                    $tableData[$value['table']] = array_merge($tableData[$value['table']], $ar);
                } else {
                    $tableData[$value['table']] = array('user_id' => $uid, $value['field'] => $this->extras->uSanVal['reg_' . $field]);
                }
            }
        }
        $ar = array('name' => $this->uSubVal['name']);
        unset($tableData['profile']['table']);
        $tableData['profile'] = array_merge($tableData['profile'], $ar);
        foreach ($tableData as $field => $value) {
            $this->registry->getObject('db')->insertRecords($field, $value);
        }
        //require_once('relateController.php');
        //$rel = new RelateController($uid, false);
        //$rel->createRelationship(0);
        return true;
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

    private function uiRegProcessed()
    {
        include('config.php');
        require_once('mailout.php');
        $mail = new Mailout();
        $mail->startFresh();
        $mail->setTo($_POST['reg_email']);
        $mail->setSender($configs['admin_email']);
        $mail->setFromName($configs['admin_name']);
        $mail->setSubject('Registration details for GeoboxX');
        $mail->buildFromTemplate('reg_complete_mail.php');
        $tags = array();
        $tags['sitename'] = $configs['sitename'];
        $tags['name'] = $this->uSubVal['name'];
        $tags['username'] = $this->uSubVal['reg_user'];
        $tags['siteURL'] = $configs['siteURL'];
        $tags['email'] = $this->uSubVal['reg_email'];
        $ak = $this->generateKey(8);
        $url = $this->registry->buildURL(array('authenticate', 'register', 'activate', $this->uid, $ak), '', 1);
        $tags['url'] = $url;
        $mail->replaceTags($tags);
        $mail->setMethod('sendmail');
        $mail->send();
        $update = array('activation_key' => $ak);
        echo $this->registry->getObject('db')->updateRecords('users', $update, "`username`='" . $this->uSubVal['reg_user'] . "'");
        $this->registry->getObject('template')->getPage()->setTitle('Mail sent');
        $this->registry->getObject('template')->buildFromTemplate('header.php', 'message.php', 'footer.php');
        $this->registry->getObject('template')->getPage()->addTag('heading', 'Activation mail sent');
        $this->registry->getObject('template')->getPage()->addTag('content', 'Please check you inbox or spam folder for further instructions.');
    }

    private function uiRegister($error)
    {
        include('config.php');
        $this->registry->getObject('template')->getPage()->setTitle('Register for ' . $configs['sitename']);
        $this->registry->getObject('template')->buildFromTemplate('header.php', 'reg_main.php', 'footer.php');

        //blank out field tags
        $fields = array_keys($this->fields);
        $fields = array_merge($fields, $this->extras->getExtraFields());
        foreach ($fields as $key) {
            $this->registry->getObject('template')->getPage()->addTag('reg_' . $key . '_label', '');
            $this->registry->getObject('template')->getPage()->addTag('reg_' . $key, '');
        }
        if ($error == false) {
            $this->registry->getObject('template')->getPage()->addTag('error', '');
        } else {
            $this->registry->getObject('template')->addTemplateBit('error', 'reg_error.php');
            $errorsData = array();
            $errors = $this->getRegErrors();//array_merge( $this->regError, $this->getRegErrors() );
            $errorLabels = $this->getErrorLabels();
            foreach ($errors as $err) {
                $errorsData[] = array('error_text' => $err);
            }
            $errorsCache = $this->registry->getObject('db')->cacheData($errorsData);
            $this->registry->getObject('template')->getPage()->addTag('errors', array('DATA', $errorsCache));
            $toFill = array_merge($this->getRegVal(), $this->getRegErrors(), $this->getErrorLabels()); // $this->getRegVal(), $this->getRegVal(), $this->regErrorLabel, $this->getErrorLabels()
            foreach ($toFill as $tag => $value) {
                $this->registry->getObject('template')->getPage()->addTag($tag, $value);
                //echo '$key = '.$tag.'$data = '.$value;
            }
        }
    }

    public function getRegErrors()
    {
        $allErrors = array_merge($this->regError, $this->extras->getExtraErrors());
        return $allErrors;
    }

    public function getRegVal()
    {
        return array_merge($this->uSubVal, $this->extras->uSubVal);
    }

    public function getErrorLabels()
    {
        return array_merge($this->regErrorLabel, $this->extras->getExtraErrorLabels());
    }

    private function activateReg($user, $key)
    {
        $sql = "SELECT * FROM `users` WHERE `ID`=" . $user . " AND `activation_key`='" . $key . "'";
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() == 1) {
            $update = array('active' => 1);
            $this->registry->getObject('db')->updateRecords('users', $update, "`ID`=" . $user);
            $this->registry->getObject('template')->getPage()->setTitle('Registration for ' . $configs['sitename'] . ' complete.');
            $this->registry->getObject('template')->buildFromTemplate('header.php', 'reg_complete.php', 'footer.php');
        } else {
            echo $sql;
        }
    }
}

//Check page 75 to hook additional fields on
class AdditionalFields
{
    public $extraFields = array();
    public $registry;
    public $exErr = array();
    public $exErrLbl = array();
    public $uSubVal = array();
    public $uSanVal = array();

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->extraFields['gender'] = array('fieldName' => 'Gender', 'table' => 'profile', 'field' => 'gender', 'type' => 'list', 'required' => true, 'options' => array('male', 'female'));
        $this->extraFields['dob'] = array('fieldName' => 'Date of Birth', 'table' => 'profile', 'field' => 'dob', 'type' => 'text', 'required' => true);
        $this->extraFields['loc'] = array('fieldName' => 'Location', 'table' => 'profile', 'field' => 'location', 'type' => 'text', 'required' => true);

    }

    public function getExtraFields() //Add the extra fields except username, password and email
    {
        return array_keys($this->extraFields);
    }


    public function checkRegExtra() //Check error in submission for extra fields
    {
        $valid = true;
        foreach ($this->extraFields as $field => $data) {
            if ((!isset($_POST['reg_' . $field]) || $_POST['reg_' . $field] == '') && $data['required'] = true) {
                $this->uSubVal[$field] = $_POST['reg_' . $field];
                $this->exErrLbl['reg_' . $field . '_label'] = 'error';
                $this->exErr[] = 'Field ' . $data['fieldName'] . ' cannot be blank';
                $valid = false;
            } elseif ($_POST['reg_' . $field] == '') {
                $this->uSubVal['reg_' . $field] = '';
            } else {
                if ($data['type'] == 'text') {
                    $this->uSanVal['reg_' . $field] = $this->registry->getObject('db')->sanitizeData($_POST['reg_' . $field]);
                    $this->uSubVal['reg_' . $field] = $_POST['reg_' . $field];
                } elseif ($data['type'] == 'int') {
                    $this->uSanVal['reg_' . $field] = intval($_POST['reg_' . $field]);
                    $this->uSubVal['reg_' . $field] = $_POST['reg_' . $field];
                } elseif ($data['type'] == 'list') {
                    if (!in_array($_POST['reg_' . $field], $data['options'])) {
                        $this->uSubVal['reg_' . $field] = $_POST['reg_' . $field];
                        $this->uSanVal['reg_' . $field] = $this->registry->getObject('db')->sanitizeData($_POST['reg_' . $field]);

                        $this->exErrLbl['reg_' . $field . '_label'] = 'error';
                        $this->exErr[] = 'Field ' . $data['fieldName'] . ' was not valid';

                        $valid = false;
                    } else {
                        $this->uSanVal['reg_' . $field] = intval($_POST['reg_' . $field]);
                        $this->uSubVal['reg_' . $field] = $_POST['reg_' . $field];
                    }
                } else {
                    $method = 'validate' . $data['type'];
                    if ($this->$method($_POST['reg_' . $field]) == true) {
                        $this->uSanVal['reg_' . $field] = $this->registry->getObject('db')->sanitizeData($_POST['reg_' . $field]);
                        $this->uSubVal['reg_' . $field] = $_POST['reg_' . $field];
                    } else {
                        $this->uSanVal['reg_' . $field] = $this->registry->getObject('db')->sanitizeData($_POST['reg_' . $field]);
                        $this->uSubVal['reg_' . $field] = $_POST['reg_' . $field];
                        $this->exErr[] = 'Field ' . $data['fieldName'] . ' was not valid';
                        $valid = false;
                    }
                }
            }
        }
        //if( $valid == true ){	return true;} else{	return false;}
        return $valid;
    }

    public function getExtraErrors()
    {
        return $this->exErr;
    }

    public function getExtraErrorLabels()
    {
        return $this->exErrLbl;
    }

    /*Validate the date
    @param String the date
    @return array containing the date, month and year
    */
    public function valiDate($date)
    {
        if (substr_count($date, '/') != 2) {
            return false;
        } else {
            $parts = explode('/', $date);
            if ($parts[1] < 0 || $parts[1] > 12) //Check for validity of month
            {
                return false;
            } else {
                //$today = getdate(); //If today is supposed to be the limit
                if ($parts[2] < 1900 || $parts[2] > 2005) //Check if the year lies between 1900 - 2005
                {
                    return false;
                } else {
                    if ($parts[1] == 1 || $parts[1] == 3 || $parts[1] == 5 || $parts[1] == 7 || $parts[1] == 8 || $parts[1] == 10 || $parts[1] == 12) // Months which have 31 days
                    {
                        if ($parts[0] < 0 || $parts[0] > 31) {
                            return false;
                        } else return true;
                    } elseif ($parts[1] == 4 || $parts[1] == 6 || $parts[1] == 9 || $parts[1] == 11) //Months which have 30 days
                    {
                        if ($parts[0] < 0 || $parts[0] > 30) {
                            return false;
                        } else return true;
                    } elseif ($parts[1] == 2 && $parts[2] % 4 == 0) //February leap year
                    {
                        if ($parts[0] < 0 || $parts[0] > 29) {
                            return false;
                        } else return true;
                    } elseif ($parts[1] == 2 && $parts[2] % 4 != 0) //February non-leap year
                    {
                        if ($parts[0] < 0 || $parts[0] > 28) {
                            return false;
                        } else return true;
                    }
                }
            }
        }
    }

}

?>