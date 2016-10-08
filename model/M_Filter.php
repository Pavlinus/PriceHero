<?php

class M_Filter
{
    private $msql;
    private static $instance;

    
    function __constructor()
    {
        $msql = M_MSQL::Instance();
    }

    
    public static function Instance()
    {
        if(self::$instance == null)
        {
            self::$instance = new Filter();
        }

        return self::$instance;
    }
}
