<?php


class PoolTeam{

		private $m_name;
		private $m_id;

		public function __construct($name, $id)
		{
			$this->m_name = $name;
			$this->m_id = $id;
		}

		public function getName()
		{
			return $this->m_name;
		}

		public function getId()
		{
			return $this->m_id;
		}

}