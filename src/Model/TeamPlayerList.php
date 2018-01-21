<?php

	require_once("TeamPlayer.php");

	class TeamPlayerList{

			private $m_teams;
			//adding private variable m_teams to an array.
			public function __construct()
			{
				$this->m_teams = array();
			}
			//adding poolteam to the array. 
			public function add(TeamPlayer $player)
			{
				$this->m_teams[] = $player;
			}
			//returning the array.
			public function toArray()
			{
				return $this->m_teams;
			}
	}