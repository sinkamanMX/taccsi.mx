<?php

class main_ImageController extends My_Controller_Action
{
    public function init()
    {

    }

    public function indexAction()
    {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();          
		$data = $this->_request->getParams();         
               
        $height = $data['height']; 
        $width  = $data['width']; 
        $imgBase= $data['img'];
                 
        $dataImage = getimagesize($imgBase);
 
        if($dataImage[2]==1){$img = @imagecreatefromgif($imgBase);} 
        if($dataImage[2]==2){$img = @imagecreatefromjpeg($imgBase);} 
        if($dataImage[2]==3){$img = @imagecreatefrompng($imgBase);}
         
        $ratio  = ($dataImage[0] / $width); 
        $altura = ($dataImage[1] / $ratio); 
         
        if(isset($data['relatio']) && $data['relatio'] == true){
        	$altura  = $height;	
        }else{
			if($altura>$height){
	        	$anchura2= $height*$width/$altura;
	        	$altura  = $height;
	        	$width   = $anchura2;}        	
        }        

        $thumb = imagecreatetruecolor($width,$altura);
         
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $width, $altura, $dataImage[0], $dataImage[1]); 
        
       
        if($dataImage[2]==1){header("Content-type: image/gif"); imagegif($thumb);} 
        if($dataImage[2]==2){header("Content-type: image/jpeg");imagejpeg($thumb);} 
        if($dataImage[2]==3){header("Content-type: image/png");imagepng($thumb); } 
        imagedestroy($thumb);                 
    }
}