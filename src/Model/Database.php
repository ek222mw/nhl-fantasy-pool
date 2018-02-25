<?php
	
	require_once("TeamList.php");
	require_once("PoolList.php");
	require_once("PoolTeamList.php");
	require_once("TeamPlayerList.php");
	require_once("ApiPlayerList.php");

class Database{


		//Databasuppgifter för databasen.
		protected $dbUsername = "root";
		protected $dbPassword = "";
		protected $dbConnstring = 'mysql:host=127.0.0.1;dbname=pool';
		protected $dbConnection;
		protected $dbTable = "";
		//privata statiska variabler som används för att undvika strängberoenden i metoderna.
		private static $event = "event";
		private static $band = "band";
		private static $name = "name";
		private static $id = "id";
		private static $points = "points";
		private static $goals = "goals";
		private static $assists = "assists";
		private static $position = "position";
		private static $fkpoolname = "fk_poolname";
		private static $fkteam ="fk_team";
		private static $grade = "grade";
		private static $eventband = "eventband";
		private static $username = "username";
		private static $password = "password";
		private static $team = "team";
		private static $tblUser = "users";
		private static $tblEvent = "event";
		private static $tblPools = "pools";
		private static $tblBand = "band";
		private static $tblEventBand = "eventband";
		private static $tblSummaryGrade = "summarygrade";
		private static $tblTeams = "teams";
		private static $tblApiPlayers = "apiplayers";
		private static $tblPlayers = "players";
		private static $colId = "id";
		private static $colusername = "username";
		private static $colevent = "event";
		private static $colband = "band";
		private static $colgrade = "grade";
		private static $colpassword = "password";
		private static $colrating = "rating";
		private static $ID = "ID";
	//returnerar anslutningssträngen.
		protected function connection() 
		{
			if ($this->dbConnection == NULL)
					$this->dbConnection = new \PDO($this->dbConnstring, $this->dbUsername, $this->dbPassword);
			
			$this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				
			return $this->dbConnection;
		}
		
		//Kontrollerar om användarnamnet är upptaget, returnerar true om det inte är upptaget. Annars kastas undantag.
		public function ReadSpecifik($inputuser)
		{
			
			$db = $this -> connection();
			$this->dbTable = self::$tblUser;
			$sql = "SELECT ". self::$username ." FROM `$this->dbTable` WHERE ". self::$username ." = ?";
			$params = array($inputuser);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			$result = $query -> fetch();
			
			
			if ($result[self::$colusername] !== null) {
				
				throw new Exception("Användarnamnet är redan upptaget");
			}else{
				return true;
			}
			
		
		}	
		//Hämtar och returnerar användarnamnet från databasen.
		public function getDBUserInput($inputUser)
		{
			$db = $this -> connection();
			$this->dbTable = self::$tblUser;
			$sql = "SELECT ". self::$username ." FROM `$this->dbTable` WHERE ". self::$username ." = ?";
			$params = array($inputUser);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			$result = $query -> fetch();
			
			
			if ($result) {
				
				return $result[self::$colusername];
				
			}
		}
		//Hämtar och returnerar lösenordet från databasen.
		public function getDBPassInput($inputPassword)
		{
			$db = $this -> connection();
			$this->dbTable = self::$tblUser;
			$sql = "SELECT ". self::$password ." FROM `$this->dbTable` WHERE ". self::$password ." = ?";
			$params = array($inputPassword);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			$result = $query -> fetch();
			
			
			if ($result) {
				
				return $result[self::$colpassword];
				
			}
		}
		//Kontrollerar om livespelningen redan finns.Om inte så returneras true annars kastas undantag.
		public function checkIfPoolExist($input)
		{
			
				$db = $this -> connection();
				$this->dbTable = self::$tblPools;
				$sql = "SELECT ". self::$name ." FROM `$this->dbTable` WHERE ". self::$name ." = ?";
				$params = array($input);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
				
				
				
				if ($result[self::$name] !== null) {
					throw new Exception("Poolname already exists");
				}else{
					return true;
				}
			
		
		}
		//Kontrollerar om bandet redan finns.Om inte så returneras true annars kastas undantag.
		public function checkIfBandExist($inputband)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblBand;
				$sql = "SELECT ". self::$band ." FROM `$this->dbTable` WHERE ". self::$band ." = ?";
				$params = array($inputband);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
				
				
				
				if ($result[self::$colband] !== null) {
					throw new Exception("Bandet med det namnet är redan upptaget");
				}else{
					return true;
				}
		}
		//Kontrollerar om livespelningen redan innehåller inmatade bandet. Om inte så returneras true annars kastas undantag.
		public function checkIfRowExists($team, $pool)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblTeams;
				$sql = "SELECT ". self::$name .",". self::$fkpoolname ." FROM `".$this->dbTable."` WHERE ". self::$name ." = ? AND ". self::$fkpoolname ." = ?";
				$params = array($team,$pool);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
				
				
				
				if ($result[self::$name] !== null && $result[self::$fkpoolname] !== null ) {
					throw new Exception("Team already exists in that pool in database");
				}else{
					return true;
				}
		}
		//Kontrollerar om användaren redan satt betyg på den livespelningen med det bandet. Om inte så returneras true annars kastas undantag.
		public function checkIfGradeExistOnEventBandUser($eventdropdown,$banddropdown,$username)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblSummaryGrade;
				$sql = "SELECT ". self::$event .",". self::$band .",". self::$username ." FROM `".$this->dbTable."` WHERE ". self::$event ." = ? AND ". self::$band ." = ? AND ". self::$username ." = ?";
				$params = array($eventdropdown,$banddropdown,$username);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$event] !== null && $result[self::$colband] !== null && $result[self::$colusername] !== null ) {
					throw new Exception("Spelningen med det bandet och användarnamn har redan ett betyg");
				}else{
					return true;
				}
		}
		//Kontrollerar om id värde har manipulerats till något annat. Om inte så returneras true annars kastas undantag.
		public function checkIfIdManipulated($pickedid, $loggedinUser)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblSummaryGrade;
				$sql = "SELECT ". self::$id .",". self::$username ." FROM `".$this->dbTable."` WHERE ". self::$id ." = ? AND ". self::$username ." = ? ";
				$params = array($pickedid,$loggedinUser);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$colId] == null && $result[self::$colusername] == null ) {
					throw new Exception("Id till det betyget har inte rätt användarnamn");
				}else{
					return true;
				}
		}
		//Kontrollerar om livespelningen och/eller bandet har fått sina värden manipulerade. Om inte så returneras true annars kastas undantag.
		public function checkIfBandAndEventManipulated($pickedevent,$pickedband)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblEventBand;
				$sql = "SELECT ". self::$event .",". self::$band ." FROM `".$this->dbTable."` WHERE ". self::$event ." = ? AND ". self::$band ." = ? ";
				$params = array($pickedevent,$pickedband);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$colevent] == null && $result[self::$colband] == null ) {
					throw new Exception("Livespelning och/eller bandet existerar ej i kolumnen livespelning respektive band");
				}else{
					return true;
				}
		}
		//Kontrollerar om vald livespelnings värde har blivit manipulerad.Om inte så returneras true annars kastas undantag.
		public function checkIfPickEventManipulated($pickedevent)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblEventBand;
				$sql = "SELECT ". self::$event ." FROM `".$this->dbTable."` WHERE ". self::$event ." = ?";
				$params = array($pickedevent);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$colevent] == null) {
					throw new Exception("Livespelningen existerar ej i kolumnen");
				}else{
					return true;
				}
		}
		//Kontrollerar om betyget har fått sitt värde manipulerat. Om inte så returneras true annars kastas undantag.
		public function checkIfPickRatingManipulated($pickedrating)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblRating;
				$sql = "SELECT ". self::$rating ." FROM `".$this->dbTable."` WHERE ". self::$rating ." = ?";
				$params = array($pickedrating);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$colrating] == null) {
					throw new Exception("Betyg existerar ej i kolumnen");
				}else{
					return true;
				}
		}
		//Kontrollerar om livespelningen har fått sitt värde manipulerat i livespelningstabellen. Om inte så returneras true annars kastas undantag.
		public function checkIfPickPoolFromPoolTblIsManipulated($pickedpool)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblPools;
				$sql = "SELECT ". self::$name ." FROM `".$this->dbTable."` WHERE ". self::$name ." = ?";
				$params = array($pickedpool);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$name] == null) {
					throw new Exception("Pool input manipulated");
				}else{
					return true;
				}
		}
		//Kontrollerar om bandet har fått sitt värde manipulerat i bandtabellen. Om inte så returneras true annars kastas undantag.
		public function checkIfPickBandFromBandTableIsManipulated($pickedband)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblBand;
				$sql = "SELECT ". self::$band ." FROM `".$this->dbTable."` WHERE ". self::$band ." = ?";
				$params = array($pickedband);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$colband] == null) {
					throw new Exception("Bandet existerar ej i kolumnen");
				}else{
					return true;
				}
		}
		
		//Hämtar alla livespelningar från databasen och returnerar dessa.
		public function fetchAllEvents()
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblEvent;
				$sql = "SELECT * FROM `$this->dbTable`";
				
				$query = $db -> prepare($sql);
				$query -> execute();
				$result = $query -> fetchall();
				$events = new EventList();
				foreach ($result as $eventdb) {
					$event = new Event($eventdb[self::$event], $eventdb[self::$id]);
					$events->add($event);
				}
				return $events;
				
				
		}
		//Hämtar alla band från databasen och returnerar dessa.
		public function fetchAllBands()
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblBand;
				$sql = "SELECT * FROM `$this->dbTable`";
				
				$query = $db -> prepare($sql);
				$query -> execute();
				$result = $query -> fetchall();
				$bands = new BandList();
				foreach ($result as $banddb) {
					$band = new Band($banddb[self::$band], $banddb[self::$id]);
					$bands->add($band);
				}
				return $bands;
		}
		//Hämtar alla livespelningar med band från databasen och returner dessa.
		public function fetchAllEventWithBands()
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblEventBand;
				$sql = "SELECT * FROM `$this->dbTable` GROUP BY ". self::$event ."";
				
				$query = $db -> prepare($sql);
				$query -> execute();
				$result = $query -> fetchall();
				$eventbands = new EventBandList();
				foreach ($result as $eventbanddb) {
					$eventband = new EventBand($eventbanddb[self::$event], $eventbanddb[self::$id]);
					$eventbands->add($eventband);
				}
				return $eventbands;
		}
		//Hämtar alla band innehållandes livespelningar och returnerar dessa.
		public function fetchAllPools(){
				$db = $this -> connection();
				$this->dbTable = self::$tblPools;
				$sql = "SELECT * FROM `$this->dbTable`";
				
				$query = $db -> prepare($sql);
				$query -> execute();
				$result = $query -> fetchall();
				$pools = new PoolList();
				foreach ($result as $poolsdb) {
					$pool = new Pool($poolsdb[self::$name], $poolsdb[self::$id]);
					$pools->add($pool);
				}
				return $pools;
		}
		//Hämtar alla band tillhörandes vald livespelning och returnerar dessa.
		public function fetchChosenBandsInEventDropdown($eventdropdown)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblEventBand;
				$sql = "SELECT * FROM `$this->dbTable` WHERE ". self::$event ." = ? ";
				$params = array($eventdropdown);
				
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetchall();
				$eventbands = new EventBandList();
				foreach ($result as $eventbanddb) {
					$eventband = new EventBand($eventbanddb[self::$band], $eventbanddb[self::$id]);
					$eventbands->add($eventband);
				}
				return $eventbands;
		}
		//Filter teams on picked pool
		public function fetchChosenPoolTeams($pool)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblTeams;
				$sql = "SELECT * FROM `$this->dbTable` WHERE ". self::$fkpoolname ." = ? GROUP BY ". self::$name ." ";
				$params = array($pool);
				
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetchall();
				$poolTeams = new PoolTeamList();
				foreach ($result as $poolteamdb) {
					$poolTeam = new PoolTeam($poolteamdb[self::$name], $poolteamdb[self::$id]);
					$poolTeams->add($poolTeam);
				}

				return $poolTeams;
		}

		//Filter teams on picked pool
		public function fetchChosenTeamPlayers($team)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblPlayers;
				$sql = "SELECT * FROM `$this->dbTable` WHERE ". self::$fkteam ." = ? GROUP BY ". self::$name ." ";
				$params = array($team);
				
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetchall();
				$teamPlayers = new TeamPlayerList();
				foreach ($result as $teamplayerdb) {
					$teamPlayer = new TeamPlayer($teamplayerdb[self::$name], $teamplayerdb[self::$id], $teamplayerdb[self::$team], $teamplayerdb[self::$points], $teamplayerdb[self::$goals], $teamplayerdb[self::$assists]);
					$teamPlayers->add($teamPlayer);
				}

				return $teamPlayers;
		}
		//Hämtar alla betyg och returnerar dessa.
		public function fetchAllTeams()
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblTeams;
				$sql = "SELECT * FROM `$this->dbTable`";
				
				$query = $db -> prepare($sql);
				$query -> execute();
				$result = $query -> fetchall();
				$teams = new TeamList();
				foreach ($result as $teamdb) {
					$team = new Team($teamdb[self::$name], $teamdb[self::$id]);
					$teams->add($team);
				}
				return $teams;
		}

		public function fetchAllApiPlayers()
		{
			$db = $this->connection();
			$this->dbTable = self::$tblApiPlayers;
			$sql = "SELECT * FROM `$this->dbTable`";

			$query = $db->prepare($sql);
			$query->execute();
			$result = $query->fetchall();

			$players = new ApiPlayerList();

			foreach($result as $playerdb){
				$player = new ApiPlayer($playerdb[self::$name], $playerdb[self::$id], $playerdb[self::$team], $playerdb[self::$points], $playerdb[self::$goals], $playerdb[self::$assists], $playerdb[self::$position]);
				$players->add($player);
			}
			return $players;
		}
		//Hämtar alla band,id,livespelningar,betyg och användarnamn och returnerar dessa.
		public function fetchShowAllEvents()
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblSummaryGrade;
				$sql = "SELECT * FROM `$this->dbTable`";
				
				$query = $db -> prepare($sql);
				$query -> execute();
				$result = $query -> fetchall();
				
				
				$showevents = new ShowEventList();
				foreach ($result as $showeventdb) {
					$showevent = new ShowEvent($showeventdb[self::$band], $showeventdb[self::$id], $showeventdb[self::$event], $showeventdb[self::$grade],$showeventdb[self::$username]);
					$showevents->add($showevent);
				}
				return $showevents;
		}
		//Hämtar alla betyg satta av inloggade användaren och returnerar dessa.
		public function fetchEditGrades($loggedinUser)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblSummaryGrade;
				$sql = "SELECT * FROM `$this->dbTable` WHERE ". self::$username ." = ? ";
				$params = array($loggedinUser);
				
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetchall();
				
				
				$editgrades = new EditGradeList();
				foreach ($result as $editgradedb) {
					$editgrade = new EditGrade($editgradedb[self::$grade], $editgradedb[self::$id], $editgradedb[self::$event], $editgradedb[self::$band],$editgradedb[self::$username]);
					$editgrades->add($editgrade);
				}
				return $editgrades;
		}
		//Hämtar id till det betyg som inloggade användaren har valt att editera.Hämtar även livespelning,band och användarnamn. returnerar sedan samtliga poster.
		public function fetchIdPickedEditGrades($pickedid)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblSummaryGrade;
				$sql = "SELECT * FROM `$this->dbTable` WHERE ". self::$id ." = ? ";
				$params = array($pickedid);
				
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetchall();
				
				
				$editgrades = new EditGradeList();
				foreach ($result as $editgradedb) {
					$editgrade = new EditGrade($editgradedb[self::$grade], $editgradedb[self::$id], $editgradedb[self::$event], $editgradedb[self::$band],$editgradedb[self::$username]);
					$editgrades->add($editgrade);
				}
				return $editgrades;
		}
		//Hämtar alla betyg satta av inloggade användaren och returnerar dessa.
		public function fetchDeleteGradesWithSpecUser($loggedinUser)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblSummaryGrade;
				$sql = "SELECT * FROM `$this->dbTable` WHERE ". self::$username ." = ? ";
				$params = array($loggedinUser);
				
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetchall();
				
				
				$deletegrades = new DeleteGradeList();
				foreach ($result as $deletegradedb) {
					$deletegrade = new DeleteGrade($deletegradedb[self::$grade], $deletegradedb[self::$id], $deletegradedb[self::$event], $deletegradedb[self::$band],$deletegradedb[self::$username]);
					$deletegrades->add($deletegrade);
				}
				return $deletegrades;
		}
		//Lägger till användaren med användarnamn och lösenord och returnerar true för att sätta en variabel i LoginModel klassen.
		public function addUser($inputuser,$inputpassword) {
			try {
				$db = $this -> connection();
				$this->dbTable = self::$tblUser;
				$sql = "INSERT INTO $this->dbTable (". self::$username .",". self::$password  .") VALUES (?, ?)";
				$params = array($inputuser, $inputpassword);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				
				return true;
			} catch (\PDOException $e) {
				die('An unknown error have occured.');
			}
		}
		//Lägger till bandet.
		public function addBand($inputband)
		{
			try {
					$db = $this -> connection();
					$this->dbTable = self::$tblBand;
					$sql = "INSERT INTO $this->dbTable (".self::$band.") VALUES (?)";
					$params = array($inputband);
					$query = $db -> prepare($sql);
					$query -> execute($params);
					
				} catch (\PDOException $e) {
					die('An unknown error have occured.');
				}
		}
		//Lägger till bandet till livespelningen.
		public function addTeamToPool($team,$pool)
		{
				try {
					$db = $this -> connection();
					$this->dbTable = self::$tblTeams;
					$sql = "INSERT INTO $this->dbTable (".self::$name.",". self::$fkpoolname .") VALUES (?,?)";
					$params = array($team,$pool);
					$query = $db -> prepare($sql);
					$query -> execute($params);
					
				} catch (\PDOException $e) {
					die('An unknown error have occured.');
				}
		}
		//Lägger till betyg till livespelning med angivet band till den inloggade användaren.
		public function addGradeToEventBandWithUser($eventdropdown,$banddropdown,$gradedropdown,$username){
			try {
					$db = $this -> connection();
					$this->dbTable = self::$tblSummaryGrade;
					$sql = "INSERT INTO $this->dbTable (".self::$event.",". self::$band .",". self::$grade .", ". self::$username .") VALUES (?,?,?,?)";
					$params = array($eventdropdown,$banddropdown,$gradedropdown,$username);
					$query = $db -> prepare($sql);
					$query -> execute($params);
					
				} catch (\PDOException $e) {
					die('An unknown error have occured.');
				}
		}
		//lägger till livespelningen.
		public function addPool($input) {
				try {
					$db = $this -> connection();
					$this->dbTable = self::$tblPools;
					$sql = "INSERT INTO $this->dbTable (".self::$name.") VALUES (?)";
					$params = array($input);
					$query = $db -> prepare($sql);
					$query -> execute($params);
					
				} catch (\PDOException $e) {
					die('An unknown error have occured.');
				}
		}
		//Editerar betyget.
		public function EditGrades($inputgrade,$inputid)
		{
			try{
				
			$db = $this -> connection();
			$this->dbTable = self::$tblSummaryGrade;
			$sql = "UPDATE $this->dbTable SET ". self::$grade ."=? WHERE ". self::$id ."=?";
			$params = array($inputgrade,$inputid);
			$query = $db -> prepare($sql);
			$query -> execute($params);
					
			} catch (\PDOException $e) {
					die('An unknown error have occured.');
			}
        
		}
		//Tar bort betyget.
		public function DeleteGrades($inputid)
		{
			$db = $this -> connection();
			$this->dbTable = self::$tblSummaryGrade;
			$sql = "DELETE FROM $this->dbTable WHERE ". self::$id ." = ?";
			$params = array($inputid);
			$query = $db -> prepare($sql);
			$query -> execute($params);
		}



}