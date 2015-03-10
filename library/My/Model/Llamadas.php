<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Llamadas extends My_Db_Table
{
    protected $_schema 	= 'SIMA';
	protected $_name 	= 'OPERADORES_ACTIVOS';
	protected $_primary = 'ID_OPERADOR_ACTIVO';
			
    public function validateStatus($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT  *
                FROM $this->_name
                WHERE ID_USUARIO = $idObject LIMIT 1";	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];
		}	
        
		return $result;	    	
    }	
}