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
    	$sql ="SELECT R.ID_REGION AS ID,R.DESCRIPCION, IF(E.ID_SP_ESTADOS IS NULL,'-', E.NAME) AS ESTADO, IF(R.ESTATUS=1,'Activo','Inactivo') AS ESTATUS
				FROM ADMIN_REGIONES R
				INNER JOIN SP_ESTADOS E ON R.ID_ESTADO = E.ID_SP_ESTADOS
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
	    	$sql ="SELECT ID_REGION,ID_ESTADO,DESCRIPCION,ESTATUS, ASTEXT(MAP_OBJECT) AS MAP_OBJECT2
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
        
        $idEstado = (isset($data['inputEstado']))  ? $data['inputEstado'] : 'NULL' ;
        $sPolygono= (isset($data['inputPolygon'])) ? "MAP_OBJECT = GEOMFROMTEXT('".$data['inputPolygon']."')," : "" ;  
        
        $sql="INSERT INTO ADMIN_REGIONES	
        		SET DESCRIPCION     ='".$data['inputNombre']."',
        			ID_ESTADO		= ".$idEstado.",
        			$sPolygono
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
        $sPolygono= (isset($data['inputPolygon'])) ? "MAP_OBJECT = GEOMFROMTEXT('".$data['inputPolygon']."')," : "" ;
        
        $sql="UPDATE ADMIN_REGIONES	
				SET DESCRIPCION     ='".$data['inputNombre']."',
					ID_ESTADO		= ".$idEstado.",
					$sPolygono
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