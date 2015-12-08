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
class My_Model_Reservaciones extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'ADMIN_RESERVACIONES';
	protected $_primary = 'ID_RESERVACION';
	
    public function insertNewRow($data){
        $result     = Array();
        $result['status']  = false;
        
		$idToInsert = $this->insertGenId();
        
        $dFpago	 = ($data['inputTarjeta']=='-1') ? '1': '2';
        $dLat    = ($data['inputLatOrigen']=='') ? '0.000000': $data['inputLatOrigen'] ;
        $dLon	 = ($data['inputLonOrigen']=='') ? '0.000000': $data['inputLonOrigen'] ;
        $iDistan = ($data['inputDistancia']=='') ? '0': $data['inputDistancia'];
        
        $sql="INSERT INTO $this->_name			 
				SET ID_RESERVACION  = ".$idToInsert.",
              		ID_FORMA_PAGO	= ".$dFpago.",
              		FECHA_RESERVACION='".$data['inputFechaViaje']."',
              		ORIGEN			='".$data['inputOrigen']."',
              		ORIGEN_REFERENCIAS='".$data['inputRefsO']."',
              		DESTINO			='".$data['inputDestino']."',
              		ORIGEN_LATITUD	= ".$data['inputLatOrigen'].",
              		ORIGEN_LONGITUD	= ".$data['inputLonOrigen'].",
              		DESTINO_LATITUD	= ".$dLat.",
              		DESTINO_LONGITUD= ".$dLon.",
              		ID_CLIENTE		= ".$data['strClient'].",            		
              		SOLICITADO_DESDE= 'W',
              		ID_TIPO_TAXI	= ".$data['inputSize'].",
              		/*
              		AC 				= ".@$data['inAc'].",
              		WIFI 			= ".@$data['inWifi'].",
              		IAVE 			= ".@$data['inIave'].",
              		CARGADOR        = ".@$data['inCargdor'].",
              		*/
              		CREADO			= CURRENT_TIMESTAMP,
              		ID_ESTATUS_RESERVACION  = 0";
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
    
    public function insertServices($idViaje,$idServicio){
        $result     = Array();
        $result['status']  = false;
        
        $idToInsert = $this->insertGenId();
        $codigo 	= rand(1,9999);
        
        $sql="INSERT INTO ADMIN_RESERVACIONES_SERVICIOS
				SET ID_RESERVACION    = ".$idViaje.",
                	ID_SERVICIO 	  = ".$idServicio;
        try{            
    		$query   = $this->query($sql,false);    		
			if(count($query)>0){				
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
    }   

    public function insertGenId(){
        $result     = 0;
        
        $sql="INSERT INTO ADMIN_RESERV_GEN
				 SET ID_RESERVACION = NULL";
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

  	public function  validaSinReservaciones($id_cliente,$fecha_reservacion){
	    $res = false;
	    $sql = "SELECT COUNT(ID_RESERVACION) AS TOTAL
	            FROM ADMIN_RESERVACIONES
	            WHERE ID_CLIENTE = ".$id_cliente."
	             AND  ID_ESTATUS_RESERVACION = 0
	             AND  FECHA_RESERVACION BETWEEN (INTERVAL -1 HOUR + '".$fecha_reservacion."')
	                                    AND (INTERVAL 1 HOUR + '".$fecha_reservacion."')";
		$query   = $this->query($sql);
		//Zend_Debug::dump($query[0]['TOTAL']);	  
		if($query[0]['TOTAL']=='0'){
			$res = true;	
		}			
	    //Zend_Debug::dump($res);
	    return $res;
  	}      
}