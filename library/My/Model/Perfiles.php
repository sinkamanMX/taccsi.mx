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
class My_Model_Perfiles extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'ADMIN_VIAJES';
	protected $_primary = 'ID_VIAJES';

	public function getModuleDefault($idProfile){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT *
				FROM MODULOS_TIPO T
				INNER JOIN MODULOS M ON T.ID_MODULO = M.ID_MODULO
				WHERE T.ID_TIPO_USUARIO = $idProfile
				 AND  T.INICIO    		= 1 
				 AND  M.ACTIVO 			= 1 LIMIT 1";	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	  		
	}   
	
	public function getModules($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		 		
    	$sql ="/*SELECT M.*, M.DESCRIPCION AS M_DESCRIPCION,N.DESCRIPCION AS N_DESCRIPCION,N.ID_MENU AS IDMENU, N.*, M.SCRIPT AS S_MODULE, N.ICONO AS N_ICONO*/
    			SELECT M.ID_MENU, M.ID_MODULO, M.DESCRIPCION AS M_DESCRIPCION,N.DESCRIPCION AS N_DESCRIPCION,N.ID_MENU AS IDMENU, M.SCRIPT AS S_MODULE, N.ICONO AS N_ICONO,
					   M.ICONO, M.SCRIPT AS S_MODULE
				FROM MODULOS_TIPO MP
				INNER JOIN MODULOS M ON MP.ID_MODULO = M.ID_MODULO
				INNER JOIN MENU    N ON M.ID_MENU    = N.ID_MENU 
				WHERE MP.ID_TIPO_USUARIO = $idObject 
				  AND M.ACTIVO 			 = 1
				ORDER BY N.ID_MENU ASC, M.DESCRIPCION ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}	
	
	public function getDataModule($classObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT *
				FROM  MODULOS
				WHERE CLASE = '".$classObject."' LIMIT 1";	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	 		
	}
	
	public function getDataMenu($classObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT *
				FROM  MODULOS
				WHERE CLASE = '".$classObject."' LIMIT 1";	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	 		
	}		
		
}