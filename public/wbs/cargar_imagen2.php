<?php




$target_path = "/var/www/vhosts/taccsi.com/htdocs/public/images/taxis/";
$target_path = $target_path.basename($_FILES['uploadedfile']['name']);
//$file = basename($_FILES['uploadedfile']['name']);
if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$target_path)) {
  //echo "The file ".basename($_FILES['uploadedfile']['name'])." has been uploaded";
 
      echo "OK";

  
} else {
  echo utf8_decode("No fue posible actualizar la fotografía");
}
?>
