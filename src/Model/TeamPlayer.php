<?php


class TeamPlayer{

		private $m_name;
		private $m_id;
		private $m_team;
		private $m_points;
		private $m_goals;
		private $m_assists;

		public function __construct($name, $id, $team, $points, $goals, $assists)
		{
			$this->m_name = $name;
			$this->m_id = $id;
			$this->m_team = $team;
			$this->m_points = $points;
			$this->m_goals = $goals;
			$this->m_assists = $assists;
		}

		public function getName()
		{
			return $this->m_name;
		}

		public function getId()
		{
			return $this->m_id;
		}

		public function getTeam()
		{
			return $this->m_team;
		}

		public function getPoints()
		{
			return $this->m_points;
		}

		public function getGoals()
		{
			return $this->m_goals;
		}

		public function getAssists()
		{
			return $this->m_assists;
		}

}