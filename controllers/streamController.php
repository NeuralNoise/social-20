<?php

class StreamController
{
    private $registry;
    private $model; //Stream model object

    public function __construct(Registry $registry, $directCall = true)
    {
        $this->registry = $registry;
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true && $directCall == true) {
            $this->username = '';
            $urlBits = $this->registry->getObject('url')->getURLBits();
            if (isset($urlBits[1])) {
                if (isset($_POST['addComment']) && isset($_POST['usr_comment']) && $urlBits[1] == 'addcomment') {
                    //$this->registry->ajaxReply($_POST);
                    $this->addComment($_POST['usr_comment'], $urlBits[2], $this->registry->getObject('authenticate')->getUser()->getUserID());//, time());
                } elseif (isset($_POST['deleteComment']) && isset($_POST['del_comment']) && $urlBits[1] == 'deletecomment') {
                    //$this->registry->ajaxReply($_POST);
                    $this->deleteComment($_POST['del_comment'], $urlBits[2], $this->registry->getObject('authenticate')->getUser()->getUserID());
                } elseif ($urlBits[1] == 'addStatus' && isset($_POST['updatestatus'])) {
                    //$this->registry->ajaxReply(array_merge($_POST, $_FILES));
                    //require_once('profile.php');
                    if (!isset($_FILES)) {
                        $_FILES = array();
                    }
                    $this->addStatus(array_merge($_POST, $_FILES), $this->registry->getObject('authenticate')->getUser()->getUserID());
                } elseif ($urlBits[1] == 'deletestatus' && isset($_POST['deleteStatus'])) {
                    $this->deleteStatus($urlBits[2], $this->registry->getObject('authenticate')->getUser()->getUserID());
                } elseif ($urlBits[1] == 'rate' && isset($_POST['rate_type'])) {
                    $this->addRate($_POST, $this->registry->getObject('authenticate')->getUser()->getUserID());
                } elseif ($urlBits[1] == 'more') {
                    if(isset($urlBits[2])){
                        $this->generateStream($urlBits[2]);
                    }
                    else{$this->registry->errorPage('Check URL', 'Missing Offset value for streaming');}
                } else {
                    $this->generateStream();
                }
            } else {
                $this->generateStream();
            }
        } elseif ($this->registry->getObject('authenticate')->isLoggedIn() == false && $directCall == true) {
            $this->registry->errorPage('Login Needed', 'Please login to continue');
        } else {
            //directcall is false.
        }
    }

    public function generateStream($offset = 0)
    {
        if ($offset == 0) {
            $this->registry->getObject('template')->getPage()->addTag('referer', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));
            require_once('stream.php');
            $stream = new Stream($this->registry);
            $stream->buildStream($this->registry->getObject('authenticate')->getUser()->getUserID(), $offset);
            $statusTypes = $stream->getStatusType();
            if (!$stream->isEmpty()) {
                $this->registry->getObject('template')->buildFromTemplate('header.php', 'stream_main.php', 'footer.php');
                $streamdata = $stream->getStream();
                $IDs = $stream->getIDs();
                $cacheableIDs = array();
                foreach ($IDs as $id) {
                    $i = array();
                    $i['status_id'] = $id;
                    $cacheableIDs[] = $i;
                }
                $cache = $this->registry->getObject('db')->cacheData($cacheableIDs);
                $this->registry->getObject('template')->getPage()->addTag('stream', array('DATA', $cache));
                //var_dump($cacheableIDs);
                foreach ($streamdata as $data) {
                    $datatags = array();
                    foreach ($data as $tag => $value) {
                        $datatags['status' . $tag] = $value;
                    }
                    //var_dump($datatags);
                    // your own status updates
                    if ($data['profile'] == 0) {
                        // network updates
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-general.php', $datatags);
                    } elseif ($data['profile'] == $this->registry->getObject('authenticate')->getUser()->getUserID() && $data['poster'] == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                        $this->registry->getObject('template')->addTemplateBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-self.php', $datatags);
                    } elseif ($data['profile'] == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                        // updates to you
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-toSelf.php', $datatags);
                    } elseif ($data['poster'] == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                        // updates by you
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-fromSelf.php', $datatags);
                    } elseif ($data['poster'] == $data['profile']) {
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-user.php', $datatags);
                    } else {
                        // network updates
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '.php', $datatags);
                    }
                }
                // stream comments, likes and dislikes
                $status_ids = implode(',', $IDs);
                $start = array();
                foreach ($IDs as $id) {
                    $start[$id] = array();
                }
                // comments
                $this->generateComments($start, $status_ids);
                //rates
                $this->getRates('status', $IDs);
                //$this->getRates('comments', $IDs);
                $this->registry->getObject('template')->getPage()->addTag('offset', $offset + 20);
                //var_dump($this->registry->getObject('template')->getPage()->getBits());
                if($stream->noMoreStreams){$this->registry->ajaxReply(array('content' => '', 'status' => 'stream404'));}
            } else {
                $this->registry->getObject('template')->buildFromTemplate('header.php', 'stream_none.php', 'footer.php');
            }
        } else {
            //"View More" of Stream
            $this->registry->getObject('template')->getPage()->addTag('referer', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));
            require_once('stream.php');
            $stream = new Stream($this->registry);
            $stream->buildStream($this->registry->getObject('authenticate')->getUser()->getUserID(), $offset);
            $statusTypes = $stream->getStatusType();
            if (!$stream->isEmpty()) {
                $this->registry->getObject('template')->buildFromTemplate('stream_more.php');
                $streamdata = $stream->getStream();
                $IDs = $stream->getIDs();
                $cacheableIDs = array();
                foreach ($IDs as $id) {
                    $i = array();
                    $i['status_id'] = $id;
                    $cacheableIDs[] = $i;
                }
                $cache = $this->registry->getObject('db')->cacheData($cacheableIDs);
                $this->registry->getObject('template')->getPage()->addTag('stream', array('DATA', $cache));
                //var_dump($cacheableIDs);
                foreach ($streamdata as $data) {
                    $datatags = array();
                    foreach ($data as $tag => $value) {
                        $datatags['status' . $tag] = $value;
                    }
                    //var_dump($datatags);
                    // your own status updates
                    if ($data['profile'] == 0) {
                        // network updates
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-general.php', $datatags);
                    } elseif ($data['profile'] == $this->registry->getObject('authenticate')->getUser()->getUserID() && $data['poster'] == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                        $this->registry->getObject('template')->addTemplateBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-self.php', $datatags);
                    } elseif ($data['profile'] == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                        // updates to you
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-toSelf.php', $datatags);
                    } elseif ($data['poster'] == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                        // updates by you
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-fromSelf.php', $datatags);
                    } elseif ($data['poster'] == $data['profile']) {
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-user.php', $datatags);
                    } else {
                        // network updates
                        $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '.php', $datatags);
                    }
                }
                // stream comments, likes and dislikes
                $status_ids = implode(',', $IDs);
                $start = array();
                foreach ($IDs as $id) {
                    $start[$id] = array();
                }
                // comments
                $this->generateComments($start, $status_ids);
                //rates
                $this->getRates('status', $IDs);
                //$this->getRates('comments', $IDs);
                $this->registry->getObject('template')->getPage()->addTag('offset', $offset + 20);
                //var_dump($this->registry->getObject('template')->getPage()->getBits());
                $this->registry->getObject('template')->parseOutput();
                $this->ajaxReply(array('content' => $this->registry->getObject('template')->getPage()->getContentToPrint(), 'status' => ''));
            }
        }
    }

    //Comments
    public function generateComments($start, $status_ids)
    {
        $comments = $start;//(isset($this->start)) ? $this->start : array();
        $sql = "SELECT p.name as commenter, c.profile_post, c.comment, c.ID as comment_id, p.user_id FROM profile p, comments c WHERE p.user_id=c.creator AND c.approved=1 AND c.profile_post IN (" . $status_ids . ")";
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) {
            $IDs = array();
            while ($comment = $this->registry->getObject('db')->getRows()) {
                $comments[$comment['profile_post']][] = $comment;
                $IDs[] = $comment['comment_id'];
            }
            $this->getRates('comment', $IDs);
        }
        foreach ($comments as $status => $comments) {
            $cache = $this->registry->getObject('db')->cacheData($comments);
            $this->registry->getObject('template')->getPage()->addTag('comments-' . $status, array('DATA', $cache));
        }
    }

    //Let's Do Something New

    private function addBit($strm, $file, $tags)
    {
        //if($file == 'updates/image.php') {echo 'hi';}
        $this->registry->getObject('template')->addTemplateBit($strm, $file, $tags);
    }

    //Comments on statuses
    public function commentary($ids)
    {
        $status_ids = implode(",", $ids);
        $comments = array();
        $sql = "SELECT p.name as commenter, c.profile_post, c.comment, c.ID as comment_id, p.user_id FROM profile p, comments c WHERE p.user_id=c.creator AND c.approved=1 AND c.profile_post IN (0," . $status_ids . ")";
        //echo $sql;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) {
            $IDs = array();
            while ($comment = $this->registry->getObject('db')->getRows()) {
                $comments[$comment['profile_post']][] = $comment;
                $IDs[] = $comment['comment_id'];
            }
            $this->getRates('comment', $IDs);
            foreach ($ids as $id) {
                if (!isset($comments[$id])) {
                    $comments[$id] = '';//array('commenter'=>'', 'comment'=>'', 'profile_post'=>$id);
                }
            }
        }
        if (!empty($comments)) {
            foreach ($comments as $status => $comments) {
                $cache = $this->registry->getObject('db')->cacheData($comments);
                $this->registry->getObject('template')->getPage()->addTag('comments-' . $status, array('DATA', $cache));
            }
        } else {
            foreach ($ids as $id) {
                $this->registry->getObject('template')->getPage()->addTag('comments-' . $id, array('DATA', -1));
            }
        }
    }

    public function addComment($com, $pp, $cr)//, $time)
    {
        $comment = $this->registry->getObject('db')->sanitizeData($com);
        $this->registry->getObject('db')->insertRecords('comments', array('comment' => $comment, 'profile_post' => $pp, 'creator' => $cr, 'approved' => 1));
        $commentID = $this->registry->getObject('db')->lastInsertID();
        if (isset($commentID)) {
            //$this->registry->redirectUser('', 'Comment Added', 'The comment has been added to the post.');
            $this->registry->ajaxReply(array('status' => 'The Comment Has Been Added', 'content' => ''));
        } else {
            //$this->registry->errorPage('Comment Error', 'There has been some error in adding your comment');
            $this->registry->ajaxReply(array('status' => 'The Comment Could Not Be Added'));
        }
        /*$entry = "'".$comment."',".$pp.",".$cr.",".$time;
        $sql = "INSERT INTO `comments`(`ID`, `comment`, `profile_post`, `creator`, `created`, `approved`) VALUES (".$entry.")";
        $this->registry->getObject('db')->executeQuery($sql);
        if($this->registry->getObject('db')->numRows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }*/
    }

    public function deleteComment($com, $pp, $user)
    {
        $comment = $this->registry->getObject('db')->sanitizeData($com);
        $sql = "SELECT * FROM `comments` WHERE `ID`='" . $comment . "' AND `profile_post`=" . $pp . " AND `creator`=" . $user;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) {
            $deleteCondition = "`ID`='" . $comment . "' AND `profile_post`=" . $pp . " AND `creator`=" . $user;
            $this->registry->getObject('db')->deleteRecords('comments', $deleteCondition, '');
            $this->registry->ajaxReply(array('status' => 'The Comment Has Been Deleted', 'content' => ''));
            //$this->registry->redirectUser('', 'Comment Deleted', 'The comment has been deleted from the post.');
        } else {
            $this->registry->ajaxReply(array('status' => 'The Comment Could Not Be Deleted' . $sql, 'content' => ''));
        }
    }

    public function deleteStatus($id, $user)
    {
        $sql = "SELECT * FROM `statuses` WHERE `ID`=" . $id . " AND `poster`=" . $user;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) {
            $del = "`ID`=" . $id;
            $this->registry->getObject('db')->deleteRecords('statuses', $del, '');
            $del2 = "`profile_post`=" . $id;
            $this->registry->getObject('db')->deleteRecords('comments', $del2, '');
            $this->registry->ajaxReply(array('status' => 'Status Deleted', 'content' => $id));
        } else {
            $this->registry->ajaxReply(array('status' => 'Status Could Not Be Deleted', 'content' => ''));
        }
    }

    private function addStatus($array, $user)
    {
        $loggedIn = $this->registry->getObject('authenticate')->isLoggedIn();
        if ($loggedIn == true) {
            require_once('status.php');
            if (isset($_POST['status_type']) && $_POST['status_type'] != 'update') {
                if ($_POST['status_type'] == 'image') {
                    require_once('imagestatus.php');
                    $status = new Imagestatus($this->registry, 0, $user);
                    $status->processImage('image_file');
                } elseif ($_POST['status_type'] == 'video') {
                    require_once('videostatus.php');
                    $status = new Videostatus($this->registry, 0, $user);
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
            $status->setPoster($user);
            if (isset($_POST['status'])) {
                $status->setStatus($this->registry->getObject('db')->sanitizeData($_POST['status']));
            }
            $status->generateType();
            $status->save();
            $newAddID = $status->getID();
            //Status Wierdness Start
            $this->registry->getObject('template')->getPage()->addTag('referer', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));
            require_once('stream.php');
            $stream = new Stream($this->registry);
            $status = $stream->getStatusByID($newAddID);
            $statusTypes = $stream->getStatusType();
            if (!$stream->isEmpty())

                $this->registry->getObject('template')->buildFromTemplate('stream_more.php');
            $streamdata = $stream->getStream();
            $IDs = $stream->getIDs();
            $cacheableIDs = array();
            foreach ($IDs as $id) {
                $i = array();
                $i['status_id'] = $id;
                $cacheableIDs[] = $i;
            }
            $cache = $this->registry->getObject('db')->cacheData($cacheableIDs);
            $this->registry->getObject('template')->getPage()->addTag('stream', array('DATA', $cache));
            //var_dump($cacheableIDs);
            foreach ($streamdata as $data) {
                $datatags = array();
                foreach ($data as $tag => $value) {
                    $datatags['status' . $tag] = $value;
                }
                //var_dump($datatags);
                // your own status updates
                if ($data['profile'] == 0) {
                    // network updates
                    $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-general.php', $datatags);
                } elseif ($data['profile'] == $this->registry->getObject('authenticate')->getUser()->getUserID() && $data['poster'] == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                    $this->registry->getObject('template')->addTemplateBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-self.php', $datatags);
                } elseif ($data['profile'] == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                    // updates to you
                    $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-toSelf.php', $datatags);
                } elseif ($data['poster'] == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                    // updates by you
                    $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-fromSelf.php', $datatags);
                } elseif ($data['poster'] == $data['profile']) {
                    $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '-user.php', $datatags);
                } else {
                    // network updates
                    $this->addBit('stream-' . $data['ID'], 'updates/' . $data['type_reference'] . '.php', $datatags);
                }
            }
            // stream comments, likes and dislikes
            $status_ids = implode(',', $IDs);
            $start = array();
            foreach ($IDs as $id) {
                $start[$id] = array();
            }
            // comments
            $this->generateComments($start, $status_ids);
            //rates
            $this->getRates('status', $IDs);
            //$this->getRates('comments', $IDs);
            $this->registry->getObject('template')->getPage()->addTag('offset', 20); //$offset +
            $this->registry->getObject('template')->parseOutput();
            $this->registry->ajaxReply(array('content' => $this->registry->getObject('template')->getPage()->getContentToPrint(), 'status' => 'Status Added'));
            //$this->registry->ajaxReply(array('content' => '<script>$(document).ready(function(){window.location.reload();})</script>', 'status' => 'Status Added'));
            //Status Wierdness End
            // success message display
            //$this->registry->ajaxReply( array('status'=>'Status Added', 'content'=>'') );
            //$this->registry->getObject('template')->addTemplateBit( 'status_update_message', 'profile_status_update_confirm.php' );
        } else {
            //$this->registry->ajaxReply( array('status'=>'Access Denied', 'content'=>'') );
            $this->registry->errorPage('Access Denied', 'Login to continue');
        }
    }

    private function getRates($type, $status_ids)
    {
        $return = array();
        foreach ($status_ids as $id) {
            $sql = "SELECT * FROM `rates` WHERE `status_type`='" . $type . "' AND `status_id`=" . $id;
            $n = 0;
            $r = 0; //Number of rates and total rate
            $this->registry->getObject('db')->executeQuery($sql);
            if ($this->registry->getObject('db')->numRows() > 0) {
                while ($row = $this->registry->getObject('db')->getRows()) {
                    $n++;
                    $r += $row['value'];
                }
                $av = $r / $n;
                $av = strval($av);
                $r1 = str_replace('.', '-', $av, $count);
                if ($count > 0) {
                    $av = $r1;
                }
                //if($n==1)
                //{
                $this->registry->getObject('template')->getPage()->addTag('rate_' . $type . '_' . $id, $av . "_" . $n . "_" . $id . "_" . $type);//'Rated '.$av.' by '.$n.' member');
                //}
                //else
                //{
                //$this->registry->getObject('template')->getPage()->addTag('rate_'.$type.'_'.$id, $av."_".$n."_".$id."_".$type);//'Rated '.$av.' by '.$n.' members');
                //}
                $return[] = array('rate' => $r, 'number' => $n, 'type' => $type, 'id' => $id);
            } else {
                $this->registry->getObject('template')->getPage()->addTag('rate_' . $type . '_' . $id, '0_0_' . $id . "_" . $type);
                $return[] = array('rate' => 0, 'number' => 0, 'type' => $type, 'id' => $id);
            }
        }
        return $return;
    }

    private function addRate($post, $user)
    {
        $sql = "SELECT * FROM `rates` WHERE `status_type`='" . $post['rate_type'] . "' AND `status_id`=" . $post['rate_id'] . " AND `rater`=" . $user;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) {
            $this->registry->ajaxReply(array('status' => 'You have already rated', 'content' => ''));
        } else {
            $rates = $this->getRates($post['rate_type'], array($post['rate_id']));
            $r = $post['rate_value'];
            //foreach($rates as $val)
            //{
            //$r+=$val['rate'];
            //}
            //$this->registry->ajaxReply(array('status'=>var_dump($r)));
            $this->registry->getObject('db')->insertRecords('rates', array('value' => $r, 'status_type' => $post['rate_type'], 'status_id' => $post['rate_id'], 'rater' => $user));
            if ($this->registry->getObject('db')->lastInsertID() != null) {
                $this->registry->ajaxReply(array('status' => 'Rate Added', 'content' => ''));
            } else {
                $this->registry->ajaxReply(array('status' => 'Rate Could Not Be Added', 'content' => ''));
            }
        }
        //$this->registry->ajaxReply(array('status'=>$post['rate_value'].' on '.$post['rate_type'].' no. '.$post['rate_id']));
    }
}

?>