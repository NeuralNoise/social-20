<?php

class RelateController //Relationships Controller
{
    //{siteurl}/relationships/create/{userid}/{relationtype}
    public function __construct(Registry $registry, $directCall = true)
    {
        $this->registry = $registry;
        $urlBits = $this->registry->getObject('url')->getURLBits();
        //$last = count($urlBits) - 1;
        if ($directCall == false) {
        } elseif ($urlBits[$last - 1] != 'create' || $urlBits[$last - 1] != 'approve' || $urlBits[$last - 1] != 'reject' || $urlBits[$last - 1] != 'pending') {
            $this->myRelationships();
        } else {
            switch ($urlBits[$last - 1]) {
                case 'create':
                    $this->createRelationship(intval($urlBits[$last]));
                    break;
                case 'approve':
                    $this->approveRelationship(intval($urlBits[$last]));
                    break;
                case 'reject':
                    $this->rejectRelationship(intval($urlBits[$last]));
                    break;
                case 'pending':
                    $this->pendingRelationship();
                    break;
                default:
                    $this->myRelationships();
                    break;
            }
        }
        /*
        if( isset( $urlBits[1] ) )
        {
            switch( $urlBits[1] )
            {
                case 'create':
                    $this->createRelationship( intval( $urlBits[2] ) );
                    break;
                case 'approve':
                    $this->approveRelationship( intval( $urlBits[2] ) );
                    break;
                case 'reject':
                    $this->rejectRelationship( intval( $urlBits[2] ) );
                    break;
                case 'pending':
                    $this->pendingRelationship();
                    break;
                default:
                    $this->myRelationships();
                    break;
            }
        }
        else
        {
            $this->myRelationships();
        }*/
    }

    //Create a relationship
    public function createRelationship($userb)
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn()) {
            $usera = $this->registry->getObject('authenticate')->getUser()->getUserID();
            $type = intval($_POST['relationship_type']);
            $relation = new Relation($this->registry, $usera, $userb, 0, 0, $type);
            if ($relation->isApproved()) {
                $this->registry->errorPage('Relationship created', 'Thank you for connecting!');// email the user, tell them they have a new connection
            } else {
                $this->registry->errorPage('Request sent', 'Thanks for requesting to connect!');// email the user, tell them they have a new pending connection
            }
        } else {
            $this->registry->errorPage('Please login', 'Only logged in members can connect on this site');// display an error
        }
    }


    //Approve a relationship
    private function approveRelationship($r)
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn()) {
            $rel = new Relation($this->registry, $r, 0, 0, 0, 0);
            if ($rel->getUserB() == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                $rel->approveRelationship();
                $rel->save();
                $this->registry->errorPage('Relationship approved', 'Thank you for approving the relationship');
            } else {
                $this->registry->errorPage('Invalid request', 'You are not authorized to approve that request');
            }
        } else {
            $this->registry->errorPage('Please login', 'Please login to approve this connection');
        }
    }

    //Get the pending relationships
    private function pendingRelationship()
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
            $rel = new RelationsGet($registry);
            $pending = $rel->getRelationships(0, $this->getObject('authenticate')->getUser()->getUserID(), 0);
            $this->registry->getObject('template')->buildFromTemplate('header.php', 'pending.php', 'footer.php');
            //echo $pending;
            $this->registry->getObject('template')->getPage()->addTag('pending', array('SQL', $pending));
        } else {
            $this->registry->errorPage('Required Login', 'Please Login to manage your pending connections');
        }
    }

    //Reject a relationship
    private function rejectRelationship($r)
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
            $rel = new Relation($this->registry, $r, 0, 0, 0, 0);
            if ($rel->getUserB() == $this->registry->getObject('authenticate')->getUser()->getUserID()) {
                $rel->approveRelationship();
                $rel->delete();
                $this->registry->errorPage('Relationship deleted', 'We have rejected the relationship.');
            } else {
                $this->registry->errorPage('Invalid request', 'You are not authorized to reject that request');
            }
        } else {
            $this->registry->errorPage('Please login', 'Please login to reject this connection');
        }
    }

    //Get a list of my relationships
    private function myRelationships()
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
            require_once('relation.php');
            $rel = new RelationsGet($this->registry);
            $related = $rel->getByUser($this->registry->getObject('authenticate')->getUser()->getUserID());
            $this->registry->getObject('template')->buildFromTemplate('header.php', 'relations_mine.php', 'footer.php');
            $this->registry->getObject('template')->getPage()->addTag('connections', array('SQL', $related));
        } else {
            $this->registry->errorPage('Please login', 'You need to be a logged in user to see your friends');
        }
    }

    private function viewAll($user)
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
            $rel = new RelationsGet($this->registry);
            $all = $rel->getByUser($user, false, 0);
            $this->registry->getObject('template')->buildFromTemplate('header.php', 'relations_all.php', 'footer.php');
            //echo $all;
            $this->registry->getObject('template')->getPage()->addTag('all', array('SQL', $all));
            $profile = new Profile($this->registry, $user);
            $name = $profile->getName();
            $this->registry->getObject('template')->getPage()->addTag('connecting_name', $name);
        } else {
            $this->registry->errorPage('Error', 'Please Login to view connections');
        }
    }
}

?>