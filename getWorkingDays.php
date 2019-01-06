<?php

class Workdayser
{
    function GetWorkDays($StartDateDay, $EndDateDay)
    {
        $Res = array ();
        $CurrentDateDay = $StartDateDay;
        while(strtotime($CurrentDateDay) <= strtotime($EndDateDay))
        {
            array_push($Res, $CurrentDateDay);
            $CurrentDateDay = date("Y-m-d", strtotime("+1 day", strtotime($CurrentDateDay)));
        }
        return $Res;
    }
}
?>