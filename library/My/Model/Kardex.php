<?php
/**
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Kardex extends My_Db_Table
{
	protected $_schema 	= 'TACCSI';
	protected $_name 	= 'ADMIN_KARDEX';
	protected $_primary = 'ID_KARDEX';
	
    public function getData($idObject){
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
 
   public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO ADMIN_KARDEX	
        		SET	  ID_USUARIO    = ".$data['userCreate'].",        			  
					  REGISTRADO    = CURRENT_TIMESTAMP";
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
        
		$nameImage1  = ($data['simageLicF']!="")  	? ", VAL_LICENCIA_FRENTE	= 1 , LICENCIA_FRENTE    	='".$data['simageLicF']."'": "";
        $nameImage2  = ($data['simageLicB']!="") 	? ", VAL_LICENCIA_REVERSA= 1 , LICENCIA_REVERSA 		='".$data['simageLicB']."'": "";
        $nameImage3  = ($data['simageIde']!="") 	? ", VAL_IDENTIFICACION 	= 1 , IDENTIFICACION 		='".$data['simageIde']."'": "";
        $nameImage4  = ($data['simageCodom']!="") 	? ", VAL_COMP_DOMICILIO = 1  ,COMPROBANTE_DOMICILIO='".$data['simageCodom']."'": "";
        $nameImage5  = ($data['simageSat']!="") 	? ", VAL_CEDULA_FISCAL 	= 1 , CEDULA_FISCAL			='".$data['simageSat']."'": "";
        $nameImage6  = ($data['simageNopen']!="") 	? ", VAL_ANTECEDENTES 	= 1 , ANTECEDENTES			='".$data['simageNopen']."'": "";
        $nameImage7  = ($data['imageEdoCu']!="") 	? ", VAL_ESTADO_CUENTA 	= 1 , ESTADO_CUENTA			='".$data['imageEdoCu']."'": "";
        
        $sql="UPDATE ADMIN_KARDEX	
        		SET	  ULT_ACTUALIZACION= CURRENT_TIMESTAMP
        			  $nameImage1
        			  $nameImage2
        			  $nameImage3
        			  $nameImage4
        			  $nameImage5
        			  $nameImage6
        			  $nameImage7
			  WHERE ID_USUARIO  = ".$data['userCreate']." LIMIT 1";
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

    public function getDataToValidate(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT U.ID_USUARIO, E.NOMBRE_EMPRESA, CONCAT(U.NOMBRE,' ',U.APATERNO,' ',U.AMATERNO) AS N_USER, 
				IF(K.ID_KARDEX IS NULL,'Sin Documentacion', IF(U.DOCUMENTOS_VALIDADOS = 0 ,'Faltan Documentos','Documentacion Completa')) AS N_DOCS
				FROM ADMIN_USUARIOS U
				INNER JOIN ADMIN_EMPRESAS E ON U.ID_EMPRESA = E.ID_EMPRESA
				LEFT JOIN ADMIN_KARDEX K ON U.ID_USUARIO = K.ID_USUARIO
				WHERE U.TIPO_USUARIO = 4
				  AND U.ID_EMPRESA IS NOT NULL
				ORDER BY U.NOMBRE ASC";	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;	      	
    }
    
    public function updateValidate($data){
       	$result     = Array();
        $result['status']  = false;
        $sOption 	= "";
        
    	$aRespuesta  = $data['iRespuesta'];
								
		if($data['sOption']=='licencefront'){
			$sOption  = ", VAL_LICENCIA_FRENTE	= ".$aRespuesta;
		}
		
		if($data['sOption']=='licenceback'){
			$sOption  = ", VAL_LICENCIA_REVERSA	= ".$aRespuesta;
		}
						
		if($data['sOption']=='identification'){
			$sOption  = ", VAL_IDENTIFICACION	= ".$aRespuesta;
		}

		if($data['sOption']=='comp_domicilio'){
			$sOption  = ", VAL_COMP_DOMICILIO	= ".$aRespuesta;
		}

		if($data['sOption']=='ced_fiscal'){
			$sOption  = ", VAL_CEDULA_FISCAL	= ".$aRespuesta;
		}
						
		if($data['sOption']=='antecedentes'){
			$sOption  = ", VAL_ANTECEDENTES	= ".$aRespuesta;
		}

		if($data['sOption']=='edocuenta'){
			$sOption  = ", VAL_ESTADO_CUENTA	= ".$aRespuesta;
		}        
        
        $sql="UPDATE ADMIN_KARDEX	
        		SET	  ULT_ACTUALIZACION= CURRENT_TIMESTAMP
        		$sOption
			  WHERE ID_USUARIO  = ".$data['catId']." LIMIT 1";
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
    
    public function validateTaxis(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT T.ID_TAXI, E.NOMBRE_EMPRESA, T.PLACAS, T.ECO,
			      IF(T.DOCUMENTOS_VALIDADOS = 1 ,'Documentacion Completa','Faltan Documentos') AS N_DOCS
			FROM ADMIN_TAXIS T
			INNER JOIN ADMIN_EMPRESAS E ON T.ID_EMPRESA = E.ID_EMPRESA
			AND T.ID_EMPRESA IS NOT NULL
			ORDER BY T.PLACAS ASC";	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;	      	
        	
    }
}