<?php
/**
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Clases extends My_Db_Table
{
	protected $_schema 	= 'TACCSI';
	protected $_name 	= 'ADMIN_CLASE_TAXIS';
	protected $_primary = 'ID_CLASE';
	
	public function getDataTables(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT ID_CLASE AS ID, DESCRIPCION, IF(ESTATUS=1,'Activo','Inactivo') AS ESTATUS
				FROM ADMIN_CLASE_TAXIS
				ORDER BY DESCRIPCION ASC";
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
				FROM ADMIN_CLASE_TAXIS
				WHERE ID_CLASE = $idObject
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