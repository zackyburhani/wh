<?php
  $page_title = 'Lead Time';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_warehouse = find_all1('warehouse');
   $user = current_user();
   
?>

<?php

function GetDrivingDistance($lat1, $lat2, $long1, $long2)
    {
    $distance="";
    $duration="";
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=en";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);

    $response_a = json_decode($response, true);
    
      $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
      return array('time' => $time);   
}

$leadTime_po  = find_leadtime_po_from($user['id_warehouse']);
$leadTime     = find_leadtime_from($leadTime_po['id_po'],$user['id_warehouse']);  

function get_driving_information($start, $finish, $raw = false)
{
  if(strcmp($start, $finish) == 0)
  {
    $time = 0;
    if($raw)
    {
      $time .= ' seconds';
    }
 
    return array('distance' => 0, 'time' => $time);
  }
 
  $start  = urlencode($start);
  $finish = urlencode($finish);
 
  $distance   = 'unknown';
  $time   = 'unknown';
 
  $url = 'http://maps.googleapis.com/maps/api/directions/xml?origin='.$start.'&destination='.$finish.'&sensor=false';
  if($data = file_get_contents($url))
  {
    $xml = new SimpleXMLElement($data);
 
    if(isset($xml->route->leg->duration->value) AND (int)$xml->route->leg->duration->value > 0)
    {
      if($raw)
      {
        $distance = (string)$xml->route->leg->distance->text;
        $time   = (string)$xml->route->leg->duration->text;
      }
      else
      {
        $distance = (int)$xml->route->leg->distance->value / 1000 / 1.609344; 
        $time   = (int)$xml->route->leg->duration->value;
      }
    }
    else
    {
      throw new Exception('Could not find that route');
    }
 
    return array('distance' => $distance, 'time' => $time);
  }
  else
  {
    throw new Exception('Could not resolve URL');
  }
}


?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <i class="fa fa-clock-o"></i>
        <span>Lead Time</span>
     </strong>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePosition">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No. </th>
            <th class="text-center">ID PO</th>
            <th class="text-center">From Warehouse</th>
            <th class="text-center">To Warehouse</th>
            <th class="text-center" style="width: 150px;">Distance</th>
            <th class="text-center" style="width: 150px;">Estimated Time</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; ?>
          <?php foreach($leadTime as $time) { ?>
          <?php 

            $lat1_long1 = find_lat1_long1($time['id_po']);
            $lat2_long2 = find_lat2_long2($time['id_po']);

            $latitudeFrom  = $time['latitude1']; 
            $longitudeFrom = $time['longitude1'];
            $latitudeTo    = $time['latitude2'];
            $longitudeTo   = $time['longitude2'];

            //Calculate distance from latitude and longitude
            $theta = $longitudeFrom - $longitudeTo;
            $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $distance = ($miles * 1.609344);

          ?>
            <tr>
             <td class="text-center"><?php echo $no++."."; ?></td>
             <td class="text-center"><?php echo remove_junk(ucwords($time['id_po']))?></td>
             <td class="text-center"><?php echo remove_junk(ucwords($time['from_wh_nm']))?></td>
             <td class="text-center"><?php echo remove_junk(ucwords($time['for_wh_nm']))?></td>
             <td class="text-center"><?php echo round($distance,2)." <b>Km</b>" ?></td>
             <td class="text-center">
               <?php 

                try
                {
                  $info = get_driving_information($time['address1'], $time['address2'], true);
                  echo $info['time'];
                }
                catch(Exception $e)
                {
                    echo 'Caught exception: '.$e->getMessage()."\n";
                }

               ?>
             </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
     </div>
    </div>
  </div>
</div>


  </div>
</div>

 
<?php include_once('layouts/footer.php'); ?>

