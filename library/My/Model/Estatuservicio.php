<?php
/**
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Estatuservicio extends My_Db_Table
{
	protected $_schema 	= 'taccsi';
	protected $_name 	= 'SRV_ESTATUS';
	protected $_primary = 'ID_ADMIN_ESTATUS';
	
	public function getCbo(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT $this->_primary AS ID, ESTATUS AS NAME 
    			FROM $this->_name 
    			ORDER BY NAME ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}	
}