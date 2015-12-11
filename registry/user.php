<?php

//User Procedures
class User
{
    private $valid = false;

    public function __construct(Registry $registry, $id = 0, $username = '', $password = '')
    {
        $this->registry = $registry;
        if ($id == 0 && $username != '' && $password != '') {
            $user = $this->registry->getObject('db')->sanitizeData($username);
            $hash = hash('md5', $password);
            $sql = "SELECT * FROM users WHERE username='" . $user . "'";// AND password_hash='".$hash."' AND deleted=0";
            $this->registry->getObject('db')->executeQuery($sql);
            if ($this->registry->getObject('db')->numRows() == 1) {
                //echo 'hi';
                $data = $this->registry->getObject('db')->getRows();
                if ($data['password_hash'] == $hash . $data['password_salt']) {
                    $this->id = $data['ID'];
                    $this->username = $data['username'];
                    $this->active = $data['active'];
                    $this->banned = $data['banned'];
                    $this->admin = $data['admin'];
                    $this->email = $data['email'];
                    $this->pwd_reset_key = (isset($data['pwd_reset_key'])) ? $data['pwd_reset_key'] : '';
                    $this->valid = true;
                }
            } else {
                //echo $sql;
            }
        } elseif ($id > 0) {
            $id = intval($id);
            $sql = "SELECT * FROM users WHERE ID=" . $id . " AND deleted=0";
            $this->registry->getObject('db')->executeQuery($sql);
            if ($this->registry->getObject('db')->numRows() == 1) {
                $data = $this->registry->getObject('db')->getRows();
                $this->id = $data['ID'];
                $this->username = $data['username'];
                $this->active = $data['active'];
                $this->banned = $data['banned'];
                $this->admin = $data['admin'];
                $this->email = $data['email'];
                $this->pwd_reset_key = (isset($data['pwd_reset_key'])) ? $data['pwd_reset_key'] : '';
                $this->valid = true;
            }
        } else {
            $this->valid = false;
            //echo 'hi false';
        }
    }

    public function getUserID()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function isActive()
    {
        return ($this->active == 1) ? true : false;
    }

    public function isBanned()
    {
        return ($this->banned == 1) ? true : false;
    }

    public function isAdmin()
    {
        return ($this->admin == 1) ? true : false;
    }

    public function isValid()
    {
        return $this->valid;
    }
}

?>
