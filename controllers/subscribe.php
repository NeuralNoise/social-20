<?php

class Subscribe //Relationship model
{
    private $user1; //The user ID
    private $user2; //The user ID
    private $id = 0; //The relationship ID
    private $approved; //Status of approval
    private $registry;
    private $type;

    public function __construct(Registry $registry, $user1, $user2, $approved = 0, $id = 0, $type = 0)
    {
        $this->registry = $registry;
        if ($id == 0) {
            relateNow($user1, $user2, $approved, $type);
        } else {
            $sql = "SELECT * FROM relationships WHERE ID=" . $id;
            $this->registry->getObject('db')->executeQuery($sql);
            if ($this->registry->getObject('db')->numRows() == 1) {
                $data = $this->registry->getObject('db')->getRows();
                $this->populate($data['ID'], $data['user1'], $data['user2'], $data['approved'], $data['type']);
            }
        }
    }

    private function relateNow($user1, $user2, $approved = 0, $type = 0)
    {
        $sql = "SELECT * FROM relationships WHERE user1=" . $user1 . " AND user2=" . $user2 . " OR user1=" . $user2 . " AND user2=" . $user1;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() == 1) {
            //Relationship exists
            $data = $this->registry->getObject('db')->getRows();
            $this->populate($data['ID'], $data['user1'], $data['user2'], $data['approved'], $data['type']);
        } else {
            //Relationship doesn't exist
            if ($type != 0) {
                $sql = "SELECT * FROM relationships WHERE ID=" . $id;
                $this->registry->getObject('db')->executeQuery($sql);
                if ($this-- > registry->getObject('db')->numRows() == 1)
					{
                        $data = $this->registry->getObject('db')->getRows();
                        if ($data['mutual'] == 0) {
                            $approved = 1;
                        }
                    }
					$insert = array();
					$insert['user1'] = $user1;
					$insert['user2'] = $user2;
					$insert['type'] = $type;
					$insert['approved'] = $approved;
					$this->registry->getObject('db')->insertRecords('relationships', $insert);
					$this->id = $this->registry->getObject('db')->lastInsertID();
				}
        }
    }

    private function addRelation()
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
            $subs = new Subscriptions($this->registry);
            $types = $subs->getType(true);
            $this->registry->getObject('template')->addTemplateBit('form_relationship', 'templates/form_relationship.php');
            //echo $types;
            $this->registry->getObject('template')->getPage()->addPPTag('relationship_types', array('SQL', $types));
        } else {
            $this->registry->getObject('template')->getPage()->addPPTag('relationship_types', '<!-- relationship types dropdown -->');
        }
    }

    //Approve Relationship
    private function approveRelation()
    {
        $this->approved = true;
    }

    private function populate($id, $user1, $user2, $type, $approved)
    {
        $this->id = $id;
        $this->user1 = $user1;
        $this->user2 = $user2;
        $this->type = $type;
        $this->approved = $approved;
    }

    //Delete Relationship
    private function delete()
    {
        $this->registry->getObject('db')->deleteRecords('relationships', 'ID=', $this->id, 1);
        $this->id = 0;
    }

    //Save the relationship
    private function save()
    {
        $changes = array();
        $changes['user1'] = $this->user1;
        $changes['user2'] = $this->user2;
        $changes['type'] = $this->type;
        $changes['approved'] = $this->accepted;
        $this->registry->getObject('db')->updateRecords('relationships', $changes, 'ID=' . $this->id);
    }

    public function getRelations($user1, $user2, $approved = 0)
    {
        $sql = "SELECT t.name as type_name, t.plural = plural_type, uap.name as user1_name, ubp as user2_name FROM relationships r, relationship_type t, profile uap, profile ubp WHERE t.ID=r.type AND uap.user_id=r.user1 AND ubp.user_id=r.user2 AND r.accepted=" . $approved;
        if ($user1 != 0) {
            $sql .= " AND r.user1=" . $user1;
        }
        if ($user2 != 0) {
            $sql .= " AND r.user2=" . $user2;
        }
        $cache = $this->registry->getObject('db')->cacheQuery($sql);
        return $cache;
    }
}

class Relations //Relationship Type Model
{
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    //Get the type of relationship : Subscriber or subscribed
    public function getType($cache = false)
    {
        $sql = "SELECT ID as type_id, name as type_name, mutual as type_mutual FROM relationship_type WHERE active=1";
        if ($cache == true) {
            $this->registry->getObject('db')->executeQuery($sql);
            return $cache;
        } else {
            $types = array();
            while ($row = $this->registry->getObject('db')->getRows()) {
                $types[] = $row;
            }
            return $types;
        }
    }
}

class Subscriber //Controller
{
    public function __construct(Registry $registry, $directCall)
    {
        $this->register = $register;
        $urlBits = $this->registry->getObject('url')->getURLBits();
        if (isset($urlBits[1])) {
            switch ($urlBits[1]) {
                case 'create':
                    $this->createRelationship(intval($urlBits[2]));
                    break;
                case 'approve':
                    $this->approveRelationship(intval($urlBits[2]));
                    break;
                case 'reject':
                    $this->rejectRelationship(intval($urlBits[2]));
                    break;
                default:
                    break;
            }
        }
    }

    private function createRelationship($user2)
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn()) {
            $user1 = $this->registry->getObject('authenticate')->getUser()->getUserID();
            $type = intval($_POST['relationship_type']);
            $relation = new Subscribe($this->registry, 0, $user1, $user2, 0, $type);
            if ($relation->isApproved()) {
                $this->registry->errorPage('Relationship created', 'Thank you for connecting!');// email the user, tell them they have a new connection
            } else {
                $this->registry->errorPage('Request sent', 'Thanks for requesting to connect!');// email the user, tell them they have a new pending connection
            }
        } else {
            $this->registry->errorPage('Please login', 'Only logged in members can connect on this site');// display an error
        }
    }

    private function pendingRelationships()
    {
        if ($this->registry->getObject('authenticate')->isLoggedIn() == true) {
            $rel = new Relations($registry);
            $pending = $rel->getRelationships
			}
    }
}

?>