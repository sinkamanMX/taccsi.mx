<?php
/**
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Bancarios extends My_Db_Table
{
	protected $_schema 	= 'TACCSI';
	protected $_name 	= 'ADMIN_BANCOS';
	protected $_primary = 'ID_BANCO';
	
	public function getCboBancos(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 		
    	$sql ="SELECT ID_BANCO AS ID, NOMBRE AS NAME 
    			FROM ADMIN_BANCOS 
    			ORDER BY NOMBRE ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}
	
    public function getData($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT  *
                FROM ADMIN_FINANZAS
                WHERE ID_USUARIO = $idObject LIMIT 1";	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	    	
    }	
	
    public function updateRow($data){
       	$result     = Array();
        $result['status']  = false;
        
        $sql="UPDATE ADMIN_FINANZAS	
        		SET	  ID_BANCO		= ".$data['inputbanco'].",
				  	  NO_CUENTA		='".$data['inputNoCuenta']."', 
					  CLABE			='".$data['inputClabe']."',
					  SUCURSAL		='".$data['inputSucursal']."'
			  WHERE ID_USUARIO      = ".$data['userCreate']." LIMIT 1";
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

    public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT INTO ADMIN_FINANZAS	
        		SET	  ID_USUARIO    = ".$data['userCreate'].",
        			  ID_BANCO		= ".$data['inputbanco'].",
				  	  NO_CUENTA		='".$data['inputNoCuenta']."', 
					  CLABE			='".$data['inputClabe']."',
					  SUCURSAL		='".$data['inputSucursal']."',
					  REGISTRADO    = CURRENT_TIMESTAMP";
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
}