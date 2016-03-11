<?php

class Relation //Relationship Manager model
{
    private $usera; //The user ID
    private $userb; //The user ID
    private $id = 0; //The relationship ID
    private $approved; //Status of approval
    private $registry;
    private $type;

    public function __construct(Registry $registry, $usera, $userb, $approved = 0, $id = 0, $type = 0)
    {
        $this->registry = $registry;
        if ($id == 0) {
            relateNow($usera, $userb, $approved, $type);
        } else {
            $sql = "SELECT * FROM relationships WHERE ID=" . $id;
            $this->registry->getObject('db')->executeQuery($sql);
            if ($this->registry->getObject('db')->numRows() == 1) {
                $data = $this->registry->getObject('db')->getRows();
                $this->populate($data['ID'], $data['usera'], $data['userb'], $data['approved'], $data['type']);
            }
        }
    }

    private function relateNow($usera, $userb, $approved = 0, $type = 0)
    {
        $sql = "SELECT * FROM `relationships` WHERE `usera`='" . $usera . "' AND `userb`='" . $userb . "' OR `usera`='" . $userb . "' AND `userb`=" . $usera;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->numRows() == 1) {
            //Relationship exists
            $data = $this->registry->getObject('db')->getRows();
            $this->populate($data['ID'], $data['usera'], $data['userb'], $data['approved'], $data['type']);
        } else {
            //Relationship doesn't exist
            if ($type != 0) {
                $sql = "SELECT * FROM relationships WHERE ID=" . $id;
                $this->registry->getObject('db')->executeQuery($sql);
                if ($this->registry->getObject('db')->numRows() == 1) {
                    $data = $this->registry->getObject('db')->getRows();
                    if ($data['mutual'] == 0) {
                        $approved = 1;
                    }
                }
                $insert = array();
                $insert['usera'] = $usera;
                $insert['userb'] = $userb;
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

    private function populate($id, $usera, $userb, $type, $approved)
    {
        $this->id = $id;
        $this->usera = $usera;
        $this->userb = $userb;
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
        $changes['usera'] = $this->usera;
        $changes['userb'] = $this->userb;
        $changes['type'] = $this->type;
        $changes['approved'] = $this->accepted;
        $this->registry->getObject('db')->updateRecords('relationships', $changes, 'ID=' . $this->id);
    }

    private function getUserB()
    {
        return $this->userb;
    }
}

class RelationsGet //Get Relationship Model
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

    //Get if relationship exists
    public function getRelations($usera, $userb, $approved = 0)
    {
        $sql = "SELECT t.name as type_name, t.plural_name as type_plural_name, uap.name as usera_name, ubp.name as userb_name, r.ID FROM relationships r, relationship_types t, profile uap, profile ubp WHERE t.ID=r.type AND uap.user_id=r.usera AND ubp.user_id=r.userb AND r.accepted=" . $approved;
        if ($usera != 0) {
            $sql .= " AND r.usera=" . $usera;
        }
        if ($userb != 0) {
            $sql .= " AND r.userb=" . $userb;
        }
        $cache = $this->registry->getObject('db')->cacheQuery($sql);
        //echo $cache;
        return $cache;
    }

    //Get Relationship status by user
    public function getByUser($user, $obr = false, $limit = 0)
    {
        $sql = "SELECT t.plural_name, p.name as users_name, u.ID FROM users u, profile p, relationships r, relationship_types t WHERE t.ID=r.type AND r.accepted=1 AND (r.usera=" . $user . " OR r.userb=" . $user . ") AND (IF(r.usera=" . $user . ",u.ID=r.userb,u.ID=r.usera)) AND p.user_id=u.ID";
        if ($obr == true) //Ordering randomly
        {
            $sql .= " ORDER BY RAND() ";
        }
        if ($limit != 0) //limit the results
        {
            $sql .= " LIMIT " . $limit;
        }
        //echo $sql;
        $cache = $this->registry->getObject('db')->cacheQuery($sql);
        return $cache;
    }

    public function getNetwork($user)
    {
        $sql = "SELECT u.ID FROM users u, profile p, relationships r, relationship_types t WHERE t.ID=r.type AND r.accepted=1 AND (r.usera='" . $user . "' OR r.userb='" . $user . "') AND IF( r.usera='" . $user . "',u.ID=r.userb,u.ID=r.usera) AND p.user_id=u.ID";
        $this->registry->getObject('db')->executeQuery($sql);
        $network = array();
        if ($this->registry->getObject('db')->numRows() > 0) {
            while ($r = $this->registry->getObject('db')->getRows()) {
                $network[] = $r['ID'];
            }
        }
        return $network;
    }

    public function getSubscribers($user)//b subscribed to a, so b shouldn't be on network of a
    {
        $sql = "SELECT u.ID FROM users u, profile p, relationships r, relationship_types t WHERE t.ID=r.type AND r.accepted=1 AND r.usera='" . $user . "' AND IF( r.usera='" . $user . "',u.ID=r.usera) AND p.user_id=u.ID";
        $this->registry->getObject('db')->executeQuery($sql);
        $network = array();
        if ($this->registry->getObject('db')->numRows() > 0) {
            while ($r = $this->registry->getObject('db')->getRows()) {
                $network[] = $r['ID'];
            }
        }
        return $network;
    }
}

?>