<?php

require_once("Team.php");

	class TeamList{


			private $m_teams;
			//adding private variable m_teams to an array.
			public function __construct()
			{
				$this->m_teams = array();
			}
			//adding team to the array. 
			public function add(Team $team)
			{
				$this->m_teams[] = $team;
			}
			//returning the array.
			public function toArray()
			{
				return $this->m_teams;
			}

	}