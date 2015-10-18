<?php
/**
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Tarifas extends My_Db_Table
{
	protected $_schema 	= 'TACCSI';
	protected $_name 	= 'ADMIN_TARIFAS';
	protected $_primary = 'ID_TARIFA';
	
	public function getDataTables(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT T.DESCRIPCION, C.DESCRIPCION AS N_CLASE, E.NOM_ENT, IF(USA_TAXIMETRO=0,'No','Si') AS N_TAXIMETRO,
    			T.BANDERAZO , IF(T.ESTATUS=0,'Inactivo','Activo') AS N_ESTATUS,T.HORARIO_INICIO, T.HORARIO_FIN, T.COSTO_KILOMETRO ,T.COSTO_MINUTOS
				FROM ADMIN_TARIFAS T
				INNER JOIN ADMIN_CLASE_TAXIS C ON T.ID_CLASE  = C.ID_CLASE
				INNER JOIN SP_ESTADOS        E ON T.ID_ESTADO = E.ID
				ORDER BY T.DESCRIPCION  ASC";
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
				FROM ADMIN_TARIFAS
				WHERE ID_TARIFA = $idObject
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
    
    public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO ADMIN_CLASE_TAXIS	
        		SET DESCRIPCION     ='".$data['inputNombre']."',
        			ESTATUS			= ".$data['inputEstatus']." ";
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

        $sql="UPDATE ADMIN_CLASE_TAXIS	
				SET DESCRIPCION     ='".$data['inputNombre']."',
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
}