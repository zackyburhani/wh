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

$leadTime_po = find_leadtime_po($user['id_warehouse']);

$leadTime   = find_leadtime($leadTime_po['id_po'],$user['id_warehouse']);

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
            <th class="text-center">From Warehouse</th>
            <th class="text-center">To Warehouse</th>
            <th class="text-center" style="width: 150px;">Distance</th>
            <!-- <th class="text-center" style="width: 150px;">Estimated Time</th> -->
            <th class="text-center" style="width: 150px;">Polylines</th>
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
             <td class="text-center"><?php echo $no++; ?></td>
             <td class="text-center"><?php echo remove_junk(ucwords($time['from_wh']))?></td>
             <td class="text-center"><?php echo remove_junk(ucwords($time['for_wh']))?></td>
             <td class="text-center"><?php echo round($distance,2)." <b>Km</b>" ?></td>
             <!-- <td class="text-center">    
              <?php 

              $dist = GetDrivingDistance($latitudeFrom,$latitudeTo,$longitudeFrom,$longitudeTo);
              if($dist) {
                echo $dist['time']; 
              } else {
                  echo "Uncaught Location";
              }

              ?>

             </td> -->
             <td class="text-center"><button data-target="#target<?php echo $time['id_po'] ?>" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plane"></i></button></td>
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

<!-- MODAL ADD NEW PACKAGE -->
<?php foreach($leadTime as $time) { ?>
<div class="modal fade" id="target<?php echo $time['id_po'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-plane"></i> Polylines</h4>
      </div>
      <div class="modal-body">

        <div id="map"></div>


        <input type="hidden" id="latitude1"  value="<?php echo $latitudeFrom  = $time['latitude1'];  ?>">
        <input type="hidden" id="longitude1" value="<?php echo $longitudeFrom = $time['longitude1']; ?>">
        <input type="hidden" id="latitude2"  value="<?php echo $latitudeTo    = $time['latitude2']; ?>">
        <input type="hidden" id="longitude2" value="<?php echo $longitudeTo   = $time['longitude2']; ?>">

        <script>

          var lat1  = parseFloat(document.getElementById('latitude1').value);
          var long1 = parseFloat(document.getElementById('longitude1').value);
          var lat2  = parseFloat(document.getElementById('latitude2').value);
          var long2 = parseFloat(document.getElementById('longitude2').value);

          function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 4,
              center: {lat: lat1, lng: long1},
              mapTypeId: 'terrain'
            });

            console.log(lat2);

            var flightPlanCoordinates = [
              {lat: lat1, lng: long1},
              {lat: lat2, lng: long2}
            ];
            var flightPath = new google.maps.Polyline({
              path: flightPlanCoordinates,
              geodesic: true,
              strokeColor: '#3175b8',
              strokeOpacity: 1.0,
              strokeWeight: 2
            });

            flightPath.setMap(map);
          }
        </script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjdeO9J1CF_PRTS9aOjZ9-Scg8dIlhxGg&callback=initMap">
        </script>

      </div>
      <div class="modal-footer">
        <button type="button" title="Close" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
      </div>
    </div>
  </div>
</div>
  </div>
</div>
<?php } ?>
<!-- END MODAL ADD NEW PACKAGE -->

 
<?php include_once('layouts/footer.php'); ?>

