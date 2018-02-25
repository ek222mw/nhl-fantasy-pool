<?php

require_once("./src/model/LoginModel.php");
require_once("./src/view/LoginView.php");
require_once("./src/view/PoolView.php");
require_once("LoginController.php");
require_once("PoolController.php");
require_once("./src/model/PoolModel.php");

class NavigationController{
	
	private $model;
	private $view;
	private $poolView;
	private $poolModel;


	public function __construct(){

		$this->model = new LoginModel();
		$this->PoolModel = new poolModel();
		$this->view = new LoginView($this->model);
		$this->poolView = new poolView($this->PoolModel);

		if(($this->poolView->didUserPressCreatePool() || $this->poolView->didUserPressCreateTeam() || $this->poolView->didUserPressViewPool() || $this->poolView->didUserPressViewTeam()) || $this->poolView->didUserPressAddPlayerToTeam()  && $this->model->checkLoginStatus())
		{
			
			new PoolController();
				
		}
		else{
			$loginControl = new LoginController();
			$htmlBodyLogin = $loginControl->doHTMLBody();
		}
		

	}

	
}