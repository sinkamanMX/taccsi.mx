<?php
class admin_ValdocsController extends My_Controller_Action
{
	protected $_clase = 'mvaldocs';
	
    public function init()
    {
    	try{
    		$this->view->layout()->setLayout('admin_layout');
    		
    		$sessions = new My_Controller_Auth();
			$perfiles = new My_Model_Perfiles();
			$fc 	  = Zend_controller_front::getInstance();
	        if($sessions->validateSession()){
		        $this->_dataUser   = $sessions->getContentSession();   	
		        $this->_baseUrl    = $fc->getBaseUrl();	
			}else{
				$this->_redirect("/login/main/index");
			}    		
			$this->view->dataUser   = $this->_dataUser;			
			$this->view->modules    = $perfiles->getModules($this->_dataUser['TIPO_USUARIO']);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
			$this->view->BaseUrl    = $this->_baseUrl;
						
			$this->_dataIn 					= $this->_request->getParams();
			$this->_dataIn['userCreate']	= $this->_dataUser['ID_USUARIO'];
			$this->_dataIn['dataIdEmpresa'] = $this->_dataUser['ID_EMPRESA'];
	    	if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];				
			}
			
			if(isset($this->_dataIn['catId'])){
				$this->_idUpdate = $this->_dataIn['catId'];				
			}			
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
    public function indexAction(){
    	try{
			$classObject = new My_Model_Kardex();
			$this->view->datatTable = $classObject->getDataToValidate();
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    } 

    public function getinfoAction(){
    	try{
			$cKardex		= new My_Model_Kardex();
			$cUsuario  		= new My_Model_Usuarios();			
			$aDataKardex	= Array();
			$dataInfo		= Array();
			
			if($this->_idUpdate >-1){
				$aDataKardex = $cKardex->getData($this->_idUpdate);
				$dataInfo  	 = $cUsuario->getDataUser($this->_idUpdate);	
			}
			
			if($this->_dataOp=='validateFields'){
				$update = $cKardex->updateValidate($this->_dataIn);
				if($update['status']){
					$aDataKardex = $cKardex->getData($this->_idUpdate);

					if($aDataKardex['VAL_LICENCIA_FRENTE']   =="2" && $aDataKardex['VAL_LICENCIA_REVERSA']=="2" 
						&& $aDataKardex['VAL_IDENTIFICACION']=="2" && $aDataKardex['VAL_COMP_DOMICILIO'] =="2"
						&& $aDataKardex['VAL_CEDULA_FISCAL'] =="2" && $aDataKardex['VAL_ESTADO_CUENTA']  =="2"){
							
							$cHtmlMail = new My_Controller_Htmlmailing();							
							$cHtmlMail->documentsValidate($dataInfo);
					}
					
					$this->validateErrors($this->_dataIn,$dataInfo);
				}
			}
			
			$this->view->aDataKardex= $aDataKardex;    		
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;		
			$this->view->resultOp   = $this->_resultOp;			
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 
        
    } 

    function validateErrors($data,$dataUser){
    	$aRespuesta  = $data['iRespuesta'];
    	$aCancel     = '';
								
		if($data['sOption']=='licencefront' && $aRespuesta==3){
			$aCancel  .= ($aRespuesta==3) ? 'La licencia (Frente) es inv&aacute;lida <br/>': '';
		}
		
		if($data['sOption']=='licenceback' && $aRespuesta==3){
			$aCancel  .= ($aRespuesta==3) ? 'La licencia (Reversa) es inv&aacute;lida <br/>': '';
		}
						
		if($data['sOption']=='identification' && $aRespuesta==3){
			$aCancel  .= ($aRespuesta==3) ? 'La identificaci&oacute;n es inv&aacute;lida <br/>': '';
		}

		if($data['sOption']=='comp_domicilio' && $aRespuesta==3){
			$aCancel  .= ($aRespuesta==3) ? 'El comprobante de domicilio es inv&aacute;lida <br/>': '';
		}

		if($data['sOption']=='ced_fiscal' && $aRespuesta==3){
			$aCancel  .= ($aRespuesta==3) ? 'La c&eacute;dula fiscal es inv&aacute;lida <br/>': '';
		}
						
		if($data['sOption']=='antecedentes' && $aRespuesta==3){
			$aCancel  .= ($aRespuesta==3) ? 'El documento de Antecedentes es inv&aacute;lido <br/>': '';
		}

		if($data['sOption']=='edocuenta' && $aRespuesta==3){
			$aCancel  .= ($aRespuesta==3) ? 'El estado de cuenta es inv&aacute;lido <br/>': '';
		}
		
		if($aCancel!=""){
			$cHtmlMail = new My_Controller_Htmlmailing();							
			$cHtmlMail->documentsinValidate($dataUser,$aCancel);
		}
    }
}