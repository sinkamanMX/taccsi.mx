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
class My_Model_Empresas extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'ADMIN_EMPRESAS';
	protected $_primary = 'ID_EMPRESA';
	
	public function getDataTables(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT *, IF(TIPO_RAZON='M','Moral','Fisica') AS TIPO
				FROM $this->_name
				ORDER BY RAZON_SOCIAL ASC";
		$query   = $this->query($sql);
		if(count($query)>0){
			$result = $query;			
		}
		return $result;
	}   
	
    public function getData($idObject){
		try{
			$result= Array();
	    	$sql ="SELECT *, IF(TIPO_RAZON='M','Moral','Fisica') AS TIPO
					FROM $this->_name
	                WHERE ID_EMPRESA = $idObject";
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

        $idEstado    = (isset($data['inputEstadoF'])    && $data['inputEstadoF']    > 0) ? $data['inputEstadoF']   : 0;
        $idMunicipio = (isset($data['inputMunicipioF']) && $data['inputMunicipioF'] > 0) ? $data['inputMunicipioF']: 0;        
        
        $sql="INSERT INTO $this->_name	
        		SET	  TIPO_RAZON		='".$data['inputTipoRazon']."',
				  	  NOMBRE_EMPRESA	='".$data['inputNameEmpresa']."', 
					  RAZON_SOCIAL		='".$data['inputRazon']."',
					  RFC				='".$data['inputRFC']."',
					  REPRESENTANTE_LEGAL='".$data['inputRep']."',
					  CALLE				='".$data['inputCalle']."',
					  NOEXT				='".$data['inputNoext']."',
					  NOINT				='".$data['inputNoint']."',
					  COLONIA			='".$data['inputColonia']."',
					  ID_MUNICIPIO		= ".$data['inputMunicipio'].",
					  ID_ESTADO			= ".$data['inputEstado'].",
					  CP				='".$data['inputCp']."',
					  DIR_DIFERENTE		= ".$data['inputDirDif'].",
					  FIS_CALLE			='".$data['inputCalleF']."',
					  FIS_NOEXT			='".$data['inputNoextF']."',
					  FIS_NOINT			='".$data['inputNointF']."',
					  FIS_COLONIA		='".$data['inputColoniaF']."',
					  FIS_ID_MUNICIPIO	= ".$idMunicipio.",
					  FIS_ID_ESTADO		= ".$idEstado.",
					  FIS_CP			='".$data['inputCpF']."',
					  ESTATUS			= ".$data['inputEstatus'].",
					  FECHA_REGISTRO	= CURRENT_TIMESTAMP,
					  USUARIO_CREACION  = ".$data['userCreate'];
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
        
        $idEstado    = (isset($data['inputEstadoF'])    && $data['inputEstadoF']    > 0) ? $data['inputEstadoF']   : 0;
        $idMunicipio = (isset($data['inputMunicipioF']) && $data['inputMunicipioF'] > 0) ? $data['inputMunicipioF']: 0;  

        $sql="UPDATE $this->_name	
        		SET	  TIPO_RAZON		='".$data['inputTipoRazon']."',
				  	  NOMBRE_EMPRESA	='".$data['inputNameEmpresa']."', 
					  RAZON_SOCIAL		='".$data['inputRazon']."',
					  RFC				='".$data['inputRFC']."',
					  REPRESENTANTE_LEGAL='".$data['inputRep']."',
					  CALLE				='".$data['inputCalle']."',
					  NOEXT				='".$data['inputNoext']."',
					  NOINT				='".$data['inputNoint']."',
					  COLONIA			='".$data['inputColonia']."',
					  ID_MUNICIPIO		= ".$data['inputMunicipio'].",
					  ID_ESTADO			= ".$data['inputEstado'].",
					  CP				='".$data['inputCp']."',
					  DIR_DIFERENTE		= ".$data['inputDirDif'].",
					  FIS_CALLE			='".$data['inputCalleF']."',
					  FIS_NOEXT			='".$data['inputNoextF']."',
					  FIS_NOINT			='".$data['inputNointF']."',
					  FIS_COLONIA		='".$data['inputColoniaF']."',
					  FIS_ID_MUNICIPIO	= ".$idMunicipio.",
					  FIS_ID_ESTADO		= ".$idEstado.",
					  FIS_CP			='".$data['inputCpF']."',
					  ESTATUS			= ".$data['inputEstatus']."
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
	 	
}