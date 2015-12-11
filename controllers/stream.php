<?php

class Stream
{
    private $types = array(); //Types of streams

    private $empty = true; //If the stream is empty

    private $stream = array(); //The stream

    private $IDs = array(); //IDs of statuses in the stream

    private $statusType = array();

    private $registry; //Do I need to tell you?

    public $noMoreStreams = false;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function buildStream($user, $offset = 0)
    {
        $network = array(); // prepare an array
        require_once('relation.php'); // use the relationships model to get relationships
        $relation = new RelationsGet($this->registry);
        $network = $relation->getNetwork($user);
        //$subscribers = $relation->getSubscribers( $user );
        //$network = array_merge($network, $subscribers);
        // Add a zero element; so if network is empty the IN part of the query won't fail
        if (count($network) == 0) {
            $network[] = 0;
        }
        $network = implode(',', $network);
        // query the statuses table
        //$sql = "SELECT t.type_reference, t.type_name, s.*, UNIX_TIMESTAMP(s.posted) as timestamp, p.name as poster_name, r.name as profile_name FROM statuses s, status_types t, profile p, profile r WHERE t.ID=s.type AND p.user_id=s.poster AND r.user_id=s.profile AND ( p.user_id=".$user." OR r.user_id=".$user." OR ( p.user_id IN (".$network.") AND r.user_id IN (".$network.") ) ) ORDER BY s.ID DESC LIMIT ".$offset.", 25";
        $sql = "SELECT t.type_reference, t.type_name, s.*, UNIX_TIMESTAMP(s.posted) as timestamp, p.username as poster_user, p.name as poster_name, r.name as profile_name, i.image, v.video_id, l.URL, s.ID FROM status_types t, profile p, profile r, statuses s LEFT JOIN statuses_images i ON s.ID=i.id LEFT JOIN statuses_videos v ON s.ID=v.id LEFT JOIN statuses_links l ON s.ID=l.id WHERE t.ID=s.type AND p.user_id=s.poster AND (r.user_id=s.profile OR s.profile=0) AND ( p.user_id=" . $user . " OR r.user_id=" . $user . " OR ( p.user_id IN (" . $network . ") AND r.user_id IN (" . $network . ") ) ) ORDER BY s.ID DESC LIMIT " . $offset . ", 20";
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) {
            $this->empty = false;
            // iterate through the statuses, adding the ID to the IDs array, making the time friendly, and saving the stream
            //echo $sql;
            while ($row = $this->registry->getObject('db')->getRows()) {
                if (!in_array($row['ID'], $this->IDs)) {
                    $row['friendly_time'] = $this->generateFriendlyTime($row['timestamp']);
                    $this->IDs[] = $row['ID'];
                    $this->stream[] = $row;
                }
            }
            //var_dump($this->IDs);var_dump($this->stream);
        }
        else{
            //$this->registry->errorPage('No more Streams', 'No more streams were found at this offset');
            $this->noMoreStreams = true;
        }
    }

    public function getStatusType()
    {
        $sql = "SELECT * FROM status_types";
        $this->registry->getObject('db')->executeQuery($sql);
        $this->statusType[0] = '';
        while ($data = $this->registry->getObject('db')->getRows()) {
            $this->statusType[] = $data['type_reference'];
        }
        return $this->statusType;
    }

    public function getStream()
    {
        return $this->stream;
    }

    public function getIDs()
    {
        return $this->IDs;
    }

    public function isEmpty()
    {
        return $this->empty;
    }

    public function getStatusByID($id)
    {
        $network = array(); // prepare an array
        $user = $this->registry->getObject('authenticate')->getUser()->getUserID();
        // query the statuses table
        $sql = "SELECT t.type_reference, t.type_name, s.*, UNIX_TIMESTAMP(s.posted) as timestamp, p.username as poster_user, p.name as poster_name, r.name as profile_name, i.image, v.video_id, l.URL, s.ID FROM status_types t, profile p, profile r, statuses s LEFT JOIN statuses_images i ON s.ID=i.id LEFT JOIN statuses_videos v ON s.ID=v.id LEFT JOIN statuses_links l ON s.ID=l.id WHERE t.ID=s.type AND p.user_id=s.poster AND s.ID=" . $id;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() > 0) {
            $this->empty = false;
            // iterate through the statuses, adding the ID to the IDs array, making the time friendly, and saving the stream
            //echo $sql;
            while ($row = $this->registry->getObject('db')->getRows()) {
                if (!in_array($row['ID'], $this->IDs)) {
                    $row['friendly_time'] = $this->generateFriendlyTime($row['timestamp']);
                    $this->IDs[] = $row['ID'];
                    $this->stream[] = $row;
                }
            }
            //var_dump($this->IDs);var_dump($this->stream);
        }
    }

    private function generateFriendlyTime($time)
    {
        $current_time = time();
        if ($current_time < ($time + 60)) {
            // the update was in the past minute
            return "less than a minute ago";
        } elseif ($current_time < ($time + 120)) {
            // it was less than 2 minutes ago, more than 1, but we don't want to say 1 minuteS ago do we?
            return "just over a minute ago";
        } elseif ($current_time < ($time + (60 * 60))) {
            // it was less than 60 minutes ago: so say X minutes ago
            return round(($current_time - $time) / 60) . " minutes ago";
        } elseif ($current_time < ($time + (60 * 120))) {
            // it was more than 1 hour ago, but less than two, again we dont want to say 1 hourS do we?
            return "just over an hour ago";
        } elseif ($current_time < ($time + (60 * 60 * 24))) {
            // it was in the last day: X hours
            return round(($current_time - $time) / (60 * 60)) . " hours ago";
        } else {
            // longer than a day ago: give up, and display the date / time
            return "on " . date('h:ia \o\n l \t\h\e jS \o\f M', $time);
        }
    }
}

?>