<?php

class main_MainController extends My_Controller_Action
{
    public function init()
    {
		/*$sessions = new My_Controller_Auth();
        if($sessions->validateSession()){
            		
		}*/
    }

    public function indexAction()
    {
		try{
			/*
            $sessions = new My_Controller_Auth();
            $promos = new My_Model_Promociones();
            $distribuidores = new My_Model_Distribuidores();
            
            $fc = Zend_controller_front::getInstance();            
            $dataUserSession = $sessions->getContentSession();            
            $logged   = ($sessions->validateSession()) ? true: false;      
            $this->view->logged   = $logged;  
            $this->view->promos   = $promos->getPromociones();
        	$this->view->baseUrl  = $fc->getBaseUrl();
        	$this->view->shops	  = $distribuidores->getDistribuidores();  
        	*/          
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function loginAction(){
		/*$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();    
                
        $answer = Array('answer' => 'no-data');
		$data = $this->_request->getParams();
        if(isset($data['usuario']) && isset($data['contrasena'])){
            $usuarios = new My_Model_Usuarios();
			$validate = $usuarios->validateUser($data); //recogemos los valores y mandamos            
			if($validate){
			     $sessions = new My_Controller_Auth();
                 $sessions->setContentSession($validate);
                 $sessions->startSession();
			    $answer = Array('answer' => 'logged'); 
			}else{ 
			    $answer = Array('answer' => 'no-perm'); 
			}
        }else{
            $answer = Array('answer' => 'problem');	
        }
        echo Zend_Json::encode($answer); */               
    }
    
    public function logoutAction(){
		/*$mysession= new Zend_Session_Namespace('petSession');
		$mysession->unsetAll();
		
		Zend_Session::namespaceUnset('petSession');
		Zend_Session::destroy();
		
		$this->_redirect('/');*/
    }   
}
