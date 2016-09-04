<?php

class C_Index extends C_Base
{
    private $mCatalog;
    
    public function __construct()
    {
        if($this->mCatalog == null)
        {
            $this->mCatalog = M_Catalog::Instance();
        }
    }
    
    function action_index()
    {
        $err_msg = '';
        $error = false;
        $login = '';
        
        $this->mCatalog->getLastUpdates();

        $this->content = $this->Template('view/v_index.php', 
                array(
                    'error' => $error,
                    'login' => $login,
                    'err_msg' => $err_msg
                )
        );
    }
}
