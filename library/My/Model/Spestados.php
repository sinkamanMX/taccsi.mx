<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Spestados extends My_Db_Table
{
    protected $_schema 	= 'SIMA';
	protected $_name 	= 'SP_ESTADOS';
	protected $_primary = 'ID_SP_ESTADOS';
		
	public function getCbo(){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT ID_SP_ESTADOS AS ID, NAME
					FROM SP_ESTADOS
					GROUP BY NAME
					ORDER BY NAME ASC";
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query;			
			}
		}catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
        
		return $result;			
	}
	
    public function getData($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT ASTEXT(ogc_geom) AS GEO
				FROM SP_ESTADOS
				WHERE ID_SP_ESTADOS = $idObject LIMIT 1";	
    	Zend_Debug::dump($sql);
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	    	
    }	
}