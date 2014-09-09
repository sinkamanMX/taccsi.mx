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
class My_Model_Usuarios extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'ADMIN_USUARIOS';
	protected $_primary = 'ID_USUARIO';
	
	public function validateUser($datauser){
		$result= Array();
    	$sql ="SELECT *
                FROM ".$this->_name."  
                WHERE NICKNAME   = '".$datauser['usuario']."'
                 AND PASSWORD  = SHA1('".$datauser['contrasena']."')";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];			
		}	
        
		return $result;			
	} 	
    
    public function getDataUser($datauser){
		try{
			$result= Array();
	    	$sql ="SELECT *
	                FROM ADMIN_USUARIOS u
					INNER JOIN ADMIN_TIPO_USUARIOS tu ON u.TIPO_USUARIO = tu.ID_TIPO_USUARIO
	                WHERE u.ID_USUARIO = $datauser";
			$query   = $this->query($sql);
			if(count($query)>0){
				$result	 = $query[0];			
			}	
	        
			return $result;	   			
			
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }                    
    }
      
    /*
	public function userExist($datauser){
		$result= Array();
    	$sql ="SELECT  *
                FROM ".$this->_name." 
                WHERE PASSWORD = SHA1('".$datauser['usuario']."') LIMIT 1";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];			
		}	
        
		return $result;			
	} 
    
	public function getAllData($status=null){
		$filter = ($status!=null) ? "WHERE ESTATUS=".$status: "";
		$result= Array();
    	$sql ="SELECT *
                FROM ".$this->_name.$filter;			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;			
	}   */ 
}