<?php

	require_once("PoolTeam.php");

	class PoolTeamList{

			private $m_pools;
			//adding private variable m_pools to an array.
			public function __construct()
			{
				$this->m_pools = array();
			}
			//adding poolteam to the array. 
			public function add(PoolTeam $pool)
			{
				$this->m_pools[] = $pool;
			}
			//returning the array.
			public function toArray()
			{
				return $this->m_pools;
			}
	}