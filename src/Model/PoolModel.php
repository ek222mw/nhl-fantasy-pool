<?php


class PoolModel{
	private $db;
	
	public function __construct(){
		$this->db = new Database();
	}

	//Kontrollerar längden på det inmatade poolvärdet. Om giltigt returnera sant annars kasta undantag.
	public function CheckLength($input){
		if(mb_strlen($input) < 1 || mb_strlen($input) > 50 ){
			// Kasta undantag.
			throw new Exception("Input must be between 1 and 50 characters long");
			
			
		}
		return true;
		
	}

}