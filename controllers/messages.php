<?php

class Messages //The message controller
{
    private $registry;
    private $empty = true;
    private $IDs = array();

    public function __construct(Registry $registry, $directCall = true)
    {
        $this->registry = $registry;
        $urlBits = $this->registry->getObject('url')->getURLBits();
        if (isset($urlBits[1]) && $directCall == true) {
            switch ($urlBits[1]) {
                case 'view':
                    if ($this->registry->getObject('authenticate')->isLoggedIn() == true && isset($urlBits[1])) {
                        $this->listMessagesFromSender($urlBits[1]);
                    } elseif ($this->registry->getObject('authenticate')->isLoggedIn() == true && $directCall == true) {
                        $this->listMessages();
                    } else {
                        $this->registry->errorPage('Login Needed', 'You are trying to access a page that you are not authorised to view.');
                    }
                    break;
                case 'add':
                    if (isset($urlBits[2])) {
                        $this->addMessage($urlBits[2]);
                    } else {
                        $this->registry->errorPage('Error', 'You are trying to access a page that may not be existing. Please check the URL again.');
                    }
                    break;
                case 'delete':
                    if (isset($urlBits[2])) {
                        $this->deleteMessage($urlBits[2]);
                    } else {
                        $this->registry->errorPage('Error', 'You are trying to access a page that may not be existing. Please check the URL again.');
                    }
                    break;
                default:
                    break;
            }
        } else {
            if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
                $this->listMessages();
            } else {
                $this->registry->errorPage('Login Needed', 'You are trying to access a page that you are not authorised to view.');
            }
        }
    }

    public function listMessages()
    {
        $user = $this->registry > getObject('authenticate')->getUser()->getUserID();
        $data = array();
        $sql = "SELECT * FROM `messages` WHERE `sender`=" . $user . " OR `recipient`=" . $user;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) //ID, sender, recipient, sent, read, subject, message
        {
            $this->empty = false;
            while ($fields = $this->registry->getObject('db')->getRows) {
                $this->IDs = $fields['ID'];
                $data[] = $fields;
            }
            $this->registry->getObject('template')->buildFromTemplate('header.php', 'messages_main.php', 'footer.php');
            $cacheableIDs = array();
            foreach ($this->IDs as $id) {
                $i = array();
                $i['message_id'] = $id;
                $cacheableIDs[] = $i;
            }
            $cache = $this->registry->getObject('db')->cacheData($cacheableIDs);
            $this->registry->getObject('template')->getPage()->addTag('message', array('DATA', $cache));
            $daTags = array();
            $type = $this->getType($d['type']);
            foreach ($data as $d) {
                foreach ($d as $f => $v) {
                    $daTags['message_' . $f] = $v;
                }
                if ($d['sender'] == $this->registry->getObject('authenticate')->getUser()->getUserID() && $d['read'] == 0 && $d['reply'] != 0) {
                    $this->listReply($d, 'messages/' . $type . '-replySelf-unread.php', $daTags);
                } elseif ($d['sender'] == $this->registry->getObject('authenticate')->getUser()->getUserID() && $d['read'] == 1 && $d['reply'] != 0) {
                    $this->listReply($d, 'messages/' . $type . '-replySelf-read.php', $daTags);
                } elseif ($d['read'] == 0 && $d['reply'] != 0) {
                    $this->listReply($d, 'messages/' . $type . '-reply-unread.php', $daTags);
                } elseif ($d['read'] == 1 && $d['reply'] != 0) {
                    $this->listReply($d, 'messages/' . $type . '-reply-read.php', $daTags);
                } elseif ($d['sender'] == $this->registry->getObject('authenticate')->getUser()->getUserID() && $d['read'] == 0) {
                    $this->registry->getObject('template')->addTemplateBit('message-' . $d['ID'], 'messages/' . $type . '-fromSelf-unread.php', $daTags);
                } elseif ($d['sender'] == $this->registry->getObject('authenticate')->getUser()->getUserID() && $d['read'] == 1) {
                    $this->registry->getObject('template')->addTemplateBit('message-' . $d['ID'], 'messages/' . $type . '-fromSelf-read.php', $daTags);
                } elseif ($d['read'] == 0) {
                    $this->registry->getObject('template')->addTemplateBit('message-' . $d['ID'], 'messages/' . $type . '-unread.php', $daTags);
                } else {
                    $daTags['message_replyFrom'] = '';
                    $this->registry->getObject('template')->addTemplateBit('message-' . $d['ID'], 'messages/' . $type . '-read.php', $daTags);
                }
            }
        }
    }

    public function listReply($sqlData, $file, $tags)
    {
        $tags['message_replyFrom'] = $sqlData['reply'];
        foreach ($tags as $field => $value) {
            $cache = $this->registry->getObject('db')->cacheData($tags);
            $this->registry->getObject('template')->getPage()->addTag('replies-' . $field, array('SQL', $cache));
        }
        $this->registry->getObject('template')->addTemplateBit('message-' . $sqlData['ID'], $file, $tags);
    }

    public function listMessagesFromSender($id)
    {
    }

    public function addMessage() //Also have a support for reply
    {
    }

    public function deleteMessage()
    {
    }

    public function getType($index)
    {
        $t = '';
        switch ($index) {
            case 1:
                $t = 'text';
                break;
            case 2:
                $t = 'image';
                break;
            case 3:
                $t = 'video';
                break;
            default:
                $t = 'text';
        }
        return $t;
    }
}

?>