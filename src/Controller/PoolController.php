<?php

require_once("./src/model/Database.php");
require_once("./src/model/PoolModel.php");
require_once("./src/view/PoolView.php");
require_once("./src/model/LoginModel.php");

class PoolController{
	
	private $model;
	private $view;
	private $db;
	private $loginModel;

	public function __construct(){

		$this->model = new PoolModel();
		$this->view = new PoolView($this->model);
		$this->db = new Database();
		$this->loginModel = new LoginModel();

		if(($this->view->didUserPressCreatePool() || $this->view->didUserPressCreatePoolBtn())  && $this->loginModel->checkLoginStatus())
		{	
			$this->doCreatePool();
		}
		else if($this->view->didUserPressCreateTeam() && $this->loginModel->checkLoginStatus())
		{
			$this->doCreateTeam();
		}
		else if($this->view->didUserPressViewPool() && $this->loginModel->checkLoginStatus())
		{
			
			$this->doView();
		}
	}

	public function doCreatePool()
	{
		
		if($this->view->didUserPressCreatePoolBtn() && $this->loginModel->checkLoginStatus())
		{
			
			$poolInput = $this->view->getPoolInput();

			try{

				if($this->model->CheckLength($poolInput))
				{
					if($this->loginModel->validateInput($poolInput))
					{
						if($this->db->checkIfPoolExist($poolInput))
						{
							$this->db->addPool($poolInput);
							$this->view->successfulAddPool();
							$this->view->showCreatePoolForm();
						}
					}
				}

			}
			catch(Exception $e)
			{
				$this->view->showMessage($e->getMessage());
				$this->view->showCreatePoolForm();
			}

		}
		else if($this->view->didUserPressCreatePool()  && $this->loginModel->checkLoginStatus())
		{
			
			$this->view->showCreatePoolForm();
		}
	}

	public function doCreateTeam()
	{
		if($this->view->didUserPressCreateTeamBtn() && $this->loginModel->checkLoginStatus())
		{
			$teamInput = $this->view->getTeamInput();
			$poolInput = $this->view->getTeamsPoolDropdownInput();

			try{
				if($this->model->CheckLength($poolInput) && $this->model->CheckLength($teamInput))
				{
					if($this->loginModel->validateInput($poolInput) && $this->loginModel->validateInput($teamInput)){


						if($this->db->checkIfRowExists($teamInput,$poolInput))
						{
							if($this->db->checkIfPickPoolFromPoolTblIsManipulated($poolInput))
							{
								$this->db->addTeamToPool($teamInput,$poolInput);
								$this->view->successfulAddTeamToPool();
								$this->view->showCreateTeamForm($this->db->fetchAllPools());
							}
						}
					}
				}

			}
			catch(Exception $e)
			{
				$this->view->showMessage($e->getMessage());
				$this->view->showCreateTeamForm($this->db->fetchAllPools());
			}
		}
		else{
			$this->view->showCreateTeamForm($this->db->fetchAllPools());
		}

		
	}

	public function doView()
	{
		$filter = $this->view->didUserPressViewPool();
		$result = $this->db->fetchChosenPoolTeams($filter);
		$this->view->showPickedPoolTeams($result, $filter);

	}

	

}