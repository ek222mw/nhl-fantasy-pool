<?php

class PoolView extends HTMLView{
	

	private $model;
	private $message = "";
	private $createPoolLink = "createpoollink";
	private $createPoolButton = "createpoolbutton";
	private $createTeamButton = "createteambutton";
	private $createPoolName = "createpool";
	private $createTeamName = "createteam";
	private $createTeamLink = "createteamlink";
	private $dropdownpickteam = "dropdownpickteam";
	private $dropdownpickteam2 = "dropdownpickteam2";
	private $dropdownpickplayer = "dropdownpickplayer";
	private $addPlayerToTeam = "addplayertoteam";
	private $addPlayerToTeamBtn = "addplayertoteambtn";

	public function __construct(PoolModel $model)
	{
			$this->model = $model;
	}

	public function didUserPressCreatePool()
	{
			 
		return isset($_GET[$this->createPoolLink]);
			
	}

	public function didUserPressCreateTeam()
	{
			 
		return isset($_GET[$this->createTeamLink]);
			
	}

	public function showCreatePoolForm()
	{
					$contentString = 
					 "
					<form method=post >
						<fieldset class='fieldaddevent'>
							<legend>Create new pool - Input new pool name</legend>
							$this->message
							<span style='white-space: nowrap'>Pool:</span> <input type='text' name='$this->createPoolName'><br>
							<span style='white-space: nowrap'></span> <input type='submit' name='$this->createPoolButton'  value='Create'>
						</fieldset>
					</form>";
					$HTMLbody = "<div class='divaddevent'>
					<h1>Create new Pool</h1>
					<p><a href='?login'>Back</a></p>
					$contentString<br>
					</div>";
					$this->echoHTML($HTMLbody);
	}

	public function showCreateTeamForm(PoolList $poollist)
	{
		$contentString = 
					 "
					<form method=post >
						<fieldset class='fieldaddevent'>
							<legend>Create new team - Input new team name</legend>
							$this->message
							<span style='white-space: nowrap'>Team:</span> <input type='text' name='$this->createTeamName'><br>
							<span style='white-space: nowrap'>Pool:</span>
							<select name='$this->dropdownpickteam'>";
							 foreach($poollist->toArray() as $pool)
							 {
							 	$contentString.= "<option value='". $pool->getName()."'>".$pool->getName()."</option>";
							 }
							 
							 $contentString .= "</select>
							 <br>
							<span style='white-space: nowrap'></span> <input type='submit' name='$this->createTeamButton'  value='Create'>
						</fieldset>
					</form>";
					$HTMLbody = "<div class='divaddevent'>
					<h1>Create new team</h1>
					<p><a href='?login'>Back</a></p>
					$contentString<br>
					</div>";
					$this->echoHTML($HTMLbody);
	}

	public function showAddPlayertoTeam(TeamList $teamlist, ApiPlayerList $playerlist)
	{
		

		$contentString = 
					 "
					<form method=post >
						<fieldset class='fieldaddevent'>
							<legend>Add player to team</legend>
							$this->message
							<span style='white-space: nowrap'>Player:</span>
							<select name='$this->dropdownpickplayer'>";
							 foreach($playerlist->toArray() as $player)
							 {
							 	$contentString.= "<option value='". $player->getName()."'>".$player->getName()."</option>";
							 }
							 
							 $contentString .= "</select>
							 <br>
							 <span style='white-space: nowrap'>Add to Team:</span>
							 <select name='$this->dropdownpickteam2'>";
							 foreach($teamlist->toArray() as $team)
							 {
							 	$contentString.= "<option value='". $team->getName()."'>".$team->getName()."</option>";
							 }
							 
							 $contentString .= "</select>
							 <br>
							<span style='white-space: nowrap'></span> <input type='submit' name='$this->addPlayerToTeamBtn'  value='Add'>
						</fieldset>
					</form>";
					$HTMLbody = "<div class='divaddevent'>
					<h1>Create new team</h1>
					<p><a href='?login'>Back</a></p>
					$contentString<br>
					</div>";
					$this->echoHTML($HTMLbody);
	
	}

	public function showPickedPoolTeams(PoolTeamList $teamlist, $pool)
	{
		
					
				
					 foreach($teamlist->toArray() as $team)
					 {
					 	$contentString = "<p><a href='?".$pool."/team/". $team->getName()."'>".$team->getName()."</p>";
					 }

					$HTMLbody = "<div class='divaddevent'>
					<h1>Pool ".$pool." </h1>
					<p><a href='./?login'>Back</a></p>
					$contentString<br>
					</div>";
					$this->echoHTML($HTMLbody);
	}

	public function showPickedTeamPlayers(TeamPlayerList $playerlist, $team)
	{				$split = explode('/', $_SERVER['REQUEST_URI']);
					$filter =preg_replace("/[^a-zA-Z0-9]/", "", $split[2]);
				
					 foreach($playerlist->toArray() as $player)
					 {
					 	$contentString = "<p>Name: ".$player->getName()." Team: ".$player->getTeam()." Points: ".$player->getPoints()." G: ".$player->getGoals()." A: ".$player->getAssists()."</p>";
					 }

					$HTMLbody = "<div class='divaddevent'>
					<h1>Team ".$team." </h1>
					<p><a href='./?pool/".$filter."'>Back</a></p>
					$contentString<br>
					</div>";
					$this->echoHTML($HTMLbody);
	}

	public function didUserPressCreatePoolBtn()
	{
		if(isset($_POST[$this->createPoolButton]))
		{
			return true;
		}
		else{
			return false;
		}
	}

	public function getPoolInput()
	{
		if(isset($_POST[$this->createPoolName]))
		{
			return $_POST[$this->createPoolName];
		}
		else{
			return false;
		}
	}

	public function didUserPressCreateTeamBtn()
	{
		if(isset($_POST[$this->createTeamButton]))
		{
			return true;
		}
		else{
			return false;
		}
	}

	public function getTeamInput()
	{
		if(isset($_POST[$this->createTeamName]))
		{
			return $_POST[$this->createTeamName];
		}
		else{
			return false;
		}
	}

	public function getTeamsPoolDropdownInput()
	{
		if(isset($_POST[$this->dropdownpickteam]))
		{
			return $_POST[$this->dropdownpickteam];
		}
		else{
			return false;
		}
	}

	public function getTeamDropdownInputAddPlayer()
	{
		if(isset($_POST[$this->dropdownpickteam2]))
		{
			return $_POST[$this->dropdownpickteam2];
		}
		else{
			return false;
		}
	}

	public function getPlayerDropdownInput()
	{
		if(isset($_POST[$this->dropdownpickplayer]))
		{
			return $_POST[$this->dropdownpickplayer];
		}
		return false;
	}

	public function didUserPressAddPlayerToTeamBtn()
	{
		if(isset($_POST[$this->addPlayerToTeamBtn]))
		{
			return true;
		}
		else{
			return false;
		}
	}

	public function didUserPressViewPool()
	{
		$split = explode('/', $_SERVER['REQUEST_URI']);
		$firstPart = $split[2];

		
		
		if($firstPart === "?pool")
		{	
			return end($split);
		}
		return false;
	}

	public function didUserPressViewTeam()
	{
		$split = explode('/', $_SERVER['REQUEST_URI']);
		
		if($split[3] === "team")
		{	
			return end($split);
		}
		return false;
	}

	public function didUserPressAddPlayerToTeam()
	{
		return isset($_GET[$this->addPlayerToTeam]);
	}	


	public function showMessage($message)
	{
		$this->message = "<p>" . $message . "</p>";
	}

	//Adding successmessage for adding pool.
	public function successfulAddPool()
	{
		$this->showMessage("Pool was created!");
	}

	public function successfulAddTeamToPool()
	{
		$this->showMessage("Teams was created and added to pool");
	}
	public function successfulAddPlayerToTeam()
	{
		$this->showMessage("Player was added to team");
	}
}