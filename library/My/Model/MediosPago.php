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
class My_Model_MediosPago extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'SRV_FORMAS_PAGO';
	protected $_primary = 'ID_FORMA_PAGO';
	protected $_keycodig= 'myTa4ss1.c0m';
	
	public function getDataTables($idUsuario){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT  T.DESCRIPCION AS N_TARJETA,M.POR_DEFECTO, M.TARJETA_VIEW,M.ESTATUS,M.CREADO,M.ID_FORMA_PAGO AS ID
                FROM $this->_name M 
                INNER JOIN ADMIN_TIPO_TARJETAS T ON M.ID_TIPO_TARJETA = T.ID_TIPO_TARJETA 
                WHERE M.ID_SRV_USUARIO = $idUsuario";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;
		}
        
		return $result;	 		
	}
	
   public function getData($idObject,$idUser){
		try{
			$result= Array();
	    	$sql ="SELECT  T.DESCRIPCION AS N_TARJETA,M.POR_DEFECTO, M.TARJETA_VIEW,M.ESTATUS,M.CREADO,M.ID_FORMA_PAGO AS ID,
	    			M.ID_TIPO_TARJETA,	    M.NOMBRE_TARJETA	,
	    			AES_DECRYPT(M.NO_TARJETA, '".$this->_keycodig."') AS NO_TARJETA,
	    			AES_DECRYPT(M.MES_VENCIMIENTO, '".$this->_keycodig."') AS MES_VENCIMIENTO,
	    			AES_DECRYPT(M.ANO_VENCIMIENTO, '".$this->_keycodig."') AS ANO_VENCIMIENTO,	   
	    			AES_DECRYPT(M.CODIGO_AUTORIZACION, '".$this->_keycodig."') AS CODIGO_AUTORIZACION 			    			
	                FROM $this->_name M 
	                INNER JOIN ADMIN_TIPO_TARJETAS T ON M.ID_TIPO_TARJETA = T.ID_TIPO_TARJETA 
	                WHERE M.ID_SRV_USUARIO = $idUser
	                 AND  M.ID_FORMA_PAGO  = $idObject LIMIT 1 ";	 	    	
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
    
	public function getCboTipoTarjerta(){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT ID_TIPO_TARJETA AS ID, DESCRIPCION AS NAME, DIGITOS
				FROM ADMIN_TIPO_TARJETAS
				WHERE ESTATUS = 1
				ORDER BY DESCRIPCION ASC";
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;
		}
        
		return $result;	 		
	}    
	
    public function updateData($data){
       	$result     = Array();
        $result['status']  = false;
        
        $sTarjetaView  = substr($data['inputTdc'], -4);
        $sTarjetaView  = str_pad($sTarjetaView,16,"#",STR_PAD_LEFT); 
       
        $sql="UPDATE SRV_FORMAS_PAGO	
        		SET  ID_TIPO_TARJETA	= '".$data['inputTipo']."', 
					  NO_TARJETA		= AES_ENCRYPT('".$data['inputTdc']."', '".$this->_keycodig."'), 
					  TARJETA_VIEW		= '".$sTarjetaView."', 
					  NOMBRE_TARJETA	= '".$data['inputNombreTdc']."', 
					  MES_VENCIMIENTO	= AES_ENCRYPT('".$data['inputMes']  ."', '".$this->_keycodig."'),  
					  ANO_VENCIMIENTO	= AES_ENCRYPT('".$data['inputAno']  ."', '".$this->_keycodig."'),  
					  CODIGO_AUTORIZACION= AES_ENCRYPT('".$data['inputCode']."', '".$this->_keycodig."'), 
					  POR_DEFECTO		= ".$data['inputDefault'].", 
					  ESTATUS        	= ".$data['inputEstatus']." 	
			  WHERE ID_FORMA_PAGO   	= ".$data['catId']."
			    AND ID_SRV_USUARIO		= ".$data['inputUsuario']."  LIMIT 1";
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				if($data['inputDefault']==1){
					$this->resetDefault($data['inputUsuario']);
					$this->setDefault($data['catId']);
				}
								
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
                             
        $sTarjetaView  = substr($data['inputTdc'], -4);
        $sTarjetaView  = str_pad($sTarjetaView,16,"#",STR_PAD_LEFT); 
                
        $sql="INSERT INTO SRV_FORMAS_PAGO
        			 SET  ID_SRV_USUARIO  	= ".$data['inputUsuario'].",
        			 	  ID_TIPO_TARJETA	= '".$data['inputTipo']."', 
						  NO_TARJETA		= AES_ENCRYPT('".$data['inputTdc']."', '".$this->_keycodig."'), 
						  TARJETA_VIEW		= '".$sTarjetaView."', 
						  NOMBRE_TARJETA	= '".$data['inputNombreTdc']."', 
						  MES_VENCIMIENTO	= AES_ENCRYPT('".$data['inputMes']  ."', '".$this->_keycodig."'),  
						  ANO_VENCIMIENTO	= AES_ENCRYPT('".$data['inputAno']  ."', '".$this->_keycodig."'),  
						  CODIGO_AUTORIZACION= AES_ENCRYPT('".$data['inputCode']."', '".$this->_keycodig."'), 
						  POR_DEFECTO 		= ".$data['inputDefault'].", 
						  ESTATUS        	= ".$data['inputEstatus'].", 	 
        				  CREADO 			=  CURRENT_TIMESTAMP";
        try{
    		$query   = $this->query($sql,false);
    		$sql_id ="SELECT LAST_INSERT_ID() AS ID_LAST;";
			$query_id   = $this->query($sql_id);
			if(count($query_id)>0){
				if($data['inputDefault']==1){
					$this->resetDefault($data['inputUsuario']);
					$this->setDefault($query_id[0]['ID_LAST']);
				}
				$result['id']	   = $query_id[0]['ID_LAST'];
				$result['status']  = true;	
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
    }
    
    public function resetDefault($idUsuario){
        $result     = Array();
        $result['status']  = false;    	
        try{
    		$sql ="UPDATE 	SRV_FORMAS_PAGO
						SET POR_DEFECTO = 0
						WHERE ID_SRV_USUARIO = $idUsuario";
			$query_id   = $this->query($sql,false);
			if(count($query_id)>0){				
				$result['status']  = true;	
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;    	
    }

    public function setDefault($idForma){
        $result     = Array();
        $result['status']  = false;    	
        try{    		
    		$sql ="UPDATE 	SRV_FORMAS_PAGO
						SET POR_DEFECTO 	= 1
						WHERE ID_FORMA_PAGO = $idForma LIMIT 1";
			$query_id   = $this->query($sql,false);
			if(count($query_id)>0){				
				$result['status']  = true;	
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;    	
    }    
}