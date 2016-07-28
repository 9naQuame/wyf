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
}