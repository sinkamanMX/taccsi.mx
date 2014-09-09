<?php
class admin_MainController extends My_Controller_Action
{
	protected $_clase = '';
	
    public function init()
    { 
		$sessions = new My_Controller_Auth();
		$perfiles = new My_Model_Perfiles();
        if($sessions->validateSession()){
	        $this->_dataUser   = $sessions->getContentSession();   		
		}else{
			$this->_redirect("/login/main/index");
		}
		
		$this->view->layout()->setLayout('admin_layout');	
		$this->view->dataUser   = $this->_dataUser;
		$this->view->modules    = $perfiles->getModules($this->view->dataUser['TIPO_USUARIO']);
		$this->view->moduleInfo = $perfiles->getDataMenu($this->_clase);
    }
    
    public function indexAction(){
    	try{
    		/*	
	    	$sessions = new My_Controller_Auth();
	    	$usuarios = new My_Model_Usuarios();
	    	
	    	
	    	$dataUserSession = $sessions->getContentSession();
	    	$dataUser = $usuarios->getDataUser($dataUserSession['IdUsuario']);
    		
	    	$this->view->dataUser = $dataUser;    	
    	    	
	    	$viajes  = new My_Model_Viajes();
	    	$resumen = $viajes->getResumen();
	    	
	    	$aresumen = Array();
	    	foreach($resumen as $key => $items){
	    		$indice = $items['SRV_ESTATUS_ID_SRV_ESTATUS'];
	    		@$aresumen[$indice]['total']++;
	    	}
	    	
	    	$this->view->Resumen = $aresumen;*/
    	} catch (Zend_Exception $e) {
            /*echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";     */
        }
    	
    	$this->view->layout()->setLayout('admin_layout');
    }
    
    public function inicioAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$profile = new My_Model_Perfiles();
		
		if($this->_dataUser['TIPO_USUARIO']!="" && $this->_dataUser['TIPO_USUARIO']!="NULL"){
	        $default = $profile->getModuleDefault($this->_dataUser['ID_TIPO_USUARIO']);	
            if(count($default)>0){
            	$this->_redirect($default['SCRIPT']);
            }else{
            	$this->_redirect('/admin/main/index');	
            }			
		}else{
        	$this->_redirect('/admin/main/errorprofile');
		} 
    }
    
    public function errorprofileAction(){
    	
    }
    
    /*
    public function getinfoAction(){
    	try{
    		$data    = $this->_request->getParams();
	    	$viajes  = new My_Model_Viajes();
	    	$resumen = $viajes->getViajes($data['SRV_ESTATUS_ID_SRV_ESTATUS']);

	    	$this->view->Resumen = $resumen;
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    	
    	$this->view->layout()->setLayout('admin_layout');    	
    }
    
    public function getmoreAction(){
    	try{
    		$data    = $this->_request->getParams();
	    	$viajes  = new My_Model_Viajes();
	    	$resumen = $viajes->infoViaje($data['ID_VIAJES']);
	    	
	    	if($resumen['SRV_ESTATUS_ID_SRV_ESTATUS']==0){
				$nameEstatus = 'Sin Asignar';
	    	}elseif($resumen['SRV_ESTATUS_ID_SRV_ESTATUS']==1){
	    		$nameEstatus = 'Terminados';
	    	}else{
	    		$nameEstatus = 'Cancelados';
	    	}
	    	 			
	    	
	    	$resumen['nameEstatus'] = $nameEstatus;
	    	$this->view->Resumen = $resumen;
	    	
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    	
    	$this->view->layout()->setLayout('admin_layout');    	
    }*/
}