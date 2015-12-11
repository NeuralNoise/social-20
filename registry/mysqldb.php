<?php

//Database Management
class mySQLDB
{
    //Allow multiple connections to database and have each connecton stored in array and accessed as variable
    private $connections;

    //Set which connection is active
    private $activeConnection = 0;

    //Tells about executed queries and the cached results. Mainly for template engine
    private $queryCache = array();

    //Data which has been prepared and cached for later. Mainly for template engine
    private $dataCache = array();

    //Number of queries during execution
    private $queryCounter = 0;

    //Record of the last query
    private $last;

    //Reference to the registry object
    private $registry;

    //Construct the database object
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    //Create a new database connection
    /*
    @param $host The host for mysql
    @param $user The username for the connection
    @param $pass The password for the connection
    @param $database The database to be used for the connection
    @return id of the connection in the $connections[] array
    */
    public function newConnection($host, $user, $pass, $database)
    {
        $this->connections[] = new mysqli($host, $user, $pass, $database);
        $connection_id = count($this->connections) - 1;
        if (mysqli_connect_errno()) {
            trigger_error('Error connecting to host. ' . $this->connections[$connection_id]->error, E_USER_ERROR);
        }
        return $connection_id;
    }

    //Change which database connection is currently being used
    /*
    @param int id for the connection
    @return void
    */
    public function setActiveConnection(int $new)
    {
        $this->activeConnection = $new;
    }

    //Execute a query string
    /*
    @param the query string to be executed
    */
    public function executeQuery($querystr)
    {
        if (!$result = $this->connections[$this->activeConnection]->query($querystr)) {
            trigger_error('Error executing query: ' . $querystr . ' - ' . $this->connections[$this->activeConnection]->error, E_USER_ERROR);
        } else {
            //var_dump($result);
            $this->last = $result;
        }
    }

    //Cache the query
    public function cacheQuery($querystr)
    {
        $result = $this->connections[$this->activeConnection]->query($querystr);
        if (!$result) {
            trigger_error('Error executing and caching query: ' . $this->connections[$this->activeConnection]->error, E_USER_ERROR);
            return -1;
        } else {
            $this->queryCache[] = $result;
            return count($this->queryCache) - 1;
        }
    }

    //Get number of rows from cache for a particular id
    public function numRowsFromCache($cache_id)
    {
        return $this->queryCache[$cache_id]->num_rows;
    }

    //Get rows from a cached query
    public function resultsFromCache($cache_id)
    {
        return $this->queryCache[$cache_id]->fetch_array(MYSQLI_ASSOC);
    }

    //Store some data in cache for user
    public function cacheData($data)
    {
        $this->dataCache[] = $data;
        return count($this->dataCache) - 1;
    }

    //Get data from data cache
    public function dataFromCache($cache_id)
    {
        return $this->dataCache[$cache_id];
    }

    //Get the rows from the most recently executed query, excluding cached queries
    /*
    @return array
    */
    public function getRows()
    {
        return $this->last->fetch_array(MYSQLI_ASSOC);
    }

    //Delete records from the database
    /*
    @param String $table The table to remove the rows from
    @param String $condition The condition which has to be satisfied to remove the rows
    @param int $limit The max number of rows to be removed
    @return void
    */
    public function deleteRecords($table, $condition, $limit = 1)
    {
        $limit = ($limit == '') ? '' : ' LIMIT ' . $limit;
        $delete = "DELETE FROM `" . $table . "` WHERE " . $condition . " " . $limit;
        $this->executeQuery($delete);
    }

    //Update records in the database
    /*
    @param String the table where the records will go
    @param array of changes field=>value
    @param String the condition
    @return bool
    */
    //UPDATE `profile` SET `user_id`=[value-1],`username`=[value-2],`location`=[value-3],`gender`=[value-4],`dob`=[value-5],`name`=[value-6],`bio`=[value-7],`photo`=[value-8] WHERE 1
    public function updateRecords($table, $changes, $condition)
    {
        $update = "UPDATE `" . $table . "` SET ";
        //var_dump($changes);
        foreach ($changes as $field => $value) {
            $update .= "`" . $field . "`='" . $value . "',";
        }

        //remove the trailing ,
        $update = substr($update, 0, -1);
        if ($condition != '') {
            $update .= " WHERE " . $condition;
        }
        //echo $update;
        $this->executeQuery($update);
        //var_dump($this->last);
        return true;
    }

    //Insert records into the database
    /*
    @param String the database table
    @param array data to insert field=>value
    @return bool
    */
    public function insertRecords($table, $data)
    {
        //Setup some variables for fields and values
        $fields = "`";
        $values = "";

        //populate them
        foreach ($data as $f => $v) {
            $fields .= $f . "`,`";
            $values .= (is_numeric($v) && (intval($v) == $v)) ? $v . "," : "'" . $v . "',";
        }

        //remove our trailings
        $fields = substr($fields, 0, -2); //,`
        $values = substr($values, 0, -1); //,

        $insert = "INSERT INTO `" . $table . "` (" . $fields . ") VALUES (" . $values . ")";
        $this->executeQuery($insert);
        return true;
    }

    //Sanitize data
    /*
    @param String the data to be sanitized
    @return String the sanitized data
    */
    public function sanitizeData($data)
    {
        //Unquote a quoted string (stripslashes) if magic quotes are set in Get/Post/Cookie
        if (get_magic_quotes_gpc()) {
            $values = stripslashes($data);
        }

        //Quote value
        if (version_compare(phpversion(), "4.3.0") == "-1") {
            $data = $this->connections[$this->activeConnection]->escape_string($data);
        } else {
            $data = $this->connections[$this->activeConnection]->real_escape_string($data);
        }
        return $data;
    }

    //Get the id of where the last insert was made
    public function lastInsertID()
    {
        return $this->connections[$this->activeConnection]->insert_id;
    }

    //Get the number of rows
    public function numRows()
    {
        return $this->last->num_rows;
    }

    //Get the number of affected rows from the previous query
    public function affectedRows()
    {
        //var_dump($this->last);
        //return $this->last->affected_rows;
        return $this->connections[$this->activeConnection]->affected_rows;
    }

    //Destroy the object
    public function __destruct()
    {
        foreach ($this->connections as $con) {
            $con->close();
        }
    }
}

?>