<?php

	require_once("Pool.php");

	class PoolList{

			private $m_pools;
			//adding private variable m_pools to an array.
			public function __construct()
			{
				$this->m_pools = array();
			}
			//adding pool to the array. 
			public function add(Pool $pool)
			{
				$this->m_pools[] = $pool;
			}
			//returning the array.
			public function toArray()
			{
				return $this->m_pools;
			}
	}