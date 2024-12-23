<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";


abstract class Event{
    protected string $title;
    protected Address $address;
    protected DateTime $dateTime;
    
    abstract protected function getDetails(): string;    
    
}

?>

