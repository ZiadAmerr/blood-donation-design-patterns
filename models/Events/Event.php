<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";


abstract class Event{
    protected $title;
    protected $address;
    protected $dateTime;
    
    protected function getDetails()
    {}
    
    
}

?>

