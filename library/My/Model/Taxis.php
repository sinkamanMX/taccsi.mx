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

	public function getTaxiService($data,$iLimit=0){
		$result= Array();
		$this->query("SET NAMES utf8",false); 	
		$sFilter = ($iLimit==0) ? ' LIMIT 5' : ' ';	
    	$sql ="SELECT B.ID_USUARIO,
                   B.NOMBRE,
                   B.APATERNO,
                   B.AMATERNO,
                   C.PLACAS,
                   'taxi_libre' AS ESTATUS,
                   A.LATITUD,
                   A.LONGITUD,
                   /*ROUND(DISTANCIA(A.LATITUD,A.LONGITUD,".$data['inputLatOrigen'].",".$data['inputLonOrigen']."),2) AS DIST,*/
                   5 AS DIST,
                   C.IMAGEN,
                   IF(B.RATING IS NULL,0,B.RATING) AS RATING,
                   IF(B.VIAJES IS NULL,0,B.VIAJES) AS VIAJES
            FROM DISP_ULTIMA_POSICION A
              INNER JOIN ADMIN_USUARIOS B ON B.ID_USUARIO = A.ID_USUARIO
              INNER JOIN ADMIN_TAXIS C ON C.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO 
            WHERE CAST(A.FECHA_SERVER AS DATE) = CURRENT_DATE AND
				B.DISPONIBLE = 0 
            	ORDER BY DIST DESC ".$sFilter;
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
	
	public function getPositionTaxi($idTaxi){
		$result= Array();
    	$sql ="SELECT LATITUD,LONGITUD,FECHA_GPS, PROVEEDOR
				FROM DISP_ULTIMA_POSICION
				WHERE ID_USUARIO = ".$idTaxi."
				LIMIT 1";	         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];
		}
		        
		return $result;				
	}
	
	public function getDataNoAssign($idEmpresa){
		$result= Array();
    	$sql ="SELECT t.*, M.DESCRIPCION AS MODELO_NAME, A.DESCRIPCION AS MARCA_NAME
				FROM ADMIN_TAXIS t
				LEFT JOIN ADMIN_USUARIOS U ON t.ADMIN_USUARIOS_ID_USUARIO = U.ID_USUARIO
				INNER JOIN ADMIN_MODELO  M ON t.ID_MODELO  = M.ID_MODELO
				INNER JOIN ADMIN_MARCA   A ON M.ID_MARCA   = A.ID_MARCA
				WHERE t.ID_EMPRESA = $idEmpresa
				AND  t.ADMIN_USUARIOS_ID_USUARIO IS NULL
				ORDER BY PLACAS ASC				 
				 ";	         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;
		}
		        
		return $result;			
	}
	
	public function setDriver($data){
        $result = false;
        
        $sql="UPDATE ADMIN_TAXIS 
	        	SET ADMIN_USUARIOS_ID_USUARIO = ".$data['catId'].",
              		NOMBRE_CHOFER	= '".$data['inputNombre']."  ".$data['inputApaterno']." ".$data['inputAmaterno']."'
              		WHERE ID_TAXI   = ".$data['inputTaxi']."
              		LIMIT 1";
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
	
	public function getDataTables($idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT t.*, M.DESCRIPCION AS MODELO_NAME, A.DESCRIPCION AS MARCA_NAME
				FROM ADMIN_TAXIS t
				LEFT JOIN ADMIN_USUARIOS U ON t.ADMIN_USUARIOS_ID_USUARIO = U.ID_USUARIO
				INNER JOIN ADMIN_MODELO  M ON t.ID_MODELO  = M.ID_MODELO
				INNER JOIN ADMIN_MARCA   A ON M.ID_MARCA   = A.ID_MARCA
				WHERE t.ID_EMPRESA = $idEmpresa
				ORDER BY PLACAS ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	        
		return $result;			
	}  

    
    public function getData($idObject){
		try{
			$result= Array();
	    	$sql ="SELECT *
				FROM ADMIN_TAXIS t
				INNER JOIN ADMIN_MODELO  M ON t.ID_MODELO  = M.ID_MODELO
				INNER JOIN ADMIN_MARCA   A ON M.ID_MARCA   = A.ID_MARCA
				WHERE t.ID_TAXI = $idObject
				LIMIT 1";
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
    
    public function getDataByDriver($idObject){
		try{
			$result= Array();
	    	$sql ="SELECT t.*,CONCAT(t.ECO,' ',t.PLACAS) AS N_TAXI, CONCAT(M.DESCRIPCION,'-',A.DESCRIPCION) AS N_MODELO,A.ID_MARCA,
	    		t.ID_MODELO,t.ID_COLOR
				FROM ADMIN_TAXIS t
				INNER JOIN ADMIN_MODELO  M ON t.ID_MODELO  = M.ID_MODELO
				INNER JOIN ADMIN_MARCA   A ON M.ID_MARCA   = A.ID_MARCA
				WHERE t.ADMIN_USUARIOS_ID_USUARIO = $idObject
				LIMIT 1";
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
    
    public function insertRow($data,$idTaccista=NULL){
        $result     = Array();
        $result['status']  = false;
        
        $nameImage  = ($data['nameImagen']!="") ? ",IMAGEN			='".$data['nameImagen']."'": "";
        $iTaccista  = ($idTaccista!=NULL) ? "ADMIN_USUARIOS_ID_USUARIO =".$idTaccista."," : "" ;
        
        $sql="INSERT INTO $this->_name	
        		SET ID_EMPRESA		= ".$data['dataIdEmpresa'].",
					ID_MODELO		= ".$data['inputModelo'].",
					ID_ESTATUS_TAXI	= ".$data['inputEstatus'].",
					ID_COLOR		= ".$data['inputColor'].",
					NOMBRE_CHOFER	='".$data['inputChofer']."',
					PLACAS			='".$data['inputPlacas']."',
					ECO				='".$data['inputEco']."',
					ANIO			= ".$data['inputAno'].",
					$iTaccista
					FECHA_REGISTRO	= CURRENT_TIMESTAMP,
					USUARIO_REGISTRO= ".$data['userCreate']."
					$nameImage";
        try{            
    		$query   = $this->query($sql,false);
    		$sql_id ="SELECT LAST_INSERT_ID() AS ID_LAST;";
			$query_id   = $this->query($sql_id);
			if(count($query_id)>0){
				$result['id']  = $query_id[0]['ID_LAST'];  			 	
				$result['status']  = true;	
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
    }    
    
    public function updateRow($data){
       	$result     = Array();
        $result['status']  = false;

        $nameImage  = ($data['nameImagen']!="") ? ",IMAGEN			='".$data['nameImagen']."'": "";

        $sql="UPDATE $this->_name	
        		SET ID_MODELO		= ".$data['inputModelo'].",
					ID_ESTATUS_TAXI	= ".$data['inputEstatus'].",
					ID_COLOR		= ".$data['inputColor'].",
					NOMBRE_CHOFER	='".$data['inputChofer']."',
					PLACAS			='".$data['inputPlacas']."',
					ECO				='".$data['inputEco']."',
					ANIO			= ".$data['inputAno']."
					$nameImage
			  WHERE $this->_primary = ".$data['catId']." LIMIT 1";
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				$result['status']  = true;								
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;
    }       

    public function deleteRow($data){
		$result = false;    	
    	try{    	
			$sqlInsert="DELETE FROM $this->_name
        			WHERE $this->_primary = ".$data['catId']." LIMIT 1";
    		$queryInsert   = $this->query($sqlInsert,false);				
			if($queryInsert){
				$result = true;	
			}					
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;    	
    } 

    public function updateEmpresa($idEmpresa,$idTaxi){
       	$result     = Array();
        $result['status']  = false;

        $sql="UPDATE $this->_name	
        		SET ID_EMPRESA      = $idEmpresa       			
			  WHERE $this->_primary = ".$idTaxi." LIMIT 1";
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				$result['status']  = true;								
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;
    }     

    public function getLastPosition($idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT CONCAT(U.NOMBRE,' ', U.APATERNO, ' ',U.AMATERNO) AS CHOFER, T.PLACAS AS TAXI, 
    				P.UBICACION, P.FECHA_GPS, IF(V.ID_VIAJEs IS NULL,'Sin viaje','En Viaje')  AS ESTADO, P.LATITUD,
    			 	P.LONGITUD, P.ANGULO, P.VELOCIDAD
				FROM DISP_ULTIMA_POSICION P
				INNER JOIN ADMIN_USUARIOS U ON P.IDENTIFICADOR = U.DISPOSITIVO
				INNER JOIN ADMIN_TAXIS    T ON U.ID_USUARIO    = T.ADMIN_USUARIOS_ID_USUARIO
				 LEFT JOIN ADMIN_VIAJES   V ON U.ID_USUARIO    = V.ID_TAXISTA AND V.ID_SRV_ESTATUS IN(2,5) 
				WHERE P.IDENTIFICADOR IN (
					SELECT DISPOSITIVO
					FROM ADMIN_USUARIOS
					WHERE ID_EMPRESA = $idEmpresa
					  AND DISPOSITIVO IS NOT NULL
				)
				GROUP BY P.IDENTIFICADOR
				ORDER BY P.FECHA_GPS";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	        
		return $result;	    	
    }
    
    public function getLastPositionByTaccsi($idTaccista){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT CONCAT(U.NOMBRE,' ', U.APATERNO, ' ',U.AMATERNO) AS CHOFER, T.PLACAS AS TAXI, 
    				P.UBICACION, P.FECHA_GPS, IF(V.ID_VIAJEs IS NULL,'Sin viaje','En Viaje')  AS ESTADO, P.LATITUD,
    			 	P.LONGITUD, P.ANGULO, P.VELOCIDAD
				FROM DISP_ULTIMA_POSICION P
				INNER JOIN ADMIN_USUARIOS U ON P.IDENTIFICADOR = U.DISPOSITIVO
				INNER JOIN ADMIN_TAXIS    T ON U.ID_USUARIO    = T.ADMIN_USUARIOS_ID_USUARIO
				 LEFT JOIN ADMIN_VIAJES   V ON U.ID_USUARIO    = V.ID_TAXISTA AND V.ID_SRV_ESTATUS IN(2,5) 
				WHERE P.IDENTIFICADOR IN (
					SELECT DISPOSITIVO
					FROM ADMIN_USUARIOS
					WHERE ID_USUARIO = $idTaccista
					  AND DISPOSITIVO IS NOT NULL
				)
				GROUP BY P.IDENTIFICADOR
				ORDER BY P.FECHA_GPS
				LIMIT 1";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	        
		return $result;	    	
    }   

	public function updateSolicitud($idTravel){
        $result = false;
        
        $sql="UPDATE VIAJES_ASIGNACIONES 
	        	SET FECHA			= CURRENT_DATE,
              		HORA			= CURRENT_TIME,
              		ACEPTO			= NULL
              WHERE ID_VIAJE		= ".$idTravel." LIMIT 1";
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				$result['status']  = true;								
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;
	}	    
}	