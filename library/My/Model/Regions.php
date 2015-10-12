<?php
/**
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Regions extends My_Db_Table
{
	protected $_schema 	= 'TACCSI';
	protected $_name 	= 'ADMIN_REGIONES';
	protected $_primary = 'ID_REGION';
	
	public function getDataTables(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT R.ID_REGION AS ID,R.DESCRIPCION, IF(E.ID_ESTADO IS NULL,'-', E.NOMBRE) AS ESTADO, IF(R.ESTATUS=1,'Activo','Inactivo') AS ESTATUS
				FROM ADMIN_REGIONES R
				INNER JOIN ZZ_SPM_ENTIDADES E ON R.ID_ESTADO = E.ID_ESTADO
				ORDER BY R.DESCRIPCION ASC";
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
					FROM ADMIN_REGIONES
					WHERE ID_REGION = $idObject
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
        
        $idEstado = (isset($data['inputEstado'])) ? $data['inputEstado'] : 'NULL' ; 
        
        $sql="INSERT INTO ADMIN_REGIONES	
        		SET DESCRIPCION     ='".$data['inputNombre']."',
        			ID_ESTADO		= ".$idEstado.",
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
        
        $idEstado = (isset($data['inputEstado'])) ? $data['inputEstado'] : 'NULL' ;

        $sql="UPDATE ADMIN_REGIONES	
				SET DESCRIPCION     ='".$data['inputNombre']."',
					ID_ESTADO		= ".$idEstado.",
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