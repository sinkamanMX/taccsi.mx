<?php
class main_RegisterController extends My_Controller_Action
{	
    public function init()
    {
    	try{
    		$this->view->layout()->setLayout('layout_blank');						
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
    				$idCompany = (isset($this->_dataIn['inputCodEmp'])) ? $cEmpresas->validateCodeEmp($this->_dataIn['inputCodEmp']) : -1 ;
					$this->_dataIn['inputTipo'] 	= 4;
					$nameUsuario  = $this->_dataIn['inputNombre']." ".$this->_dataIn['inputApaterno']." ".$this->_dataIn['inputAmaterno'];
    				$this->_dataIn['dataIdEmpresa'] = $idCompany;
    				$this->_dataIn['inputEstatus']  = ($idCompany>-1) ? 1 : 0;
    				
					$insert = $classObject->insertRow($this->_dataIn);
					if($insert['status']){
						$idTaccista = $insert['id'];
	
						$this->_dataIn['inputChofer']  = $nameUsuario;
						$this->_dataIn['userCreate']   = -1;
		            	$insertTaccsi = $cTaxis->insertRow($this->_dataIn,$idTaccista);
				 		if($insertTaccsi['status']){
				 			/**
							 * Si el taccista no tiene empresa, se le notifica al adminsitrador. 
							 */
							if($idCompany==-1){
								$bodymail   = '<h3>Estimado '.$aDataAdmin['name'].':</h3>'.
											  'Se ha enviado una solicitud para formar parte de Taccsi.com<br/>'.
											  'Los datos del solicitante son los siguientes:</br>'.
											  '<table><tr><td>Nombre Completo: </td><td>'.$nameUsuario.'</td></tr>'.
											  '<tr><td>Usuario: </td><td>'.$this->_dataIn['inputUsuario'].'</td></tr>'.
											  '<tr><td>Tel&eacute;fono: </td><td>'.$this->_dataIn['inputPhone'].'</td></tr>'.
								              '<tr><td>Nombre: </td><td>'.$nameUsuario.'</td></tr>'.
											  '</table><br/>'.
											  'Para obtener mas informaci—n es necesario ingresar al sistema de administraci&oacute;n Taccsi<br>'.
											  '<a href="http://taccsi.com/login/main/index">Da Click Aqu&iacute;</a><br/>'.
											  'o bien copia y pega en tu navegador el siguiente enlace<br>'.
											  '<b> http://taccsi.com/login/main/index</b>';
								$aMailer    = Array(
									'emailTo' 	=> $aDataAdmin['mail'],
									'nameTo' 	=> $aDataAdmin['name'],
									'subjectTo' => ('Taccsi - Registro Nuevo Taccista - Web'),
									'bodyTo' 	=> $bodymail,
								);	
							 	$enviar = $cFunctions->sendMailSmtp($aMailer);
							   	$this->_resultOp= 'okRegisterMail';
							}else{
				 				$this->_resultOp = 'okRegister';							
				 			}				 			
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