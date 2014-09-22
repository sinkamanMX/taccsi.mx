<?php

class My_Model_Incidencias extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'ATN_INCIDENCIAS';
	protected $_primary = 'ID_SERVICIO';
	
	public function getIncidencias($idViaje){
		$result= Array();
    	$sql ="SELECT S.ID_SERVICIO,S.ID_VIAJE,S.FECHA_REGISTRO, I.NOMBRE_SERVICIO, E.DESCRIPCION AS ESTATUS
				FROM ".$this->_name." S
				INNER JOIN ATN_TIPO_INCIDENCIA I ON S.ID_TIPO_INCIDENCIA = I.ID_TIPO_INCIDENCIA
				INNER JOIN ATN_INCIDENCIAS_ESTATUS E  ON S.ID_INCIDENCIAS_ESTATUS = E.ID_INCIDENCIAS_ESTATUS
				WHERE ID_VIAJE = ".$idViaje;			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		
	}
	
	public function getData($idIncidencia){
		$result= Array();
    	$sql ="SELECT I.*, T.NOMBRE_SERVICIO, E.DESCRIPCION AS E_DESCRIPCION, CONCAT(U.NOMBRE,' ',U.APATERNO,' ',U.AMATERNO) AS UATENCION
				FROM ATN_INCIDENCIAS I
				INNER JOIN ATN_TIPO_INCIDENCIA T     ON I.ID_TIPO_INCIDENCIA     = T.ID_TIPO_INCIDENCIA
				INNER JOIN ATN_INCIDENCIAS_ESTATUS E ON I.ID_INCIDENCIAS_ESTATUS = E.ID_INCIDENCIAS_ESTATUS
				INNER JOIN ADMIN_USUARIOS          U ON I.USUARIO_ATENCION       = U.ID_USUARIO
				WHERE ID_SERVICIO = ".$idIncidencia."
				LIMIT 1";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];						
		}
		        
		return $result;				
	}
	
	public function getEstatusInc(){
		$result= Array();
    	$sql ="SELECT ID_INCIDENCIAS_ESTATUS AS ID, DESCRIPCION AS NAME
				FROM ATN_INCIDENCIAS_ESTATUS
				ORDER BY NAME ASC";		
    	         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}		        
		return $result;			
	}
	
	public function getTipoInc(){
		$result= Array();
    	$sql ="SELECT ID_TIPO_INCIDENCIA AS ID, NOMBRE_SERVICIO AS NAME
				FROM ATN_TIPO_INCIDENCIA
				ORDER BY ID_TIPO_INCIDENCIA";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;			
	}
	
	public function getNotasInc($idIncidencia){
		$result= Array();
    	$sql ="SELECT N.* , CONCAT(U.NOMBRE,' ',U.APATERNO,' ',U.AMATERNO) AS UATENCION
				FROM ATN_INCIDENCIAS_NOTAS N
				INNER JOIN ADMIN_USUARIOS U ON N.`USUARIO_ATENDIO` = U.`ID_USUARIO`
				WHERE N.ID_INCIDENCIAS = ".$idIncidencia;			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		
	}
	
	public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO ".$this->_name."
        		SET ID_VIAJE				= ".$data['idViaje'].",
					ID_TIPO_INCIDENCIA		= ".$data['inputTipo'].",
					ID_INCIDENCIAS_ESTATUS	= ".$data['inputEstatus'].",
					DESCRIPCION				= '".$data['inputDescripcion']."',
					FECHA_REGISTRO			= CURRENT_TIMESTAMP,
					USUARIO_ATENCION 		= ".$data['userCreate'];
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
	
	public function updateRow($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="UPDATE ".$this->_name."
        		SET ID_TIPO_INCIDENCIA		= ".$data['inputTipo'].",
					ID_INCIDENCIAS_ESTATUS	= ".$data['inputEstatus'].
        		" WHERE ID_SERVICIO = ".$data['strInc'];
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
	
	public function insertRowNota($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO ATN_INCIDENCIAS_NOTAS
        			SET ID_INCIDENCIAS	= ".$data['strInc'].",
						USUARIO_ATENDIO	= ".$data['userCreate'].",
						FECHA_REGISTRO	= CURRENT_TIMESTAMP, 
						COMENTARIO 		= '".$data['inputComment']."'";
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
}