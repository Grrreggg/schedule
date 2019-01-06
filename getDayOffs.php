<?php

class Dayoffer
{
    function GetDayOffs($StartDateYear, $EndDateYear, $WorkerDayOffsDates)
    {
        $Result = array ();
        $WorkerDayOffsDatesByYear = array();
        $CurrentDateYear = $StartDateYear;

        //CONVERT DAY OFFS ARRAY TO EVERY YEAR NEEDED
        while(strtotime($CurrentDateYear) <= strtotime($EndDateYear))
        {
            foreach ($WorkerDayOffsDates as $WorkerDayOffsDate)
            {
                $Year = substr($CurrentDateYear, 0, 4);
                $ResStartDate = substr($WorkerDayOffsDate[0], 1);
                $ResEndDate = substr($WorkerDayOffsDate[1], 1);
                $ResArray = array($Year.$ResStartDate, $Year.$ResEndDate);

                array_push($WorkerDayOffsDatesByYear, $ResArray);
            }
            $CurrentDateYear = date("Y-m-d", strtotime("+1 year", strtotime($CurrentDateYear)));
        }

        //THEN GET RESULT DAY OFFS
        foreach ($WorkerDayOffsDatesByYear as $WorkerDayOffsDate)
        {
            $CurrentDate = date($WorkerDayOffsDate[0]);
            $EndDate = date($WorkerDayOffsDate[1]);

            while(strtotime($CurrentDate) <= strtotime($EndDate))
            {
                array_push($Result, $CurrentDate);
                $CurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($CurrentDate)));
            }
        }
        return $Result;
    }
}
?>