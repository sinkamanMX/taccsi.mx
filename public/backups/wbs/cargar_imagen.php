<?php


function dame_vieja_taccsi($id){
  $res = '';
  $sql = "SELECT IMAGEN
          FROM ADMIN_TAXIS
          WHERE ID_TAXI = ".$id;
  if ($qry = mysql_query($sql)){
    $res = $row->IMAGEN;
    mysql_free_result($qry);  
  }

}

function dame_vieja_taccsita($id){
  $res = '';
  $sql = "SELECT FOTO
          FROM ADMIN_USUARIOS
          WHERE ID_USUARIO = ".$id;
  if ($qry = mysql_query($sql)){
    $res = $row->IMAGEN;
    mysql_free_result($qry);  
  }
}

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


function actualiza_taccsi($id,$foto){
  $sql = "UPDATE ADMIN_TAXIS
          SET IMAGEN = '".$foto."'
          WHERE ADMIN_USUARIOS_ID_USUARIO = ".$id;
  $qry = mysql_query($sql); 
}

function actualiza_taccsista($id,$foto){
  $sql = "UPDATE ADMIN_USUARIOS
          SET FOTO = '".$foto."'
          WHERE ID_USUARIO = ".$id;
  $qry = mysql_query($sql); 
}

function actualiza_usuario($id,$foto){
   $sql = "UPDATE SRV_USUARIOS
          SET IMAGEN = '".$foto."'
          WHERE ID_SRV_USUARIO = ".$id;
  $qry = mysql_query($sql); 
}

$target_path = "/var/www/vhosts/taccsi.com/htdocs/public/images/taxis/";
$target_path = $target_path.basename($_FILES['uploadedfile']['name']);
//$file = basename($_FILES['uploadedfile']['name']);
if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$target_path)) {
  //echo "The file ".basename($_FILES['uploadedfile']['name'])." has been uploaded";
  $id = $_GET["id"];
  $im = $_GET["im"];
  $tpo= $_GET["tpo"];
  //$ex = $_GET["ex"];
  //$foto = $im.'.'.$ex;
  //$con = mysql_connect("localhost","factordev","fd123");
  $con = mysql_connect("localhost","dba","t3cnod8A!");

  if ($con){
    //$base = mysql_select_db("petlocator",$con);
    $base = mysql_select_db("taccsi",$con);

    if($tpo=="TACCSISTA"){
      $foto_vieja = dame_vieja_taccsita($id);
      actualiza_taccsista($id,$im);
      unlink('/var/www/vhosts/taccsi.com/htdocs/public/images/taxis/'.$foto_vieja);
      echo "OK";
      mysql_close($con);
    }else if($tpo=="TACCSI"){
      $foto_vieja = dame_vieja_taccsi($id);
      actualiza_taccsi($id,$im);
      unlink('/var/www/vhosts/taccsi.com/htdocs/public/images/taxis/'.$foto_vieja);
      echo "OK_taccsi";
      mysql_close($con);
    }else if($tpo=="USUARIO"){
      $foto_vieja = dame_vieja_usuario($id);
      actualiza_usuario($id,$im);
      unlink('/var/www/vhosts/taccsi.com/htdocs/public/images/taxis/'.$foto_vieja);
      echo "OK";
      mysql_close($con);
    }else{
      echo "El tipo no es valido";
    }

    
  }
} else {
  echo utf8_decode("No fue posible actualizar la fotografía");
}
?>
