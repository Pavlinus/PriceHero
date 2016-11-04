<?php

class C_Faq extends C_Base
{
    
    public function __construct()
    {
        
    }
    

    function action_index()
    {
        $this->content = $this->Template(
                'view/v_faq.php', 
                array()
        );
    }
    
}
