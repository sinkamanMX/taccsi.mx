<?php
/**
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Zonas extends My_Db_Table
{
	protected $_schema 	= 'TACCSI';
	protected $_name 	= 'ADMIN_ZONAS';
	protected $_primary = 'ID_ZONA';
	
	public function getDataTables($idTarifa){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT ID_ZONA, ID_TARIFA, IF(TIPO_ZONA=0,'Circunferencia','Poligono') AS N_TIPO, DESCRIPCION, COSTO, IF(COSTO_ACUMULABLE=0,'No','Si') AS N_ACUM
				FROM ADMIN_ZONAS
				WHERE ID_TARIFA  = $idTarifa";
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
					FROM ADMIN_ZONAS
					WHERE ID_ZONA = $idObject
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
        
		$idEstado = (isset($data['inputEstado'])     && $data['inputEstado']    !="")  	? $data['inputEstado']     : 'NULL' ;
        $sCosto   = (isset($data['inputCosto'])      && $data['inputCosto']     !="")   ? $data['inputCosto']      : '0' ;
        $sAcum	  = (isset($data['inputAcumulable']) && $data['inputAcumulable']!="")  	? $data['inputAcumulable'] : '0' ;
        $sLatitud = (isset($data['inputLatOrigen'])  && $data['inputLatOrigen']!="")  	? $data['inputLatOrigen']  : '0' ;
        $sLongitud= (isset($data['inputLonOrigen'])  && $data['inputLonOrigen']!="")  	? $data['inputLonOrigen']  : '0' ;
        $sRadio	  = (isset($data['inputRadio'])      && $data['inputRadio']    !="")  	? $data['inputRadio'] 	   : '0' ;        
        $sPolygono= (isset($data['inputPolygon'])) ? "MAP_OBJECT = GEOMFROMTEXT('".$data['inputPolygon']."')," : "" ;
        
        $sql="INSERT INTO ADMIN_ZONAS	
        		SET ID_TARIFA			= ".$data['catIdtar'].",
					  TIPO_ZONA			= ".$data['inputTipo'].",
					  ID_ESTADO			= ".$idEstado.",
					  DESCRIPCION       ='".$data['inputNombre']."',	
					  COSTO				= ".$sCosto.",
					  COSTO_ACUMULABLE  = ".$sAcum.",
					  $sPolygono
					  LATITUD			= ".$sLatitud.",
					  LONGITUD			= ".$sLongitud.",
					  RADIO				= ".$sRadio.",						  	
					  CREADO			= CURRENT_TIMESTAMP ";					  
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
        
        $idEstado = (isset($data['inputEstado'])     && $data['inputEstado']    !="")  	? $data['inputEstado']     : 'NULL' ;
        $sCosto   = (isset($data['inputCosto'])      && $data['inputCosto']     !="")   ? $data['inputCosto']      : '0' ;
        $sAcum	  = (isset($data['inputAcumulable']) && $data['inputAcumulable']!="")  	? $data['inputAcumulable'] : '0' ;
        $sLatitud = (isset($data['inputLatOrigen'])  && $data['inputLatOrigen']!="")  	? $data['inputLatOrigen']  : '0' ;
        $sLongitud= (isset($data['inputLonOrigen'])  && $data['inputLonOrigen']!="")  	? $data['inputLonOrigen']  : '0' ;
        $sRadio	  = (isset($data['inputRadio'])      && $data['inputRadio']    !="")  	? $data['inputRadio'] 	   : '0' ;        
        $sPolygono= (isset($data['inputPolygon'])) ? "MAP_OBJECT = GEOMFROMTEXT('".$data['inputPolygon']."')," : "" ;
        
        $sql="UPDATE ADMIN_ZONAS	
				SET   TIPO_ZONA			= ".$data['inputTipo'].",
					  ID_ESTADO			= ".$idEstado.",
					  DESCRIPCION       ='".$data['inputNombre']."',	
					  COSTO				= ".$sCosto.",
					  COSTO_ACUMULABLE  = ".$sAcum.",
					  $sPolygono
					  LATITUD			= ".$sLatitud.",
					  LONGITUD			= ".$sLongitud.",
					  RADIO				= ".$sRadio."					          		
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