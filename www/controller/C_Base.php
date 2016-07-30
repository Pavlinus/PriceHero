<?php

abstract class C_Base extends C_Controller
{
	protected $title;
	protected $content;
	
	public function renderPage()
	{

		$page = $this->Template('view/v_header.php', array());
		$page .= $this->content;
		$page .= $this->Template('view/v_footer.php', array());
		
		echo $page;
	}
	
	public function renderContent()
	{
		echo $this->content;
	}
}
?>