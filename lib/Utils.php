<?php

class Utils
{
    public static function getNextValue($sequence, $redirected = false)
    {
        $name = $redirected ? "$redirected.$sequence" : $sequence;
       
        $sequences = Model::load("system.sequences");
        return $sequences->nextval($name);
    }
    
    public static function getConfiguration($name, $redirected = false)
    {
        $key = $redirected ? "$redirected.$name" : $name;
        
        $configurations = Model::load("system.configurations");
        $value = $configurations->get(["fields" => "value","conditions" => "key = '$key'"]);
        
        return $value[0]['value'];
    }
    
    public static function addDays($days, $date = null, $format = 'd/m/Y' )
    {
        $operand = abs($days); 
        $operator = intval($days) < 0 ? '-' : '+';
        $timestamp = $date ? (is_numeric($date) ? $date : Utils::stringToTime($date)) : time();
       
        if($format == 'timestamp')
        {
            return strtotime("$operator $operand days", $timestamp);
        }
        
        return date($format, strtotime("$operator $operand days", $timestamp));
    }
    
    public static function dateDifference($startDate, $endDate)
    {
        $start = is_numeric($startDate) ? $startDate : Utils::stringToTime($startDate);
        $end = is_numeric($endDate) ? $endDate : Utils::stringToTime($endDate);
        
        return floor(($end - $start) / 86400);
    }
    
    public static function unComma($number)
    {
        return str_replace(',', '', $number);
    }
       
    public static function stringToTime($string, $hasTime = false)
    {
        if(preg_match("/(\d{2})\/(\d{2})\/(\d{4})(\w\d{2}:\d{2}:\d{2})?/", $string) == 0) return false;
        $dateComponents = explode(" ", $string);

        $decomposeDate = explode("/", $dateComponents[0]);
        $decomposeTime = array();

        if($hasTime === true)
        {
            $decomposeTime = explode(":", $dateComponents[1]);
        }

        return
        strtotime("{$decomposeDate[2]}-{$decomposeDate[1]}-{$decomposeDate[0]}") +
        ($hasTime === true ? ($decomposeTime[0] * 3600 + $decomposeTime[1] * 60 + $decomposeTime[2]) : 0);
    }
    
    public static function stringToDatabaseDate($string, $hasTime = false)
    {
        $timestamp = self::stringToTime($string, $hasTime);
        return date("Y-m-d", $timestamp);
    }
    
    public function sentenceTime($time, $options = null)
    {
        $elapsed = time() - $time;

        if($elapsed < 10)
        {
            $englishDate = 'now';
        }
        elseif($elapsed >= 10 && $elapsed < 60)
        {
            $englishDate = "$elapsed seconds";
        }
        elseif($elapsed >= 60 && $elapsed < 3600)
        {
            $minutes = floor($elapsed / 60);
            $englishDate = "$minutes minutes";
        }
        elseif($elapsed >= 3600 && $elapsed < 86400)
        {
            $hours = floor($elapsed / 3600);
            $englishDate = "$hours hour" . ($hours > 1 ? 's' : '');
        }
        elseif($elapsed >= 86400 && $elapsed < 172800)
        {
            $englishDate = "yesterday";
        }
        elseif($elapsed >= 172800 && $elapsed < 604800)
        {
            $days = floor($elapsed / 86400);
            $englishDate = "$days days";
        }
        elseif($elapsed >= 604800 && $elapsed < 2419200)
        {
            $weeks = floor($elapsed / 604800);
            $englishDate = "$weeks weeks";
        }
        elseif($elapsed >= 2419200 && $elapsed < 31536000)
        {
            $months = floor($elapsed / 2419200);
            $englishDate = "$months months";
        }
        elseif($elapsed >= 31536000)
        {
            $years = floor($elapsed / 31536000);
            $englishDate = "$years years";
        }

        switch($options['elaborate_with'])
        {
            case 'ago':
                if($englishDate != 'now' && $englishDate != 'yesterday')
                {
                    $englishDate .= ' ago';
                }
                break;
        }

        return $englishDate;
    }
}