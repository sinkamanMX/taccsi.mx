<?php
/**
 * Archivo de definición de usuarios
 * 
 * @author EPENA
 * @package library.My.Models
 */

/**
 * Modelo de tabla: usuarios
 *
 * @package library.My.Models
 * @author EPENA
 */
class My_Model_Formapago extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'ADMIN_FORMA_PAGO';
	protected $_primary = 'ID_FORMA_PAGO';

	public function getCbo(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT $this->_primary AS ID, DESCRIPCION AS NAME 
    			FROM $this->_name 
    			WHERE ESTATUS = 1
    			ORDER BY DESCRIPCION ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
		
        
		return $result;			
	}
	   
}	