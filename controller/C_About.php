<?php

class C_About extends C_Base
{
    
    public function __construct()
    {
        
    }
    

    function action_index()
    {
        $this->content = $this->Template(
                'view/v_about.php', 
                array()
        );
    }
    
}
