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
class My_Model_ServUsuarios extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'SRV_USUARIOS';
	protected $_primary = 'ID_SRV_USUARIO';
	
	public function validateUser($datauser){
		$result= Array();
    	$sql ="SELECT *
                FROM ".$this->_name."  
                WHERE USUARIO  = '".$datauser['usuario']."'
                 AND PASSWORD  = '".$datauser['contrasena']."'
                 AND ESTATUS   = 1";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];			
		}	
        
		return $result;			
	} 
	
    public function getDataUser($datauser){
		try{
			$result= Array();
	    	$sql ="SELECT U.*
	                FROM ".$this->_name." U
	                WHERE U.".$this->_primary."   = $datauser";	 	    	
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
    
    public function getTravelsByUser($idUsuario){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT *, E.ESTATUS AS N_ESTATUS, CONCAT(U.NOMBRE) AS N_TACCISTA,P.DESCRIPCION AS N_TPAGO, V.RATING AS N_RATING
				FROM ADMIN_VIAJES V
				INNER JOIN SRV_CLIENTES C ON V.ID_CLIENTE 	  = C.ID_CLIENTE
				INNER JOIN SRV_ESTATUS E ON V.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS			
				INNER JOIN ADMIN_FORMA_PAGO P ON V.ID_FORMA_PAGO = P.ID_FORMA_PAGO
				LEFT JOIN ADMIN_USUARIOS U ON V.ID_TAXISTA 	= U.ID_USUARIO
				LEFT JOIN ADMIN_EMPRESAS M ON U.ID_EMPRESA     = M.ID_EMPRESA
				LEFT JOIN ADMIN_TAXIS    T ON V.ID_TAXISTA     = T.ADMIN_USUARIOS_ID_USUARIO  			
    			WHERE V.ID_CLIENTE = ".$idUsuario."
    			ORDER BY V.ID_VIAJES DESC";
    	$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	        
		return $result;		
    }
    
    public function validateData($dataSearch,$idObject,$optionSearch){
		$result=true;		
		$this->query("SET NAMES utf8",false);
		$filter = ($optionSearch=='user') ? ' USUARIO = "'.$dataSearch.'"': ' IP = "'.$dataSearch.'"';
    	$sql ="SELECT $this->_primary
	    		FROM $this->_name
				WHERE $this->_primary <> $idObject
                 AND  $filter";
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = false;
		}
        
		return $result;		    	
    }    
    
    public function updateProfile($data){
       	$result     = Array();
        $result['status']  = false;
        
        $sPassword = '';
        if($data['inputPassword']!=""){
        	$sPassword = " PASSWORD	= '".$data['inputPassword']."',";
        }
        
        $nameImage  = (isset($data['nameImagen']) && $data['nameImagen']!="") ? ",IMAGEN			='".$data['nameImagen']."'": "";

        $sql="UPDATE $this->_name	
        		SET NOMBRE			= '".$data['inputNombre']."',
					APATERNO		= '".$data['inputApaterno']."',
					AMATERNO		= '".$data['inputAmaterno']."',
					TELEFONO		= '".$data['inputPhone']."',
					$sPassword
					USUARIO			= '".$data['inputUsuario']."',
					EMAIL			= '".$data['inputUsuario']."'
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
}	