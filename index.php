<?php
    require_once 'counter.php';

    if (isset($_GET['userId']))
    {
        $UserId = strip_tags($_GET['userId']);   
    }
    if (isset($_GET['startDate']))
    {
        $StartDate = strip_tags($_GET['startDate']);
    }
    if (isset($_GET['endDate']))
    {
        $EndDate = strip_tags($_GET['endDate']);
    }
    
    
    if (isset($UserId) && isset($StartDate) && isset($EndDate))
    {
        $CounterController = new Counter;

        $StartDate = date($StartDate);
        $EndDate = date($EndDate);
    
        $CounterController -> GetWorkTime($StartDate, $EndDate, $UserId);
    }
    else
    {
        echo('Error: Incorrect input data');
    }

?>