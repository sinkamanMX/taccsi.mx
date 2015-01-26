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
class My_Model_Clientes extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'SRV_USUARIOS';
	protected $_primary = 'ID_SRV_USUARIO';
	   
	public function getClients($data){
		$filter = "";
		
		if(isset($data['namefilter']) && $data['namefilter']!=""){
			$filter .= ($filter=="") ? " WHERE ": " OR ";
			$filter .= "NOMBRE LIKE '%".$data['namefilter']."%'";
		}
		
		if(isset($data['appfilter']) && $data['appfilter']!=""){
			$filter .= ($filter=="") ? " WHERE ": " OR ";
			$filter .= "APATERNO LIKE '%".$data['appfilter']."%'";
		} 
		
		if(isset($data['apmfilter']) && $data['apmfilter']!=""){
			$filter .= ($filter=="") ? " WHERE ": " OR ";
			$filter .= "AMATERNO LIKE '%".$data['apmfilter']."%'";
		} 

		if(isset($data['telfilter']) && $data['telfilter']!=""){
			$filter .= ($filter=="") ? " WHERE ": " OR ";
			$filter .= "TELEFONO LIKE '%".$data['telfilter']."%'";		
		} 		
		
		$result= Array();
		$this->query("SET NAMES utf8",false); 	
    	$sql ="SELECT NOMBRE AS NAME, APATERNO AS APP, AMATERNO AS APM, TELEFONO, ID_SRV_USUARIO
                FROM ".$this->_name." ".$filter; 
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}		
		return $result;			
	}
	
	public function getDataClient($idClient=0){
		try{
			$result= Array();
			$sql = "SELECT *
					FROM ".$this->_name."
					WHERE ".$this->_name.".".$this->_primary." = $idClient";
			/*
	    	$sql ="SELECT ".$this->_name.".*, SRV_USUARIOS.USUARIO,SRV_USUARIOS.VIAJES, SRV_USUARIOS.TELEFONO, SRV_USUARIOS.EMAIL
	                FROM ".$this->_name."
	               	 LEFT JOIN SRV_USUARIOS ON ".$this->_name.".ID_SRV_USUARIO = SRV_USUARIOS.ID_SRV_USUARIO   
	                WHERE ".$this->_name.".".$this->_primary." = $idClient";	*/
	    		         	
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
	
    public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO $this->_name	
        			SET NOMBRE			= '".$data['inputNombre']."',
        				APATERNO		= '".$data['inputApaterno']."', 
        				AMATERNO		= '".$data['inputAmaterno']."', 
        				TELEFONO		= '".$data['inputPhone']."', 
        				EMAIL			= '".$data['inputEmail']."', 
        				FECHA_CREADO 	=  CURRENT_TIMESTAMP";
        try{
    		$query   = $this->query($sql,false);
    		$sql_id ="SELECT LAST_INSERT_ID() AS ID_LAST;";
			$query_id   = $this->query($sql_id);
			if(count($query_id)>0){	 	
				$result['id']	   = $query_id[0]['ID_LAST'];
				$result['status']  = true;	
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
    }
    
	public function getViajesClient($idClient=0){
		try{
			$result= Array();
	    	$sql ="SELECT V.ID_VIAJES, E.ESTATUS, V.FECHA_VIAJE, CONCAT(U.`NOMBRE`,' ',U.`APATERNO`,' ',U.`AMATERNO`) AS TAXISTA, T.PLACAS
					FROM ADMIN_VIAJES V
					INNER JOIN SRV_ESTATUS    E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS
					 LEFT JOIN ADMIN_USUARIOS U ON V.ID_TAXISTA     = U.ID_USUARIO
					 LEFT JOIN ADMIN_TAXIS    T ON U.ID_USUARIO     = T.ADMIN_USUARIOS_ID_USUARIO
					WHERE V.ID_CLIENTE = $idClient 
	                ORDER BY V.ID_VIAJES DESC";	         	
			$query   = $this->query($sql);
			if(count($query)>0){
				$result	 = $query;			
			}	

			return $result;	   			
			
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }		
	}
}