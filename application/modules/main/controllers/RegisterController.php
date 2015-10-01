<?php
class main_RegisterController extends My_Controller_Action
{	
    public function init()
    {
    	try{
    		$this->view->layout()->setLayout('layout_register');						
			$this->_dataIn 					= $this->_request->getParams();
	    	if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];				
			}	
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
    public function indexAction(){
    	try{    	
    		$config     = Zend_Controller_Front::getInstance()->getParam('bootstrap');
			$aDataAdmin = $config->getOption('admin');
    		$cFunctions	= new My_Controller_Functions();
    		$classObject= new My_Model_Taxistas();
    		$cTaxis		= new My_Model_Taxis();    		
    		$cColores   = new My_Model_Colores();
    		$cModelos	= new My_Model_Modelos();
    		$cMarcas	= new My_Model_Marcas();
    		$cEmpresas  = new My_Model_Empresas();
    		
    		$aColores	= $cColores->getCbo();
    		$aMarcas	= $cMarcas->getCbo();
    		$aModelos   = Array();
    		$sColores	= '';
    		$sMarcas	= '';
    		$sModelos   = '';
    		$sPropio	= '';
    		$dataInput  = Array(); 
    		    		
    		if($this->_dataOp == 'opRegister'){
    			$this->_dataIn['nameImagen'] = '';
    			$validateUser = $classObject->validateData($this->_dataIn['inputUsuario'],-1,'user');
    			if($validateUser){ 			
    				$claveEmpresa = $cEmpresas->getCodeEmp($this->_dataIn['inputRFC']);
    				$nameUser     = $this->_dataIn['inputNombre']." ".$this->_dataIn['inputAmaterno']." ".$this->_dataIn['inputApaterno'];
    				$aDataEmpresa = Array(
    					'inputTipoRazon' 	=> 'F',
    					'CLAVE_EMP'			=> $claveEmpresa,
    					'inputNameEmpresa'	=> $nameUser ,
    					'inputRazon'		=> $nameUser ,
	    				'inputRFC'			=> $this->_dataIn['inputRFC'] ,
	    				'inputRep'			=> '',
	    				'inputCalle'		=> '',
	    				'inputNoext'		=> '',
	    				'inputNoint'		=> '',
	    				'inputColonia'		=> '',
	    				'inputMunicipio'	=> '0',
	    				'inputEstado'		=> '0',
	    				'inputCp'			=> '',
	    				'inputDirDif'		=> 0,
	    				'inputCalleF'		=> '',
	    				'inputNoextF'		=> '',
						'inputNointF'		=> '',
						'inputColoniaF'		=> '',
						'inputCpF'			=> '',
						'inputEstatus'		=> 1,
    					'userCreate'		=> 1
    				);
    				$insertEmpresa = $cEmpresas->insertRow($aDataEmpresa);
    				if($insertEmpresa['status']==1){
    					$idCompany = $insertEmpresa['id'];
    					
	    				//$idCompany = (isset($this->_dataIn['inputCodEmp'])) ? $cEmpresas->validateCodeEmp($this->_dataIn['inputCodEmp']) : -1 ;
						$this->_dataIn['inputTipo'] 	= 3;
						$nameUsuario  = $this->_dataIn['inputNombre']." ".$this->_dataIn['inputApaterno']." ".$this->_dataIn['inputAmaterno'];
	    				$this->_dataIn['dataIdEmpresa'] = $idCompany;
	    				$this->_dataIn['inputEstatus']  = ($idCompany>-1) ? 1 : 0;
	    				
						$insert = $classObject->insertRow($this->_dataIn);
						if($insert['status']){
							$idUsuario = $insert['id'];

							$cHtmlMail = new My_Controller_Htmlmailing();							
							$cHtmlMail->newUserTaxi($this->_dataIn);							
	
						 	$this->_resultOp= 'okRegisterMail';
						}else{
							$this->_aErrors['status'] = 'no-insert';
						}
    				}else{
    					$this->_aErrors['status'] = 'no-insert';
    				}	
				}else{
					$this->_aErrors['eUsuario'] = '1';
				}
    		}
    		
    		if(count($this->_aErrors)>0){
    			$dataInput['inputNombre'] 	= $this->_dataIn['inputNombre'];
    			$dataInput['inputApaterno'] = $this->_dataIn['inputApaterno'];
    			$dataInput['inputAmaterno'] = $this->_dataIn['inputAmaterno'];
    			$dataInput['inputUsuario'] 	= $this->_dataIn['inputUsuario'];
    			$dataInput['inputPhone'] 	= $this->_dataIn['inputPhone'];
    			
    			$dataInput['inputPlacas'] 	= $this->_dataIn['inputPlacas'];     
    			$dataInput['inputAno'] 		= $this->_dataIn['inputAno'];
    			    			
	    		$sColores	= $this->_dataIn['inputColor'];
	    		$sMarcas	= $this->_dataIn['inputMarca'];
	    		$sModelos   = $this->_dataIn['inputModelo'];
	    		$sPropio	= $this->_dataIn['inputTaxip'];   

				$aModelos	= $cModelos->getCbo($sMarcas);
				$this->view->aModelos   = $cFunctions->selectDb($aModelos,$sModelos);			           
    		}
    					
			$this->view->aMarcas	= $cFunctions->selectDb($aMarcas,$sMarcas);
			$this->view->aColores	= $cFunctions->selectDb($aColores,$sColores);    		
    		$this->view->taxpropio  = $cFunctions->cboOptions();
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->data		= $dataInput;    		
		}catch(Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";
        }  	
    }
}