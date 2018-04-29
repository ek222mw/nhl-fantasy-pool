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
		else if(($this->view->didUserPressViewPool() ||  $this->view->didUserPressViewTeam()) && $this->loginModel->checkLoginStatus())
		{
			
			$this->doView();
		}
		else if($this->view->didUserPressAddPlayerToTeam() && $this->loginModel->checkLoginStatus())
		{
			$this->doAddPlayerToTeam();
		}
		else if($this->view->didUserPressTrade() && $this->loginModel->checkLoginStatus())
		{
			$this->doTradePlayer();
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

	public function doAddPlayerToTeam()
	{
		if($this->view->didUserPressAddPlayerToTeamBtn() && $this->loginModel->checkLoginStatus())
		{
			$teamInput = $this->view->getTeamDropdownInputAddPlayer();
			$playerInput = $this->view->getPlayerDropdownInput();
			try{
				if($this->model->CheckLength($teamInput) && $this->model->CheckLength($playerInput))
				{
					if($this->loginModel->validateInput($teamInput) && $this->loginModel->validateInput($playerInput))
					{
						if($this->db->checkIfPlayerAlreadyExists($teamInput, $playerInput))
						{
							$playerArr = $this->db->fetchPickedApiPlayer($playerInput);
							$this->db->addPlayerToTeam($teamInput, $playerArr);
							$this->view->successfulAddPlayerToTeam();
							$this->view->showAddPlayertoTeam($this->db->fetchAllTeams(), $this->db->fetchAllApiPlayers());
						}
						
					}
				}
			}
			catch(Exception $e)
			{
				$this->view->showMessage($e->getMessage());
				$this->view->showAddPlayertoTeam($this->db->fetchAllTeams(), $this->db->fetchAllApiPlayers());
			}

		}
		else{
			$this->view->showAddPlayertoTeam($this->db->fetchAllTeams(), $this->db->fetchAllApiPlayers());
		}
		
	}

	public function doTradePlayer()
	{
		
		
		if($this->view->didUserPressPoolForTradeBtn())
		{
			$chosenPool = $this->view->getPoolForTradeInput();
			try{
				if(($this->model->CheckLength($chosenPool) && $this->loginModel->validateInput($chosenPool)))
				{
					
					$this->view->showTeamForTrade($this->db->fetchChosenPoolTeams($chosenPool));
				}
			}
			catch(Exception $e)
			{
				$this->view->showMessage($e->getMessage());
				$this->view->showPoolForTrade($this->db->fetchAllPools());
			}
		}
		else if($this->view->didUserPressTeamForTradeBtn())
		{
			$chosenTeam = $this->view->getTeamForTradeInput();
			try{
				if(($this->model->CheckLength($chosenTeam) && $this->loginModel->validateInput($chosenTeam)))
				{
					$this->view->showTeamPlayerstoTrade($this->db->fetchChosenTeamPlayers($chosenTeam), $this->db->fetchAllApiPlayers());
				}
			}
			catch(Exception $e)
			{
				$this->view->showMessage($e->getMessage());
				$this->view->showPoolForTrade($this->db->fetchAllPools());
			}

		}
		else if($this->view->didUserPressPlayerForTradeBtn())
		{
			$apiplayer = $this->view->getApiPlayerForTradeInput();
			$teamplayer = $this->view->getTeamPlayerForTradeInput();
			try{


				if(($this->model->CheckLength($apiplayer) && $this->loginModel->validateInput($apiplayer)) && ($this->model->CheckLength($apiplayer) && $this->loginModel->validateInput($apiplayer)))
				{
					if($this->db->checkIfPlayerisTaken($apiplayer))
					{
						$playerArr = $this->db->fetchPickedApiPlayer($apiplayer);
						$this->db->tradePlayer($playerArr, $teamplayer);
						$this->view->successfulTradedPlayerToTeam();
						$this->view->showPoolForTrade($this->db->fetchAllPools());
					}
				}
			}
			catch(Exception $e)
			{
				$this->view->showMessage($e->getMessage());
				$this->view->showPoolForTrade($this->db->fetchAllPools());
			}	
			


		}
		else if($this->view->didUserPressTrade())
		{
			$this->view->showPoolForTrade($this->db->fetchAllPools());
		}
		
	}

	public function doView()
	{
		if($this->view->didUserPressViewPool())
		{
			$filter = $this->view->didUserPressViewPool();
			$result = $this->db->fetchChosenPoolTeams($filter);
			$this->view->showPickedPoolTeams($result, $filter);
		}
		else if($this->view->didUserPressViewTeam())
		{

			$team = $this->view->didUserPressViewTeam();
			$result = $this->db->fetchChosenTeamPlayers($team);

			$this->view->showPickedTeamPlayers($result, $team);
		}
		

	}

	

}