<?php

class C_Index extends C_Base
{
	function action_index()
	{
		$err_msg = '';
		$error = false;
		$login = '';
		
		$this->content = $this->Template('view/v_index.php', 
			 array(
				'error' => $error,
				'login' => $login,
				'err_msg' => $err_msg));
	}
}

?>