<?php

class Pagination
{
    private $query; //The query to be paginated
    private $processedQuery; //The processed query
    private $limit = 20; //Max number of results per page
    private $offset = 0; //Results offset (The page we are on)
    private $method; //Pagination method
    private $cache; //The cache ID if we paginate by caching results
    private $results; //Results if pagination by direct execution
    private $numRows; //Number of rows in passed query
    private $numRowsPage; //Number of rows in the current page
    private $numPage; //Number of pages of results
    private $isFirst; //If its the first page
    private $isLast; //If its the last page
    private $currentPage; //If its the current page

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    //Set the query to be paginated
    public function setQuery($que)
    {
        $this->query = $que;
    }

    //Set the limit of number of results per page
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    //Set the offset, then show next page of results
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    //Process the query and set the paginated properties
    public function Paginagen() //generatePagination
    {
        $temp_query = $this->query;
        $this->registry->getObject('db')->executeQuery($temp_query);
        $num = $this->registry->getObject('db')->numRows();
        $this->numRows = $num;
        $limit = " LIMIT ";
        $limit .= ($this->offset * $this->limit) . ", " . $this->limit;
        $temp_query .= $limit;
        if ($this->method == "cache") {
            $this->registry->getObject('db')->cacheQuery($temp_query);
        } elseif ($this->method == "do") {
            $this->registry->getObject('db')->executeQuery($temp_query);
            $this->results = $this->getObject('db')->getRows();
        }
        $this->processedQuery = $temp_query; //The query is now processed
        //Number of pages
        $this->numPage = ceil($this->numRows / $this->limit);
        $this->isFirst = ($this->offset == 0) ? true : false;
        $this->isLast = (($this->offset + 1) == $this->numPage) ? true : false;
        $this->currentPage = ($this->numPage == 0) ? 0 : $this->offset + 1;
        $this->numRowsPage = $this->registry->getObject('db')->numRows();
        if ($this->numRowsPage == 0) {
            return false;
        } else {
            return true;
        }
    }

    //Get Cache ID
    public function getCache()
    {
        return $this->cache;
    }

    //Get the results of the query
    public function getResults()
    {
        return $this->results;
    }

    //Get number of pages of results
    public function getNumPage()
    {
        return $this->numPage;
    }

    //Get if the current page is the first page
    public function getIsFirst()
    {
        return $this->isFirst();
    }

    //Get if the current page is the last page
    public function getIsLast()
    {
        return $this->isLast();
    }

    //Get the current page
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    //Get the number of results per page
    public function getNumRowsPage()
    {
        return $this->numRowsPage;
    }
}

?>