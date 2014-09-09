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
class My_Model_Taxis extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'ADMIN_TAXIS';
	protected $_primary = 'ID_TAXI';

	public function getTaxiService($data){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT B.ID_USUARIO,
                   B.NOMBRE,
                   B.APATERNO,
                   B.AMATERNO,
                   C.PLACAS,
                   'taxi_libre' AS ESTATUS,
                   A.LATITUD,
                   A.LONGITUD,
                   ROUND(DISTANCIA(A.LATITUD,A.LONGITUD,".$data['inputLatOrigen'].",".$data['inputLonOrigen']."),2) AS DIST,
                   C.IMAGEN,
                   IF(B.RATING IS NULL,0,B.RATING) AS RATING,
                   IF(B.VIAJES IS NULL,0,B.VIAJES) AS VIAJES
            FROM DISP_ULTIMA_POSICION A
              INNER JOIN ADMIN_USUARIOS B ON B.ID_USUARIO = A.ID_USUARIO
              INNER JOIN ADMIN_TAXIS C ON C.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO 
            WHERE CAST(A.FECHA_SERVER AS DATE) = CURRENT_DATE AND
				B.DISPONIBLE = 0 
            	ORDER BY DIST DESC LIMIT 5";

		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
		
		return $result;			
	}
	
	public function insertRelTaxis($data){
        $result = false;
        
        $sql="INSERT INTO VIAJES_ASIGNACIONES 
	        	SET ID_VIAJE		= ".$data['idViaje'].",
              		ID_TACCSISTA	= ".$data['idTaccsi'].",
              		FECHA			= CURRENT_DATE,
              		HORA			= CURRENT_TIME";
        try{            
    		$query   = $this->query($sql,false);    	
			if($query){
				$result  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;
	}	
	   
}	