<?php

class Utils
{
    private static $singulars = array();
    private static $plurals = array();
    
    /**
     * Returns the sigular form of any plural english word which is passed to it.
     * 
     * @param string $word
     * @see Utils::plural
     */
    public static function singular($word)
    {
        $singular = array_search($word, Utils::$singulars);
        if($singular == false)
        {
            if(substr($word, -3) == "ses")
            {
                $singular = substr($word, 0, strlen($word) - 2);
            }
            elseif(substr($word, -3) == "ies")
            {
                $singular = substr($word, 0, strlen($word) - 3) . "y";
            }
            elseif(strtolower($word) == "indices")
            {
                $singular = "index";
            }
            else if(substr(strtolower($word), -4) == 'news')
            {
                $singular = $word;
            }
            else if(substr(strtolower($word), -8) == 'branches')
            {
                $singular = substr($word, 0, strlen($word) - 2);
            }
            else if(substr($word, -1) == "s")
            {
                $singular = substr($word, 0, strlen($word) - 1);
            }
            else
            {
                $singular = $word;
            }
            Utils::$singulars[$singular] = $word;
        }
        return $singular;
    }

    /**
     * Returns the plural form of any singular english word which is passed to it.
     * 
     * @param string $word
     */
    public static function plural($word)
    {
        $plural = array_search($word, Utils::$plurals);
        if($plural === false)
        {
            if(substr($word, -1) == "y")
            {
                $plural = substr($word, 0, strlen($word) - 1) . "ies";
            }
            elseif(strtolower($word) == "index")
            {
                $plural = "indices";
            }            
            elseif(substr($word, -2) == "us")
            {
                $plural = $word . "es";
            } 
            elseif(substr($word, -2) == "ss")
            {
                $plural = $word . "es";
            }
            elseif(substr($word, -1) != "s")
            {
                $plural = $word . "s";
            }
            else
            {
                throw new exceptions\UnknownPluralException("Could not determine the plural for $word");
            }
            Utils::$plurals[$plural] = $word;
        }
        return $plural;
    }    
}
