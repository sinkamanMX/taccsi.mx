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
				CONCAT('Marca :',A.DESCRIPCION,'<br/>Modelo :',M.DESCRIPCION,'<br/>Placas: ',ADMIN_TAXIS.PLACAS,'<br/>Eco.:',ADMIN_TAXIS.ECO) AS TAXI,
				SRV_ESTATUS.ESTATUS, TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP,FECHA_VIAJE)) AS SEG_DIF , P.DESCRIPCION AS FPAGO,
				ADMIN_VIAJES.MONTO_TAXISTA AS MONTO,ADMIN_VIAJES.ID_SRV_ESTATUS
    			FROM ADMIN_VIAJES
    			INNER JOIN SRV_ESTATUS ON ADMIN_VIAJES.ID_SRV_ESTATUS  = SRV_ESTATUS.ID_ADMIN_ESTATUS
				INNER JOIN ADMIN_FORMA_PAGO P ON ADMIN_VIAJES.ID_FORMA_PAGO = P.ID_FORMA_PAGO
     			 LEFT JOIN ADMIN_USUARIOS ON ADMIN_VIAJES.ID_TAXISTA   = ADMIN_USUARIOS.ID_USUARIO
     			 LEFT JOIN ADMIN_TAXIS    ON ADMIN_USUARIOS.ID_USUARIO = ADMIN_TAXIS.ADMIN_USUARIOS_ID_USUARIO
				 LEFT JOIN ADMIN_MODELO    M ON ADMIN_TAXIS.ID_MODELO  = M.ID_MODELO
				 LEFT JOIN ADMIN_MARCA    A ON M.ID_MARCA   = A.ID_MARCA    			
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
              		ORIGEN_REFERENCIAS='".$data['inputRefsO']."',
              		DESTINO			='".$data['inputDestino']."',
              		DESTINO_REFERENCIAS='".$data['inputRefsD']."',
              		ORIGEN_LATITUD	= ".$data['inputLatOrigen'].",
              		ORIGEN_LONGITUD	= ".$data['inputLonOrigen'].",
              		DESTINO_LATITUD	= ".$data['inputLatDestino'].",
              		DESTINO_LONGITUD= ".$data['inputLonDestino'].",
              		DISTANCIA_VIAJE = ".$data['inputDistancia'].",
              		TIEMPO_VIAJE	='".$data['inputTiempo']."',
              		ID_CLIENTE		= ".$data['strClient'].",
              		USUARIO_REGISTRO= ".$data['userRegistro'].",
              		ID_SRV_ESTATUS  = 1";
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
    
    public function resumeTotal($aFilter){
		$result= Array();
		$sFilter = ($aFilter['ID_ESTATUS']!=-99) ? "AND ID_SRV_ESTATUS = ".$aFilter['ID_ESTATUS'] : "";
		
    	$sql ="SELECT COUNT(*) AS TOTAL
				FROM ADMIN_VIAJES
				WHERE USUARIO_REGISTRO = ".$aFilter['ID_USER']."
				$sFilter
				AND FECHA_VIAJE BETWEEN '".$aFilter['FECHA_IN']."' AND '".$aFilter['FECHA_FIN']."'  				
				LIMIT 1";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0]['TOTAL'];			
		}
		        
		return $result;		    	
    } 
    
    public function getViajesResume($aFilter){
		$result= Array();
				
		$sFilter = ($aFilter['ID_ESTATUS']!=-99) ? "AND V.ID_SRV_ESTATUS = ".$aFilter['ID_ESTATUS'] : "";
		
    	$sql ="SELECT COUNT(V.ID_VIAJES) AS TOTAL, E.ESTATUS , E.COLOR
				FROM ADMIN_VIAJES V
				INNER JOIN SRV_ESTATUS E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS
				WHERE V.USUARIO_REGISTRO = ".$aFilter['ID_USER']."
				$sFilter
				AND V.FECHA_VIAJE BETWEEN '".$aFilter['FECHA_IN']."' AND '".$aFilter['FECHA_FIN']."'  
				GROUP BY V.ID_SRV_ESTATUS";		         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		    	
    }
    
    public function getTravelsResume($aFilter){
		$result= Array();
		
		$sFilter = ($aFilter['ID_ESTATUS']!=-99) ? "AND V.ID_SRV_ESTATUS = ".$aFilter['ID_ESTATUS'] : "";
		
    	$sql ="SELECT *, E.ESTATUS AS N_ESTATUS, CONCAT(C.`NOMBRE`,' ',C.APATERNO,' ',C.AMATERNO) AS N_CLIENTE
				FROM ADMIN_VIAJES V
				INNER JOIN SRV_ESTATUS  E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS
				INNER JOIN SRV_CLIENTES C ON V.ID_CLIENTE 	  = C.ID_CLIENTE
				WHERE V.USUARIO_REGISTRO = ".$aFilter['ID_USER']."
				$sFilter
				AND V.FECHA_VIAJE BETWEEN '".$aFilter['FECHA_IN']."' AND '".$aFilter['FECHA_FIN']."'				
				ORDER BY V.FECHA_VIAJE DESC";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		    	
    }  

    public function getViajesResumeEmp($aFilter){
		$result= Array();
				
		$sFilter = ($aFilter['ID_ESTATUS']!=-99) ? "AND V.ID_SRV_ESTATUS = ".$aFilter['ID_ESTATUS'] : "";
		
    	$sql ="SELECT COUNT(V.ID_VIAJES) AS TOTAL, E.ESTATUS , E.COLOR
				FROM ADMIN_VIAJES V
				INNER JOIN SRV_ESTATUS E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS
				INNER JOIN ADMIN_FORMA_PAGO P ON V.ID_FORMA_PAGO = P.ID_FORMA_PAGO
				INNER JOIN ADMIN_USUARIOS U ON V.ID_TAXISTA 	= U.ID_USUARIO
				INNER JOIN ADMIN_EMPRESAS M ON U.ID_EMPRESA     = M.ID_EMPRESA
				INNER JOIN ADMIN_TAXIS    T ON V.ID_TAXISTA     = T.ADMIN_USUARIOS_ID_USUARIO				
				WHERE U.ID_EMPRESA = ".$aFilter['ID_EMPRESA']."
				$sFilter
				AND V.FECHA_VIAJE BETWEEN '".$aFilter['FECHA_IN']."' AND '".$aFilter['FECHA_FIN']."'  
				GROUP BY V.ID_SRV_ESTATUS";						         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		    	
    }  

    public function resumeTotalEmp($aFilter){
		$result= Array();
		$sFilter = ($aFilter['ID_ESTATUS']!=-99) ? "AND ID_SRV_ESTATUS = ".$aFilter['ID_ESTATUS'] : "";
		
    	$sql ="SELECT COUNT(*) AS TOTAL
				FROM ADMIN_VIAJES V
				INNER JOIN SRV_ESTATUS E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS			
				INNER JOIN ADMIN_FORMA_PAGO P ON V.ID_FORMA_PAGO = P.ID_FORMA_PAGO
				INNER JOIN ADMIN_USUARIOS U ON V.ID_TAXISTA 	= U.ID_USUARIO
				INNER JOIN ADMIN_EMPRESAS M ON U.ID_EMPRESA     = M.ID_EMPRESA
				INNER JOIN ADMIN_TAXIS    T ON V.ID_TAXISTA     = T.ADMIN_USUARIOS_ID_USUARIO				
				WHERE U.ID_EMPRESA = ".$aFilter['ID_EMPRESA']."
				$sFilter
				AND FECHA_VIAJE BETWEEN '".$aFilter['FECHA_IN']."' AND '".$aFilter['FECHA_FIN']."'  
				ORDER BY FECHA_VIAJE DESC				
				LIMIT 1";		  	   			      	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0]['TOTAL'];			
		}
		        
		return $result;		    	
    }     
    
    public function getTravelsResumeEmp($aFilter){
		$result= Array();
		
		$sFilter = ($aFilter['ID_ESTATUS']!=-99) ? "AND V.ID_SRV_ESTATUS = ".$aFilter['ID_ESTATUS'] : "";
		
    	$sql ="SELECT *, E.ESTATUS AS N_ESTATUS, CONCAT(C.`NOMBRE`,' ',C.APATERNO,' ',C.AMATERNO) AS N_CLIENTE
				FROM ADMIN_VIAJES V
				INNER JOIN SRV_CLIENTES C ON V.ID_CLIENTE 	  = C.ID_CLIENTE
				INNER JOIN SRV_ESTATUS E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS			
				INNER JOIN ADMIN_FORMA_PAGO P ON V.ID_FORMA_PAGO = P.ID_FORMA_PAGO
				INNER JOIN ADMIN_USUARIOS U ON V.ID_TAXISTA 	= U.ID_USUARIO
				INNER JOIN ADMIN_EMPRESAS M ON U.ID_EMPRESA     = M.ID_EMPRESA
				INNER JOIN ADMIN_TAXIS    T ON V.ID_TAXISTA     = T.ADMIN_USUARIOS_ID_USUARIO				
				WHERE U.ID_EMPRESA = ".$aFilter['ID_EMPRESA']."				
				$sFilter
				AND V.FECHA_VIAJE BETWEEN '".$aFilter['FECHA_IN']."' AND '".$aFilter['FECHA_FIN']."'				
				ORDER BY V.FECHA_VIAJE DESC";     	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		    	
    }      
    
    public function getRecorrido($idViaje,$idEstatus=3){
		$result		= Array();
		$sFilter	= "";		
		if($idEstatus==5){
			$sFilter = "AND P.FECHA_GPS >=  (
					SELECT V.FECHA_VIAJE
					FROM ADMIN_VIAJES V
					WHERE V.ID_VIAJES = $idViaje
				)";	
		}
		if($idEstatus==3){
			$sFilter = "AND P.FECHA_GPS BETWEEN  (
					SELECT V.FECHA_VIAJE
					FROM ADMIN_VIAJES V
					WHERE V.ID_VIAJES = $idViaje
				)
				AND  (
					SELECT V.FIN_VIAJE_TAXISTA
					FROM ADMIN_VIAJES V
					WHERE V.ID_VIAJES = $idViaje
				)";				
		}else{
			$sFilter = "AND P.FECHA_GPS IS NULL";
		}
		
    	$sql ="SELECT P.FECHA_GPS AS FECHA, P.*
				FROM DISP_HISTORICO_POSICION P
				WHERE P.IDENTIFICADOR = ( 
					SELECT DISPOSITIVO
					FROM ADMIN_VIAJES V
					INNER JOIN ADMIN_USUARIOS U ON V.`ID_TAXISTA` = U.`ID_USUARIO`
					WHERE V.ID_VIAJES = $idViaje
				)
				$sFilter				
				ORDER BY P.FECHA_SERVER DESC";  	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;	 		   	
    }
    
    public function getViajesByTaxi($aFilter){
		$result= Array();
				
		$sFilter = ($aFilter['ID_ESTATUS']!=-99) ? "AND V.ID_SRV_ESTATUS = ".$aFilter['ID_ESTATUS'] : "";
		
    	$sql ="SELECT COUNT(V.ID_VIAJES) AS TOTAL, E.ESTATUS , E.COLOR
				FROM ADMIN_VIAJES V
				INNER JOIN SRV_ESTATUS E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS
				INNER JOIN ADMIN_FORMA_PAGO P ON V.ID_FORMA_PAGO = P.ID_FORMA_PAGO
				INNER JOIN ADMIN_USUARIOS U ON V.ID_TAXISTA 	= U.ID_USUARIO
				INNER JOIN ADMIN_EMPRESAS M ON U.ID_EMPRESA     = M.ID_EMPRESA
				INNER JOIN ADMIN_TAXIS    T ON V.ID_TAXISTA     = T.ADMIN_USUARIOS_ID_USUARIO				
				WHERE V.ID_TAXISTA = ".$aFilter['ID_TAXISTA']."
				$sFilter
				AND V.FECHA_VIAJE BETWEEN '".$aFilter['FECHA_IN']."' AND '".$aFilter['FECHA_FIN']."'  
				GROUP BY V.ID_SRV_ESTATUS";						         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		    	
    }    

    public function getTravelsByTaxi($aFilter){
		$result= Array();
		
		$sFilter = ($aFilter['ID_ESTATUS']!=-99) ? "AND V.ID_SRV_ESTATUS = ".$aFilter['ID_ESTATUS'] : "";
		
    	$sql ="SELECT *, E.ESTATUS AS N_ESTATUS, CONCAT(C.`NOMBRE`,' ',C.APATERNO,' ',C.AMATERNO) AS N_CLIENTE
				FROM ADMIN_VIAJES V
				INNER JOIN SRV_CLIENTES C ON V.ID_CLIENTE 	  = C.ID_CLIENTE
				INNER JOIN SRV_ESTATUS E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS			
				INNER JOIN ADMIN_FORMA_PAGO P ON V.ID_FORMA_PAGO = P.ID_FORMA_PAGO
				INNER JOIN ADMIN_USUARIOS U ON V.ID_TAXISTA 	= U.ID_USUARIO
				INNER JOIN ADMIN_EMPRESAS M ON U.ID_EMPRESA     = M.ID_EMPRESA
				INNER JOIN ADMIN_TAXIS    T ON V.ID_TAXISTA     = T.ADMIN_USUARIOS_ID_USUARIO				
				WHERE V.ID_TAXISTA = ".$aFilter['ID_TAXISTA']."				
				$sFilter
				AND V.FECHA_VIAJE BETWEEN '".$aFilter['FECHA_IN']."' AND '".$aFilter['FECHA_FIN']."'				
				ORDER BY V.FECHA_VIAJE DESC";     	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		    	
    }   

    public function resumeTotalByTaxi($aFilter){
		$result= Array();
		$sFilter = ($aFilter['ID_ESTATUS']!=-99) ? "AND ID_SRV_ESTATUS = ".$aFilter['ID_ESTATUS'] : "";
		
    	$sql ="SELECT COUNT(*) AS TOTAL
				FROM ADMIN_VIAJES V
				INNER JOIN SRV_ESTATUS E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS			
				INNER JOIN ADMIN_FORMA_PAGO P ON V.ID_FORMA_PAGO = P.ID_FORMA_PAGO
				INNER JOIN ADMIN_USUARIOS U ON V.ID_TAXISTA 	= U.ID_USUARIO
				INNER JOIN ADMIN_EMPRESAS M ON U.ID_EMPRESA     = M.ID_EMPRESA
				INNER JOIN ADMIN_TAXIS    T ON V.ID_TAXISTA     = T.ADMIN_USUARIOS_ID_USUARIO				
				WHERE V.ID_TAXISTA = ".$aFilter['ID_TAXISTA']."
				$sFilter
				AND FECHA_VIAJE BETWEEN '".$aFilter['FECHA_IN']."' AND '".$aFilter['FECHA_FIN']."'
				ORDER BY V.FECHA_VIAJE DESC  				
				LIMIT 1";		  	   			      	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0]['TOTAL'];			
		}
		        
		return $result;		    	
    }        
}	