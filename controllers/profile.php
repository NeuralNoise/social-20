<?php

class ProfileModel //The profile model
{
    private $registry;
    private $user_id;
    private $id;
    private $profile_fields = array('name', 'username', 'gender', 'dob', 'photo', 'bio');
    private $name;
    private $username;
    private $gender;
    private $dob;
    private $photo;
    private $bio;
    private $location;
    private $changes = array();

    public function __construct(Registry $registry, $id = 0)
    {
        $this->registry = $registry;
        if ($id != 0) {
            $this->id = $id;
            $sql = "SELECT * FROM profile WHERE user_id=" . $id;
            $this->registry->getObject('db')->executeQuery($sql);
            if ($this->registry->getObject('db')->numRows() == 1) {
                $data = $this->registry->getObject('db')->getRows();
                $ar = array();
                foreach ($data as $key => $value) {
                    //$this->key = $value;
                    $ar[] = $key;
                }
                for ($i = 0; $i < count($ar); $i++) {
                    $index = $ar[$i];
                    switch ($index) {
                        case 'user_id':
                            $this->user_id = $data[$index];
                            break;
                        case 'name':
                            $this->name = $data[$index];
                            break;
                        case 'username':
                            $this->username = $data[$index];
                            break;
                        case 'location':
                            $this->location = $data[$index];
                            break;
                        case 'gender':
                            $this->gender = $data[$index];
                            break;
                        case 'dob':
                            $this->dob = $data[$index];
                            break;
                        case 'bio':
                            $this->bio = $data[$index];
                            break;
                        case 'photo':
                            $this->photo = $data[$index];
                            break;
                    }
                }
            }
        }
    }

    public function setName($name)
    {
        if ($name != null || $name != '') {
            $this->name = $name;
        }
    }

    public function setGender($gender)
    {
        if ($gender != null || $gender != '') {
            $this->gender = $gender;
        }
    }

    public function setBio($bio)
    {
        if ($bio != null || $bio != '') {
            $this->bio = $bio;
        }
    }

    public function setLocation($location)
    {
        if ($location != null || $location != '') {
            $this->location = $location;
        }
    }

    public function setDOB($dob, $formatted = true)
    {
        if ($dob != null || $dob != '') {
            if ($formatted) {
                $this->dob = $dob;
            } else {
                $temp = explode('/', $dob);
                if (count($temp) != 1) {
                    $this->dob = $temp[0] . '-' . $temp[1] . '-' . $temp[2];
                } else {
                    $this->dob = $dob;
                }
            }
        }
    }

    public function setPhoto($photo)
    {
        if ($photo != null || $photo != '') {
            $this->photo = $photo;
        }
    }

    //Get what you set
    public function getName()
    {
        return $this->name;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getID()
    {
        return $this->user_id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getLocation()
    {
        return $this->location;
    }

    //Save the user profile
    public function save()
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true && $this->registry->getObject('authenticate')->getUser()->getUserID() == $this->id || $this->registry->getObject('authenticate')->getUser()->isAdmin() == true) {
            foreach ($this->profile_fields as $fields) {
                //$this->changes[$fields] = $this->fields;
                switch ($fields) {
                    case 'user_id':
                        $this->changes[$fields] = $this->user_id;
                        break;
                    case 'name':
                        $this->changes[$fields] = $this->name;
                        break;
                    case 'username':
                        $this->changes[$fields] = $this->username;
                        break;
                    case 'location':
                        $this->changes[$fields] = $this->location;
                        break;
                    case 'gender':
                        $this->changes[$fields] = $this->gender;
                        break;
                    case 'dob':
                        $this->changes[$fields] = $this->dob;
                        break;
                    case 'bio':
                        $this->changes[$fields] = $this->bio;
                        break;
                    case 'photo':
                        $this->changes[$fields] = $this->photo;
                        break;
                }
            }
            $this->registry->getObject('db')->updateRecords('profile', $this->changes, "`user_id`=" . $this->user_id);
            //var_dump($this->registry->getObject('db')->affectedRows());
            if ($this->registry->getObject('db')->affectedRows() == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Convert to tags
    public function toTags($prefix = '')
    {
        //echo $this->id.' '.$this->user_id.' '.$this->name.' '.$this->gender.' '.$this->dob.' '.$this->bio.' '.$this->photo;
        //var_dump(get_object_vars($this));
        $this->photo = $this->username . '/' . $this->photo;
        foreach ($this as $field => $data) {
            if (!is_object($data) && !is_array($data)) {
                $this->registry->getObject('template')->getPage()->addTag($prefix . $field, $data);
            }
        }
    }

    public function toArray($prefix = '')
    {
        $r = array();
        foreach ($this as $field => $data) {
            if (!is_object($data) && !is_array($data)) {
                $r[$field] = $data;
            }
        }
        return $r;
    }
}

class Profile //The profile controller
{
    public function __construct(Registry $registry, $directCall = true)
    {
        $this->registry = $registry;
        $urlBits = $this->registry->getObject('url')->getURLBits();
        $urlNo = 1;
        /*for($i = 1; $i < count($urlBits); $i++)
        {
            if(intval($urlBits[$i]) ==0 )
            {
                if( isset($urlBits[$i+2]) && isset($urlBits[$i+1]) )
                {
                    if( $urlBits[$i] == $urlBits[$i + 1] ) { $urlNo = $i + 2; }
                }
                elseif( isset($urlBits[$i+3]) && isset($urlBits[$i+2]) )
                {
                    if($urlBits[$i] == $urlBits[$i + 2]) { $urlNo = $i + 3; }
                }
                else
                {
                    continue;
                }
            }
            else $urlNo =count($urlBits) - 2;
        }
        if($urlBits[count($urlBits) - 1] != 'view' || $urlBits[count($urlBits) - 1] != 'statuses' || $urlBits[count($urlBits) - 1] != 'edit')
        {
            if($urlBits[count($urlBits) - 2] == 'view' || $urlBits[count($urlBits) - 2] == 'statuses' || $urlBits[count($urlBits) - 2] == 'edit')
            {
                $urlNo = count($urlBits) - 2;
            }
            else
            {
                $urlNo = count($urlBits) - 1;
            }
        }
        else
        {
            $urlNo = count($urlBits) - 1;
        }*/
        switch ($urlBits[$urlNo]) {
            case 'view':
                if (isset($urlBits[$urlNo + 1]) && isset($_SESSION['sn_auth_session_uid'])) {
                    if (intval($urlBits[$urlNo + 1]) == intval($_SESSION['sn_auth_session_uid'])) {
                        $this->staticContentDelegator(intval($_SESSION['sn_auth_session_uid']), 'view');
                        $this->registry->getObject('template')->getPage()->addTag('edit', '<a href = "{siteurl}profile/edit">Edit Profile</a>');
                        $this->registry->getObject('template')->getPage()->addTag('subscribe', '');
                    } else {
                        $this->staticContentDelegator(intval($urlBits[$urlNo + 1]), 'view');
                        $this->registry->getObject('template')->getPage()->addTag('edit', '');
                        $this->addSubscribe($urlBits[$urlNo + 1]);
                    }
                } elseif (isset($_SESSION['sn_auth_session_uid'])) {
                    $this->staticContentDelegator(intval($_SESSION['sn_auth_session_uid']), 'view');
                    $this->registry->getObject('template')->getPage()->addTag('edit', '<a href = "' . $this->registry->getSetting('siteurl') . 'profile/edit">Edit Profile</a>');
                    $this->registry->getObject('template')->getPage()->addTag('subscribe', '');
                } else {
                    $this->registry->errorPage('Error', 'You are not connected');
                }
                break;
            case 'statuses':
                if (isset($urlBits[$urlNo + 1]) && isset($_SESSION['sn_auth_session_uid'])) {
                    $this->statusDelegator(intval($urlBits[$urlNo + 1]));
                } elseif (isset($_SESSION['sn_auth_session_uid'])) {
                    $this->statusDelegator(intval($_SESSION['sn_auth_session_uid']));
                } else {
                    $this->registry->errorPage('Error', 'You are not connected');
                }
                break;
            case 'edit':
                if (isset($urlBits[$urlNo + 1])) {
                    if (intval($urlBits[$urlNo + 1]) == intval($_SESSION['sn_auth_session_uid'])) {
                        $this->staticContentDelegator(intval($_SESSION['sn_auth_session_uid']), 'edit');
                    } else {
                        $this->registry->errorPage('Error', 'You are not authorised');
                    }
                } elseif (isset($_SESSION['sn_auth_session_uid'])) {
                    $this->staticContentDelegator(intval($_SESSION['sn_auth_session_uid']), 'edit');
                } else {
                    $this->registry->errorPage('Error', 'You are not connected');
                }
                break;
            case 'addcomment':
                $stat = new ProfStatus($this->registry, $this->registry->getObject('authenticate')->getUser()->getUserID());
                $stat->addComment($_POST['usr_comment'], $urlBits[2], $this->registry->getObject('authenticate')->getUser()->getUserID());
                break;
            case 'deletecomment':
                $stat = new ProfStatus($this->registry, $this->registry->getObject('authenticate')->getUser()->getUserID());
                $stat->deleteComment($_POST['del_comment'], $urlBits[2], $this->registry->getObject('authenticate')->getUser()->getUserID());
                break;
            case 'subscribe':

                break;
            default:
                $this->profileError();
                break;
        }
    }

    private function staticContentDelegator($user, $job)
    {
        $this->commonTemplateTags($user);
        $sc = new ProfInfo($this->registry, $job, $user);
    }

    private function statusDelegator($user)
    {
        $this->commonTemplateTags($user);
        $sc = new ProfStatus($this->registry, $user);
    }

    private function profileError()
    {
        $this->registry->errorPage('Error!', 'The link you followed was invalid. Please try again.');
    }

    //Common template tag for all profile aspects
    private function commonTemplateTags($user)
    {
        //Get a sample of 5 friends
        require_once('relation.php');
        $rel = new RelationsGet($this->registry);
        $cache = $rel->getByUser($user, true, 5);
        //var_dump($this->registry->getObject('db')->dataFromCache($cache));
        $this->registry->getObject('template')->getPage()->addTag('profile_friends_sample', array('SQL', $cache));
        $profile = new ProfileModel($this->registry, $user);
        $profile->toTags('p_');
        $name = $profile->getName();
        $username = $profile->getUsername();
        $uid = $profile->getID();
        $photo = $profile->getPhoto();
        $this->registry->getObject('template')->getPage()->addTag('profile_name', $name);
        $this->registry->getObject('template')->getPage()->addTag('profile_username', $username);
        $this->registry->getObject('template')->getPage()->addTag('profile_pic', $photo);
        $this->registry->getObject('template')->getPage()->addTag('profile_id', $uid);
        $profie = '';
    }

    public function addSubscribe($pro)
    {
        require_once('relation.php');
        $rel = new RelationsGet();
        $sub = $rel->getSubscribers($this->registry->getObject('authenticate')->getUser()->getUserID());
        if (!in_array($pro, $sub)) {
            $this->registry->getObject('template')->getPage()->addTag('subscribe', "<a href = '{siteurl}relationships/subscribe/" . $pro . "'>Subscribe</a>");
        } else {
            $this->registry->getObject('template')->getPage()->addTag('subscribe', 'Subscribed');
        }
    }
}

class ProfInfo //The profile information controller
{
    public function __construct(Registry $registry, $job, $user)
    {
        $this->registry = $registry;
        switch ($job) {
            case 'edit':
                $this->editProfile();
                break;
            default:
                $this->viewProfile($user);
                break;
        }
    }

    private function viewProfile($user)
    {
        $this->registry->getObject('template')->buildFromTemplate('header.php', 'profile_info_view.php', 'footer.php');
        $profile = new ProfileModel($this->registry, $user);
        $profile->toTags('p_');
    }

    //Edit profile
    private function editProfile()
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
            $user = $this->registry->getObject('authenticate')->getUser()->getUserID();
            if (isset($_POST) && count($_POST) > 0) //Something has been posted
            {
                $profile = new ProfileModel($this->registry, $user);
                $profile->setBio($this->registry->getObject('db')->sanitizeData($_POST['bio']));
                $profile->setName($this->registry->getObject('db')->sanitizeData($_POST['name']));
                $profile->setGender($this->registry->getObject('db')->sanitizeData($_POST['gender']), false);
                $profile->setDOB($this->registry->getObject('db')->sanitizeData($_POST['dob']), false);
                if (isset($_FILES['profile_pic'])) {
                    require_once('mediaManager.php');
                    $im = new ImageManager();
                    $image = $im->loadFromPost('profile_pic', $this->registry->getSetting('upload_path') . 'profile/' . $profile->getUsername() . '/', time()); //$images .= $image;
                    if ($image == true) {
                        $im->resizeScale(50);
                        $im->save($this->registry->getSetting('upload_path') . 'profile/' . $im->getName());
                        $profile->setPhoto($im->getName());
                    }
                    //$this->registry->redirectUser('profile/edit/'.$profile->getID(), 'Image Saved', 'Image upload success');
                } else {
                    $this->registry->errorPage('Error', 'Image uploading failed');
                }
                $profile->save();
                $this->registry->redirectUser('profile/view', 'Profile saved', 'The changes to your profile have been saved.'); //array('profile', 'view', 'edit')
            } else {
                //Show the edit form
                $this->registry->getObject('template')->buildFromTemplate('header.php', 'profile_info_edit.php', 'footer.php');
                $profile = new ProfileModel($this->registry, $user);
                $profile->toTags('p_');
            }
        } else {
            $this->registry->errorPage('Error', 'Please login to continue');
        }
    }
}

class ProfStatus //The profile status controller
{
    public function __construct(Registry $registry, $user, $job = '')
    {
        $this->registry = $registry;
        if (isset($_POST['addComment']) == false && isset($_POST['deleteComment']) == false) {
            $this->listRecentStatuses($user);
            $p = new ProfileModel($this->registry, $user);
            $this->username = $p->getUsername();
        }
    }

    private function listRecentStatuses($user)
    {
        $profile = new ProfileModel($this->registry, $user);
        $profile->toTags('p_');
        $this->registry->getObject('template')->getPage()->addTag('referer', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));
        // post status / public message box
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
            if (isset($_POST) && count($_POST) > 0) {
                $this->addStatus($user);
            }
            $loggedInUser = $this->registry->getObject('authenticate')->getUser()->getUserID();
            if ($loggedInUser == $user) {
                $this->registry->getObject('template')->addTemplateBit('status_update', 'profile_status_update.php');
            } else {
                require_once('relation.php');
                $relationships = new RelationsGet($this->registry);
                $connections = $relationships->getNetwork($user, false);
                if (in_array($loggedInUser, $connections)) {
                    $this->registry->getObject('template')->addTemplateBit('status_update', 'profile_status_post.php');
                } else {
                    $this->registry->getObject('template')->getPage()->addTag('status_update', '');
                }
            }
        } else {
            $this->registry->getObject('template')->getPage()->addTag('status_update', '');
        }
        $this->registry->getObject('template')->buildFromTemplate('header.php', 'profile_status_list.php', 'footer.php');
        $updates = array();
        $ids = array();
        $status_ids = array();
        $sql = "SELECT t.type_reference, t.type_name, s.*,pa.username as poster_user, pa.name as poster_name, i.image, v.video_id, l.URL, l.description FROM status_types t, profile p, profile pa, statuses s LEFT JOIN statuses_images i ON s.ID=i.id LEFT JOIN statuses_videos v ON s.ID=v.id LEFT JOIN statuses_links l ON s.ID=l.id WHERE t.ID=s.type AND p.user_id=s.profile AND pa.user_id=s.poster AND p.user_id=" . $user . " ORDER BY s.ID DESC LIMIT 20";
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) {
            while ($row = $this->registry->getObject('db')->getRows()) {
                $updates[] = $row;
                $ids[$row['ID']] = $row;
                $status_ids[] = $row['ID'];
            }
        }
        require_once('streamController.php');
        $stream = new StreamController($this->registry, false);
        $stream->commentary($status_ids);
        $cache = $this->registry->getObject('db')->cacheData($updates);
        $this->registry->getObject('template')->getPage()->addTag('updates', array('DATA', $cache));
        foreach ($ids as $id => $data) {
            $this->registry->getObject('template')->addTemplateBit('update-' . $id, 'profile_update_' . $data['type_reference'] . '.php', $data);
        }
        //var_dump($this->registry->getObject('template')->getPage()->getTags());
        //var_dump($cache);
    }

    public function addStatus($user)
    {
        $loggedInUser = $this->registry->getObject('authenticate')->getUser()->getUserID();
        if ($loggedInUser == $user) {
            require_once('status.php');
            if (isset($_POST['status_type']) && $_POST['status_type'] != 'update') {
                if ($_POST['status_type'] == 'image') {
                    require_once('imagestatus.php');
                    $status = new Imagestatus($this->registry, 0, $this->username);
                    $status->processImage('image_file');
                } elseif ($_POST['status_type'] == 'video') {
                    require_once('videostatus.php');
                    $status = new Videostatus($this->registry, 0, $this->username);
                    $status->setVideoIdFromURL($_POST['video_url']);
                } elseif ($_POST['status_type'] == 'link') {
                    require_once('linkstatus.php');
                    $status = new Linkstatus($this->registry, 0);
                    $status->setURL($this->registry->getObject('db')->sanitizeData($_POST['link_url']));
                    $status->setDescription($this->registry->getObject('db')->sanitizeData($_POST['link_description']));
                }
            } else {
                $status = new Status($this->registry, 0);
            }

            $status->setProfile($user);
            $status->setPoster($loggedInUser);
            if (isset($_POST['status'])) {
                $status->setStatus($this->registry->getObject('db')->sanitizeData($_POST['status']));
            }
            $status->generateType();
            $status->save();
            // success message display
            $this->registry->getObject('template')->addTemplateBit('status_update_message', 'profile_status_update_confirm.php');
        } else {
            require_once('relation.php');
            $relationships = new RelationsGet($this->registry);
            $connections = $relationships->getNetwork($user, false);
            if (in_array($loggedInUser, $connections)) {
                require_once('status.php');
                if (isset($_POST['status_type']) && $_POST['status_type'] != 'update') {
                    if ($_POST['status_type'] == 'image') {
                        require_once('imagestatus.php');
                        $status = new Imagestatus($this->registry, 0, $this->username);
                        $status->processImage('image_file');
                    } elseif ($_POST['status_type'] == 'video') {
                        require_once('videostatus.php');
                        $status = new Videostatus($this->registry, 0, $this->username);
                        $status->setVideoIdFromURL($_POST['video_url']);
                    } elseif ($_POST['status_type'] == 'link') {
                        require_once('linkstatus.php');
                        $status = new Linkstatus($this->registry, 0);
                        $status->setURL($this->registry->getObject('db')->sanitizeData($_POST['link_url']));
                        $status->setDescription($this->registry->getObject('db')->sanitizeData($_POST['link_description']));
                    }
                } else {
                    $status = new Status($this->registry, 0);
                }
                $status->setProfile($user);
                $status->setPoster($loggedInUser);
                $status->setStatus($this->registry->getObject('db')->sanitizeData($_POST['status']));
                $status->generateType();
                $status->save();
                // success message display
                $this->registry->getObject('template')->addTemplateBit('status_update_message', 'profile_status_post_confirm.php');
            } else {
                // error message display
                $this->registry->getObject('template')->addTemplateBit('status_update_message', 'profile_status_error.php');
            }
        }
    }


    //Add a comment
    public function addComment($com, $pp, $cr)//, $time)
    {
        $comment = $this->registry->getObject('db')->sanitizeData($com);
        $this->registry->getObject('db')->insertRecords('comments', array('comment' => $comment, 'profile_post' => $pp, 'creator' => $cr, 'approved' => 1));
        $commentID = $this->registry->getObject('db')->lastInsertID();
        if (isset($commentID)) {
            $this->registry->redirectUser('profile/statuses/'/*.$id = The user id of the profile you were on*/, 'Comment Added', 'The comment has been added to the post.');
        } else {
            $this->registry->errorPage('Comment Error', 'There has been some error in adding your comment');
        }
    }

    //Delete a comment
    public function deleteComment($com, $pp, $user)
    {
        $comment = $this->registry->getObject('db')->sanitizeData($com);
        $sql = "SELECT * FROM `comments` WHERE `comment`='" . $comment . "' AND `profile_post`=" . $pp . " AND `creator`=" . $user;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) {
            $deleteCondition = "`comment`='" . $comment . "' AND `profile_post`=" . $pp . " AND `creator`=" . $user;
            $this->registry->getObject('db')->deleteRecords('comments', $deleteCondition, '');
            $this->registry->redirectUser($this->registry->getSetting('siteurl'), 'Comment Deleted', 'The comment has been deleted from the post.');
        } else {
            return false;
        }
    }
}

?>