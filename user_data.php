<?php

class UserData
{        
    public $users = array ( 
        "1" => array ( 
            "dayoffs" =>    array (
                ['0-01-11', '0-01-25'],
                ['0-02-01', '0-02-15'],
            ),
            "worktime" =>   array (
                ['10:00', '13:00'],
                ['14:00', '19:00'],
            ),
        ),
        "2" => array ( 
            "dayoffs" =>    array (
                ['0-02-01', '0-03-01'],
            ),
            "worktime" =>   array (
                ['09:00', '12:00'],
                ['13:00', '18:00'],
            ),
        ),
    );

    public $corporate = array (
        "date" => ['00-01-10'],
        "time" => ['15:00', '00:00'],
    );

    function GetUser($id){
        return $this -> users[strval($id)];
    }

    function GetCorporate(){
        return $this -> corporate;
    }
}


?>