<?php
function dame_vieja_usuario($id){
  $res = '';
  $sql = "SELECT IMAGEN
          FROM SRV_USUARIOS
          WHERE ID_SRV_USUARIO = ".$id;
  if ($qry = mysql_query($sql)){
    $res = $row->IMAGEN;
    mysql_free_result($qry);  
  }
}

function actualiza_usuario($id,$foto){
  $sResult = false;
  $sql = "UPDATE SRV_USUARIOS
          SET IMAGEN = '".$foto."'
          WHERE ID_SRV_USUARIO = ".$id;
  //$qry = mysql_query($sql); 
  if ($qry = mysql_query($sql)){
    $sResult = true;
  }

  return $sResult;
}

$uploaddir = './';      //Uploading to same directory as PHP file
$file = basename($_FILES['userfile']['name']);
$uploadFile = $file;
$randomNumber = rand(0, 99999); 
//$newName = $uploadDir . $randomNumber . $uploadFile;
$newName = $uploadDir.$uploadFile;



//if ($_FILES['userfile']['size']> 300000) {
//	exit("Your file is too large."); 
//}

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $newName)){
    $postsize = ini_get('post_max_size');   //Not necessary, I was using these
    $canupload = ini_get('file_uploads');    //server variables to see what was 
    $tempdir = ini_get('upload_tmp_dir');   //going wrong.
    $maxsize = ini_get('upload_max_filesize');
    //$con = mysql_connect("localhost","factordev","fd123");
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    $id = $_GET["id"];
    $im = $_GET["im"]; 
    if ($con){
       $base = mysql_select_db("taccsi",$con);
       $foto_vieja = dame_vieja_usuario($id);
       $result = actualiza_usuario($id,$im);
       //unlink('/var/www/html/petlocator/images/mascotas/'.$foto_vieja);
       unlink('/var/www/vhosts/taccsi.com/htdocs/public/images/taxis/'.$foto_vieja);
       
       if($result){
          echo "La foto fue actualizada correctamente. \r\n";
       } else {
          echo "No fue posible actualizar la foto. \r\n";
       }
       
       /*
       if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        echo "La foto fue actualizada correctamente. \r\n";
       } else {
        echo "No fue posible actualizar la foto. ".$id." ".$im." ".$result."  \r\n";
       }
       */
       mysql_close($con);
   }
 
}
?>
