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
	    	$sql ="SELECT u.*,tu.*,E.NOMBRE_EMPRESA AS N_EMPRESA
	                FROM ADMIN_USUARIOS u
					INNER JOIN ADMIN_TIPO_USUARIOS tu ON u.TIPO_USUARIO = tu.ID_TIPO_USUARIO
					INNER JOIN ADMIN_EMPRESAS    E  ON u.ID_EMPRESA    = E.ID_EMPRESA
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
    
	public function getDataTables($idEmpresa){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT   U.ID_USUARIO, 
						U.TIPO_USUARIO,
						P.DESCRIPCION AS PERFIL,
						U.NICKNAME,
						CONCAT(U.NOMBRE,' ',U.APATERNO,' ',U.AMATERNO) AS NOMBRE,
						U.ACTIVO
				FROM ADMIN_USUARIOS U
				INNER JOIN ADMIN_TIPO_USUARIOS   P ON U.TIPO_USUARIO   = P.ID_TIPO_USUARIO
				WHERE U.ID_EMPRESA    = $idEmpresa
				  AND U.TIPO_USUARIO != 4 
				ORDER BY NOMBRE ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	        
		return $result;			
	}   

    public function validateData($dataSearch,$idObject,$optionSearch){
		$result=true;		
		$this->query("SET NAMES utf8",false);
		$filter = ($optionSearch=='user') ? ' NICKNAME = "'.$dataSearch.'"': ' IP = "'.$dataSearch.'"';
    	$sql ="SELECT $this->_primary
	    		FROM $this->_name
				WHERE ID_USUARIO <> $idObject
                 AND  $filter";
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = false;
		}
        
		return $result;		    	
    }

    public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO $this->_name	
        		SET ID_EMPRESA		=  ".$data['dataIdEmpresa'].",
					TIPO_USUARIO	=  ".$data['inputTipo'].",
					NOMBRE			= '".$data['inputNombre']."',
					APATERNO		= '".$data['inputApaterno']."',
					AMATERNO		= '".$data['inputAmaterno']."',
					TELEFONO		= '".$data['inputPhone']."',
					NICKNAME		= '".$data['inputUsuario']."',
					PASSWORD		= SHA1('".$data['inputPassword']."'),
					PASSWORD_TEXT	= '".$data['inputPassword']."',
					FECHA_REGISTRO	= CURRENT_TIMESTAMP,
					ACTIVO 			= ".$data['inputEstatus'];
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
        
        $sPassword = '';
        if($data['inputPassword']!=""){
        	$sPassword = " PASSWORD	=  SHA1('".$data['inputPassword']."'), PASSWORD_TEXT	= '".$data['inputPassword']."',";
        }

        $sql="UPDATE $this->_name	
        		SET TIPO_USUARIO	=  ".$data['inputTipo'].",
					NOMBRE			= '".$data['inputNombre']."',
					APATERNO		= '".$data['inputApaterno']."',
					AMATERNO		= '".$data['inputAmaterno']."',
					TELEFONO		= '".$data['inputPhone']."',
					$sPassword
					NICKNAME		= '".$data['inputUsuario']."',				
					ACTIVO 			= ".$data['inputEstatus']."
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