<?php

class Weekender
{
    function GetWeekends($StartDateDay, $EndDateDay)
    {
        $Res = array ();
        $CurrentDateDay = $StartDateDay;
        while(strtotime($CurrentDateDay) <= strtotime($EndDateDay))
        {
            $CurrentDayNum = date('w',strtotime($CurrentDateDay));
            if (($CurrentDayNum == 0) || ($CurrentDayNum == 6))
            {
                array_push($Res, $CurrentDateDay);
            }
            $CurrentDateDay = date("Y-m-d", strtotime("+1 day", strtotime($CurrentDateDay)));
        }
        return $Res;
    }
}
?>