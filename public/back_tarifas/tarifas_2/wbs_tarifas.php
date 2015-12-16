<?php  
  set_time_limit(60000);
  require_once('./lib/nusoap.php'); 
  include 'lib/phpmailer.php';
  include('cFunciones.php');
  $debug_ = true;
  $miURL = 'http://201.131.96.45/wbs_tarifas';
  $server = new soap_server(); 
  $server->configureWSDL('wbs_tarifas', $miURL); 
  $server->wsdl->schemaTargetNamespace=$miURL;
  
  $server->register('calcularTarifa', // Nombre de la funcion 
                   array('lat_origen'   => 'xsd:string',
                         'lon_origen'   => 'xsd:string',
                         'lat_destino'  => 'xsd:string',
                         'lon_destino'  => 'xsd:string',
                         'itipoTaxi'    => 'xsd:string'),
                   array('return' => 'xsd:string'),
                   $miURL);

  $server->register('obtenerTarifas', // Nombre de la funcion 
                   array('idtaccsi'   => 'xsd:string'),
                   array('return'     => 'xsd:string'),
                   $miURL);  
  
  function calcularTarifa($lat_origen,$lon_origen,$lat_destino,$lon_destino,$itipoTaxi){
    $conexion       = conectar();
    $aControl       = 0;
    $costoRecorrido = 0;
    $cotrolDistancia= 0;
    $sUsaTaximetro  = '';
    $sFormaCobro    = 'por Km. recorrido.';
    $aDestino       = '';
    $sEstatus       = 0;
    $itipoTaxi      = 3;

    $aZonaOrigen = getZona($lat_origen,$lon_origen,$itipoTaxi);
    $bOnZona     = onZona($aZonaOrigen['ID_ZONA'],$lat_destino,$lon_destino);

    $aCosto      = $aZonaOrigen['N_COSTO'];
    $aCostoFz    = $aZonaOrigen['COSTO_FUERA_ZONA'];
    $banderazo   = $aZonaOrigen['BANDERAZO'];
    $cobrarCada  = $aZonaOrigen['TAXIMETRO_KMS'];
    $cobrarCadaMins  = $aZonaOrigen['TAXIMETRO_MINS'];
    $costoRecorrido += $banderazo;

    if($aZonaOrigen['USA_TAXIMETRO']==1){
     // var_dump("USA TAXIMETRO");
      $sEstatus       = 1;
      $sFormaCobro    = "cada ".@$cobrarCada." kms o ".@$cobrarCadaMins." mins.";
      $sUsaTaximetro  = 'Si';

      if($bOnZona){
        $distancia      = distancia_puntos($lat_origen,$lon_origen,$lat_destino,$lon_destino);
        $costoDistancia = ($distancia/$cobrarCada)*$aCosto;
        $costoRecorrido = $costoDistancia+$banderazo;
        $aDestino        = "Dentro de la zona";
        $cotrolDistancia = $distancia;
      }else{
        //echo "FUERA DE LA ZONA";
        $aDestino   = "Fuera de la zona";
        $route = "https://maps.googleapis.com/maps/api/directions/json?origin=".$lat_origen.",".$lon_origen."&destination=".$lat_destino.",".$lon_destino."&avoid=highways&mode=driving&key=AIzaSyBTBZIN5X9lsstyry5yGDwC4s_Z3IXkIC0";
        $json  = file_get_contents($route); // this WILL do an http request for you
        $data  = json_decode($json);
        $routes= $data->routes; 

        $aPocisiones  = Array();

        foreach($routes as $items){
          $positions  = $items->legs;
          foreach($positions as $p){
            $steps = $p->steps;
            foreach($steps as $s){
              $intLat= $s->start_location->lat;
              $inLon = $s->start_location->lng;

              if($aControl==0){
                $aPocisiones['lat'] = $intLat;
                $aPocisiones['lon'] = $inLon;
              }else{
                $inZone   = onZona($aZonaOrigen['ID_ZONA'],$intLat,$inLon);
                $sDistancia = distancia_puntos($aPocisiones['lat'],$aPocisiones['lon'],$intLat,$inLon);

                if(!$inZone){
                  $aCosto      = $aZonaOrigen['COSTO_FUERA_ZONA'];
                  //echo "es fuera de la zona <br>";
                }else{
                  $aCosto    = $aZonaOrigen['COSTO_RECORRIDO'];
                }
                //echo "--------- <br/>";
                $cotrolDistancia += $sDistancia;
                //var_dump($cotrolDistancia);
                $costoDistancia   = ($sDistancia/$cobrarCada)*$aCosto;
                //var_dump($sDistancia);
                //var_dump($cobrarCada);
                //var_dump($aCosto);
                $costoRecorrido  += $costoDistancia;
                //var_dump("costo acumulable ".$costoRecorrido);
                //echo "--------- <br/>";
                $aPocisiones['lat'] = $intLat;
                $aPocisiones['lon'] = $inLon;
              }
              $aControl++;
            }
          }       
        }
      }
    }else{        
        $sEstatus       = 1;
        $sFormaCobro    = '';
        $aZonaOrigen    = getZona($lat_origen,$lon_origen,$itipoTaxi);
        //var_dump($aZonaOrigen);
        $sUsaTaximetro  = 'No';
        $cobrarCada     = "";        
        $banderazo      = $aZonaOrigen['BANDERAZO'];
        $aCosto         = $aZonaOrigen['N_COSTO'];
        $cobrarCada     = $aZonaOrigen['TAXIMETRO_KMS'];           

      if($aZonaOrigen['N_CLASE']==3){
        //var_dump("DENTRO DE ZONA CLASE 3");
        if($bOnZona){
          $distancia      = distancia_puntos($lat_origen,$lon_origen,$lat_destino,$lon_destino);
          $aDestino       = "Dentro de la zona";       
          $costoDistancia = ($distancia/$cobrarCada)*$aCosto;
          $costoRecorrido = $costoDistancia+$banderazo;
          $cotrolDistancia = $distancia;
        }else{
          //var_dump("FUERA DE LA ZONA");
          //var_dump("FUER A DE EN ZONA");
          $aDestino   = "Fuera de la zona";
          $route = "https://maps.googleapis.com/maps/api/directions/json?origin=".$lat_origen.",".$lon_origen."&destination=".$lat_destino.",".$lon_destino."&avoid=highways&mode=driving&key=AIzaSyBTBZIN5X9lsstyry5yGDwC4s_Z3IXkIC0";
          $json  = file_get_contents($route); // this WILL do an http request for you
          $data  = json_decode($json);
          $routes= $data->routes; 

          $aPocisiones  = Array();          

          foreach($routes as $items){
            $positions  = $items->legs;
            foreach($positions as $p){
              $steps = $p->steps;
              foreach($steps as $s){
                $intLat= $s->start_location->lat;
                $inLon = $s->start_location->lng;

                if($aControl==0){
                  $aPocisiones['lat'] = $intLat;
                  $aPocisiones['lon'] = $inLon;
                }else{
                  $inZone     = onZona($aZonaOrigen['ID_ZONA'],$intLat,$inLon);
                  $sDistancia = distancia_puntos($aPocisiones['lat'],$aPocisiones['lon'],$intLat,$inLon); 

                  if(!$inZone){
                    $aCosto         = $aZonaOrigen['COSTO_FUERA_ZONA'];
                    $cobrarCada     = $aZonaOrigen['KM_FUERA_ZONA'];      
                  }else{
                    $aCosto         = $aZonaOrigen['N_COSTO'];
                    $cobrarCada     = $aZonaOrigen['TAXIMETRO_KMS'];      
                  }

                  $costoDistancia  = ($sDistancia/$cobrarCada)*$aCosto;
                  $costoRecorrido  += $costoDistancia; 
                  $cotrolDistancia += $sDistancia;            

                  $aPocisiones['lat'] = $intLat;
                  $aPocisiones['lon'] = $inLon; 
                }
                $aControl++;
              }
            }       
          }
          $costoRecorrido += $banderazo;
        }
      }else{        
        if($bOnZona){
          //var_dump("EN ZONA");
          $distancia      = distancia_puntos($lat_origen,$lon_origen,$lat_destino,$lon_destino);
          $aDestino       = "Dentro de la zona";
          $banderazo      = 0;
          $aCosto         = $aZonaOrigen['COSTO_NT'];
          $costoRecorrido = $aZonaOrigen['COSTO_NT'];
          $cotrolDistancia= $distancia;
        }else{
          //var_dump("FUER A DE EN ZONA");
          $aDestino   = "Fuera de la zona";
          $route = "https://maps.googleapis.com/maps/api/directions/json?origin=".$lat_origen.",".$lon_origen."&destination=".$lat_destino.",".$lon_destino."&avoid=highways&mode=driving&key=AIzaSyBTBZIN5X9lsstyry5yGDwC4s_Z3IXkIC0";
          $json  = file_get_contents($route); // this WILL do an http request for you
          $data  = json_decode($json);
          $routes= $data->routes; 

          $aPocisiones  = Array();
          
          foreach($routes as $items){
            $positions  = $items->legs;
            foreach($positions as $p){
              $steps = $p->steps;
              foreach($steps as $s){
                $intLat= $s->start_location->lat;
                $inLon = $s->start_location->lng;

                if($aControl==0){
                  $aPocisiones['lat'] = $intLat;
                  $aPocisiones['lon'] = $inLon;
                }else{
                  $inZone     = onZona($aZonaOrigen['ID_ZONA'],$intLat,$inLon);
                  $sDistancia = distancia_puntos($aPocisiones['lat'],$aPocisiones['lon'],$intLat,$inLon); 

                  if(!$inZone){
                    $aZonaget   = getZonaInit($intLat,$inLon,$itipoTaxi);
                    $aCosto     = $aZonaget['COSTO_NT'];

                    if($aZonaget['COSTO_ACUMULABLE']==1){
                      $costoRecorrido += $aCosto;
                    }else{
                      $costoRecorrido  = $aCosto;
                    }
                  }else{
                    $aCosto       = $aZonaOrigen['COSTO_NT'];
                    if($aZonaOrigen['COSTO_ACUMULABLE']==1){
                      $costoRecorrido += $aCosto;
                    }else{
                      $costoRecorrido  = $aCosto;
                    }
                  }   
                  //var_dump($aZonaOrigen);

                  $cotrolDistancia += $sDistancia;            

                  $aPocisiones['lat'] = $intLat;
                  $aPocisiones['lon'] = $inLon; 
                }
                $aControl++;
              }
            }       
          }        
        }
      }      
    }

    $sResult =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>'.$sEstatus.'</Status>
                   <taximetro>'.$sUsaTaximetro.'</taximetro>
                   <banderazo>'.$banderazo.'</banderazo>
                   <destino>'.$aDestino.'</destino>
                   <costoRecorrido>'.round($costoRecorrido,2).'</costoRecorrido>
                   <distancia>'.round($cotrolDistancia,2).'</distancia>
                   <formacobro>'.$sFormaCobro.'</formacobro>
                 </Response>
               </space>';

    return new soapval('return', 'xsd:string', $sResult);
  }  

  function obtenerTarifas($idtaccista){
    $conexion   = conectar();
    $dataTaccsi = getData($idtaccista);
    $aZones     = getZonasByTarifa($dataTaccsi['ID_TARIFA']);

    $sResult =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                    <idTarifa>'.$dataTaccsi['ID_TARIFA'].'</idTarifa>
                    <descripcion>'.$dataTaccsi['DESCRIPCION'].'</descripcion>
                    <taximetro>'.$dataTaccsi['USA_TAXIMETRO'].'</taximetro>
                    <banderazo>'.$dataTaccsi['BANDERAZO'].'</banderazo>
                    <taximetro_mts>'.$dataTaccsi['TAXIMETRO_KMS'].'</taximetro_mts>
                    <taximetro_mins>'.$dataTaccsi['TAXIMETRO_MINS'].'</taximetro_mins>
                    <costo_recorrido>'.$dataTaccsi['COSTO_RECORRIDO'].'</costo_recorrido>
                    <hor_inicio>'.$dataTaccsi['HORARIO_INICIO'].'</hor_inicio>
                    <hor_fin>'.$dataTaccsi['HORARIO_FIN'].'</hor_fin>
                    <tax_fhorario>'.$dataTaccsi['TAX_FUERA_HORARIO'].'</tax_fhorario>
                    <banderazo_fzona><'.$dataTaccsi['BAN_FUERA_HORARIO'].'</banderazo_fzona>
                    <tax_mts_zona>'.$dataTaccsi['KM_FUERA_ZONA'].'</tax_mts_zona>
                    <tax_mins_zona>'.$dataTaccsi['MIN_FUERA_ZONA'].'</tax_mins_zona>
                    <costo_recorrido_fz>'.$dataTaccsi['COSTO_FUERA_ZONA'].'</costo_recorrido_fz>
                    <zonas_tarifa>
                      '.$aZones.'
                    </zonas_tarifa>
                 </Response>
               </space>';

    return new soapval('return', 'xsd:string', $sResult);                 
  }
  
  $server->service(@$HTTP_RAW_POST_DATA);   
?>
