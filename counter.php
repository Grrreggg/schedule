<?php
require_once 'user_data.php';
require_once 'counter.php';
require_once 'getHolidaysList.php';
require_once 'getWorkingDays.php';
require_once 'getDayOffs.php';
require_once 'getWeekends.php';

class Counter
{
    public $OutputData = array ();
    
    function GetWorkTime($StartDate, $EndDate, $user_id)
    {
        $UsersController = new UserData;
        $HoidaysController = new Hoidayser;
        $WorkDaysController = new Workdayser;
        $DayOffsController = new Dayoffer;
        $WeekendsController = new Weekender;

        $UserData = $UsersController -> GetUser($user_id);
        $CorporateTemp = $UsersController -> GetCorporate();
        $Corporate = array ();

        $HolidaysArray = $HoidaysController -> GetHolidays($StartDate, $EndDate);
        $DayOffsArray = $DayOffsController -> GetDayOffs($StartDate, $EndDate, $UserData["dayoffs"]);
        $WeekendsArray = $WeekendsController -> GetWeekends($StartDate, $EndDate);

        $HolidaysTotalArray = array_merge($HolidaysArray, $DayOffsArray);
        $HolidaysTotalArray = array_merge($HolidaysTotalArray, $WeekendsArray);
        $HolidaysTotalArray = array_unique($HolidaysTotalArray);

        $WorkDaysArray = $WorkDaysController -> GetWorkDays($StartDate, $EndDate);
        $ResultWorkingDays = array ();

        foreach ($WorkDaysArray as $WorkDay)
        {
            if (!in_array($WorkDay, $HolidaysTotalArray)){
                array_push( $ResultWorkingDays, $WorkDay);
            }
        }
        
        //GET ALL CORPORATES BY YEAR
        $CurrentDateDay = $StartDate;
        while(strtotime($CurrentDateDay) <= strtotime($EndDate))
        {
            foreach ($CorporateTemp["date"] as $CorporateDate)
            {
                $CurrentCorporateYear = substr($CurrentDateDay, 0, 4);
                $CurrentCorporateDay =  substr($CorporateDate, 2);

                array_push($Corporate, $CurrentCorporateYear.$CurrentCorporateDay);
            }
            $CurrentDateDay = date("Y-m-d", strtotime("+1 year", strtotime($CurrentDateDay)));
        }
        
        //FORM OUTPUT ARRAY WITH WORKING TIME
        foreach ($ResultWorkingDays as $WorkingDay)
        {
            $WorkTimeBeforeLunch = array( $UserData["worktime"][0][0], $UserData["worktime"][0][1] );
            $WorkTimeAfterLunch = array( $UserData["worktime"][1][0], $UserData["worktime"][1][1] );

            $Conditions;

            if (in_array($WorkingDay, $Corporate))
            {
                $Conditions = array (
               // $ConditionsNoWork
                    ((strtotime($CorporateTemp["time"][0]) <= strtotime($UserData["worktime"][0][0]))),
               //$ConditionsEndLunch
                    (!(strtotime($CorporateTemp["time"][0]) <= strtotime($UserData["worktime"][0][0])) &&
                    (strtotime($CorporateTemp["time"][0]) <= strtotime($UserData["worktime"][0][1]))),
                //$ConditionsNoAfterLunch
                    ((strtotime($CorporateTemp["time"][0]) > strtotime($UserData["worktime"][0][1])) &&
                    (strtotime($CorporateTemp["time"][0]) <= strtotime($UserData["worktime"][1][0]))),
                //$ConditionsEndAfterLunch
                    (!(strtotime($CorporateTemp["time"][0]) <= strtotime($UserData["worktime"][1][0])) &&
                    (strtotime($CorporateTemp["time"][0]) <= strtotime($UserData["worktime"][1][1]))),
                );
                switch($Conditions)
                {
                    case array (1, null, null, null):
                    $WorkTimeBeforeLunch = null;
                    $WorkTimeAfterLunch = null;
                    break;

                    case array (null, 1, null, null):
                    $WorkTimeBeforeLunch = array( $UserData["worktime"][0][0], $CorporateTemp["time"][0] );
                    $WorkTimeAfterLunch = null;
                    break;

                    case array (null, null, 1, null):
                    $WorkTimeAfterLunch = null;
                    break;

                    case array (null, null, null, 1):
                    $WorkTimeAfterLunch = array( $UserData["worktime"][1][0], $CorporateTemp["time"][0] );
                    break;
                }
            }
            $this -> PushWorkDay($WorkingDay, $WorkTimeBeforeLunch, $WorkTimeAfterLunch); 
        } 
      
        $FinalResult = array (
            'schedule' => $this -> OutputData
        );
        
        echo(json_encode ($FinalResult));
    }

    
    function PushWorkDay($Day, $TimeBeforeLunch, $TimeAfterLunch)
    {
        if (!$Day || (!$TimeBeforeLunch && !$TimeAfterLunch))
        {
            return;
        }
        $Result = array (
            'day' => $Day,
            'timeRanges' => 
            array (
                'start' => $TimeBeforeLunch[0],
                'end' => $TimeBeforeLunch[1]
            ),
            array (
                'start' => $TimeAfterLunch[0],
                'end' => $TimeAfterLunch[1]
            ),
        );

        if (!$TimeAfterLunch)
        {
            unset($Result['timeRanges'][1]);
        }
        array_push($this -> OutputData, $Result);
    }
}
?>