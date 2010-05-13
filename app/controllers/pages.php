<?php

class PagesController extends Controller 
{

	function home() {
		$this->pageTitle = "Testing sunFW";
		$this->redirect("/kiss/high/");
	}

}

?>
