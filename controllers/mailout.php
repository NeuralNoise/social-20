<?php

class Mailout
{
    //Not constructor, because need new one for every mail sent
    public function startFresh()
    {
        $this->lock = false;
        $this->error = 'Message not sent because ';
        $this->message = '';
    }

    //Set the recipient
    public function setTo($to)
    {
        /*$e = urldecode($to);
        if(preg_match("\\r", $e) || preg_match("\\n", $e))
        {
            //bad header injections
            $this->lock();
            $this->error = "Recipient Email header injection attempt: Probably caused by spam attempts";
            return false;
        }
        else*/
        if (!preg_match("/^[_a-zA-Z0-9-]+(\\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\\.[a-zA-Z0-9-]+)*(\\.[a-zA-Z]{2,6})$/", $to)) {
            //bad or invalid email
            $this->lock();
            $this->error = "Please enter a valid email ID";
            return false;
        } else {
            $this->to = $to;
            return true;
        }
    }

    //Set the sender
    public function setSender($from)
    {
        if ($from == '') {
            include('config.php');
            // No email passed - use something from the registry
            $this->headers = 'From: ' . $configs['admin_email'];
            $this->from = $configs['admin_email'];
            return true;
        } else {
            if (strpos((urldecode($from)), "\r") === true || strpos((urldecode($from)), "\n") === true) {
                // bad - header injections
                $this->lock();
                $this->error .= ' Email header injection attempt, probably caused by spam attempts';
                return false;
            } elseif (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})^", $from)) {
                // bad - invalid email
                $this->lock();
                $this->error .= ' Email address not valid';
                return false;
            } else {
                //good - let's do it!
                $this->headers = 'From: ' . $from;
                $this->from = $from;
                return true;
            }
        }
    }

    public function setFromName($name)
    {
        $this->fromName = $name;
    }

    //Just set the sender
    public function setSenderIgnoringRules($email)
    {
        $this->headers = 'From: ' . $email;
    }

    // Appends header fields to the email header - note setSender must be called first
    public function appendHeader($toAppend)
    {
        $this->headers .= "\r\n" . $toAppend;
    }

    // Locks the email to prevent sending
    public function lock()
    {
        $this->lock = true;
    }

    //Set the subject
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    //Set the mailing method
    public function setMethod($method)
    {
        $this->method = $method;
    }

    //Build email from templates
    public function buildFromTemplate()
    {
        $bits = func_get_args();
        $contents = '';
        foreach ($bits as $bit) {
            if (strpos($bit, 'templates/') === false) {
                $bit = 'templates/' . $bit;
            }
            if (file_exists($bit) == true) {
                $contents .= file_get_contents($bit);
            }
        }
        $this->message = $contents;
    }

    //Build email from text (instead of templates)
    public function buildFromText($msg)
    {
        $this->message .= $msg;
    }

    //Replace some tags
    public function replaceTags($tag)
    {
        //Go through all passed tags
        if (sizeof($tag) > 0) {
            foreach ($tag as $field => $value) {
                //Check if $value is array
                if (!is_array($value)) {
                    $conNew = str_replace('{' . $field . '}', $value, $this->message);
                    $this->message = $conNew;
                }
            }
        }
    }

    //Send the mail finally!
    public function send()
    {
        switch ($this->method) {
            case 'sendMail':
                return $this->sendMail();
                break;
            case 'smtp':
                return $this->smtpSend();
                break;
            default:
                return $this->sendMail();
        }
    }

    //Send with SendMail
    public function sendMail()
    {
        if ($this->lock == true) {
            return false;
        } else {
            if (!mail($this->to, $this->subject, $this->message, $this->headers)) //Where do subject and headers come from?
            {
                $this->error .= ' error while sending via PHP\'s mail function';
                return false;
            } else {
                return true;
            }
        }
    }

    //Send with smtp
    public function smtpSend()
    {

    }
}

?>