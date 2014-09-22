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
class My_Model_Viajes extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'ADMIN_VIAJES';
	protected $_primary = 'ID_VIAJES';
	
	public function getResumen(){
		$result= Array();
    	$sql ="SELECT *
                FROM ".$this->_name." ORDER BY SRV_ESTATUS_ID_SRV_ESTATUS";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		
	}
	public function getViajes($status=null){
		$filter = ($status!=null) ? " WHERE SRV_ESTATUS_ID_SRV_ESTATUS=".$status: "";
		$result= Array();
    	$sql ="SELECT *
                FROM ".$this->_name.$filter;			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;			
	}
	
	public function infoViaje($idviaje){
		$result= Array();
    	$sql ="SELECT  ADMIN_VIAJES.*, CONCAT(ADMIN_USUARIOS.NOMBRE,' ',ADMIN_USUARIOS.APATERNO,' ',ADMIN_USUARIOS.AMATERNO) AS TAXISTA,
				CONCAT('Marca :',ADMIN_TAXIS.MARCA,'<br/> Modelo :',ADMIN_TAXIS.MODELO,'<br/>Placas: ',ADMIN_TAXIS.PLACAS,'<br/> Eco.:',ADMIN_TAXIS.ECO) AS TAXI,
				SRV_ESTATUS.ESTATUS, TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP,FECHA_VIAJE)) AS SEG_DIF
    			FROM ADMIN_VIAJES
    			INNER JOIN SRV_ESTATUS ON ADMIN_VIAJES.ID_SRV_ESTATUS  = SRV_ESTATUS.ID_ADMIN_ESTATUS
     			 LEFT JOIN ADMIN_USUARIOS ON ADMIN_VIAJES.ID_TAXISTA   = ADMIN_USUARIOS.ID_USUARIO    			
     			 LEFT JOIN ADMIN_TAXIS    ON ADMIN_USUARIOS.ID_USUARIO = ADMIN_TAXIS.ADMIN_USUARIOS_ID_USUARIO    			
    			WHERE ".$this->_primary." = ".$idviaje."
    			LIMIT 1";   	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];			
		}	
		return $result;
	}
	
    public function insertGenId(){
        $result     = 0;
        
        $sql="INSERT INTO ADMIN_VIAJES_GEN
			SET ID_VIAJE = NULL";
        try{            
    		$query   = $this->query($sql,false);
    		$sql_id ="SELECT LAST_INSERT_ID() AS ID_LAST;";
			$query_id   = $this->query($sql_id);
			if(count($query_id)>0){
				$result	   = $query_id[0]['ID_LAST'];			
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
    }	
	
    public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        
        $idToInsert = $this->insertGenId();
        
        $sql="INSERT INTO $this->_name			 
				SET ID_VIAJES 		= ".$idToInsert.",
              		ID_FORMA_PAGO	= ".$data['inputFormaPago'].",
              		FECHA_VIAJE		='".$data['inputFechaViaje']."',
              		NO_PASAJEROS	= ".$data['inputNoPasajeros'].",
              		ORIGEN			='".$data['inputOrigen']."',
              		DESTINO			='".$data['inputDestino']."',
              		ORIGEN_LATITUD	= ".$data['inputLatOrigen'].",
              		ORIGEN_LONGITUD	= ".$data['inputLonOrigen'].",
              		DESTINO_LATITUD	= ".$data['inputLatDestino'].",
              		DESTINO_LONGITUD= ".$data['inputLonDestino'].",
              		ID_CLIENTE		= ".$data['strClient'].",
              		USUARIO_REGISTRO= ".$data['userRegistro'].",
              		ID_SRV_ESTATUS  =1";
        try{            
    		$query   = $this->query($sql,false);
    		$sql_id ="SELECT LAST_INSERT_ID() AS ID_LAST;";
			$query_id   = $this->query($sql_id);
			if(count($query_id)>0){
				$result['id']	   = $idToInsert;
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
    }
    
    public function deleteAssign($idViaje){
		$result=false;
    	$sql ="DELETE 
    			FROM VIAJES_ASIGNACIONES
    			WHERE ID_VIAJE = ".$idViaje;
		$query   = $this->query($sql,false);
		$result	 =true;			

		return $result;    	
    }
    
    public function resetViaje($idViaje){
        $result     = Array();
        $result['status']  = false;
                
        $sql="UPDATE $this->_name		
        		SET FECHA_VIAJE		= CURRENT_TIMESTAMP,
        			ID_SRV_ESTATUS  = 1
        	WHERE 	ID_VIAJES 		= ".$idViaje; 
        try{            
    		$query   = $this->query($sql,false);    					
			$result['status']  = true;
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	    	
    }
}	