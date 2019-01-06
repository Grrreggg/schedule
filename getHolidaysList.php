<?php

class Hoidayser
{
    function GetHolidays($dateStart, $dateEnd)
    {
        $response = '';

        $current_date = $dateStart;
        //REQUEST HOLIDAYS IN ALL YEARS WITHIN THE RANGE
        while(strtotime($current_date) <= strtotime($dateEnd))
        {
            $current_year = substr($current_date, 0, 4);
            $response .= file_get_contents('https://holidayapi.pl/v1/holidays?country=RU&year='.$current_year, true);
            $current_date = date("Y-m-d", strtotime("+1 year", strtotime($current_date)));
        }

        return $this -> parce_responce($response);
    }

    //GET CLEAN ARRAY OF HOLIDAYS
    protected function parce_responce($response)
    {
        $result = array ();
        $response_holidays = $this -> getContents($response, 'date":"', '}]');

        foreach ($response_holidays as $holiday)
        {
            if (strpos($holiday, 'false') === false)
            {
                $holiday = substr($holiday, 0, 10);
                array_push($result, $holiday);
            }
        }
        return $result;     
    }
    //GET SUBSTRING BETWEEN TWO STRINGS
    protected function getContents($str, $startDelimiter, $endDelimiter) {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;

        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
        $contentStart += $startDelimiterLength;
        $contentEnd = strpos($str, $endDelimiter, $contentStart);

        if (false === $contentEnd) {
            break;
        }

        $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
        $startFrom = $contentEnd + $endDelimiterLength;
        }
        return $contents;
    }
}
?>