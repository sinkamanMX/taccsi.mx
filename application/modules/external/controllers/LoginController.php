<?php

class external_LoginController extends My_Controller_Action
{	
    public function init()
    {
		$this->view->layout()->setLayout('layout_contact');
		
		$sessions = new My_Controller_AuthContact();
		if($sessions->validateSession()){
	        $this->view->dataUser   = $sessions->getContentSession();
		}
    }
    
    public function indexAction()
    {
		$this->view->layout()->setLayout('layout_contact');    	
		try{
			$sessions = new My_Controller_AuthContact();
			if($sessions->validateSession()){
	            $this->_redirect('/external/login/inicio');		
			}
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function loginAction(){
		try{   			
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();    
	                
	        $answer = Array('answer' => 'no-data');
			$data = $this->_request->getParams();
	        if(isset($data['usuario']) && isset($data['contrasena'])){
	        	$usuarios = new My_Model_ServUsuarios();
	        	$validate = $usuarios->validateUser($data);
	        	if($validate){
					 $dataUser = $usuarios->getDataUser($validate['ID_SRV_USUARIO']);
					 $dataUser['typeUser'] = 2;
				     $sessions = new My_Controller_AuthContact();
	                 $sessions->setContentSession($dataUser);
	                 $sessions->startSession();
				     $answer = Array('answer' => 'logged');
				}else{ 
				    $answer = Array('answer' => 'no-perm'); 
				}
	        }else{
	            $answer = Array('answer' => 'problem');	
	        }
	        echo Zend_Json::encode($answer);   
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function logoutAction(){
		$mysession= new Zend_Session_Namespace('contactSesson');
		$mysession->unsetAll();
		
		Zend_Session::namespaceUnset('contactSesson');
		Zend_Session::destroy();
		
		$this->_redirect('/');
    }  
    
    public function inicioAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();    
		
		$sessions = new My_Controller_AuthContact();
        if($sessions->validateSession()){
        	$this->_redirect('/external/main/index');        		     
		}		   	
    }    
}