<?php

	require_once("ApiPlayer.php");

	class ApiPlayerList{

		private $m_players;

		public function __construct()
		{
			$this->m_players = array();
		}

		public function add(ApiPlayer $apiplayer)
		{
			$this->m_players[] = $apiplayer;
		}

		public function toArray()
		{
			return $this->m_players;
		}

	}