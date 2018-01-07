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

	public function showPickedPoolTeams(PoolTeamList $teamlist, $pool)
	{
		
					
				
					 foreach($teamlist->toArray() as $team)
					 {
					 	$contentString = "<p><a href='team/". $team->getName()."'>".$team->getName()."</p>";
					 }

					$HTMLbody = "<div class='divaddevent'>
					<h1>Pool ".$pool." </h1>
					<p><a href='./?login'>Back</a></p>
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
}