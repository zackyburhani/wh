<?php
error_reporting(0);
  $page_title = 'Receive Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
   // $po = find_all1('detil_po');
   $user               = current_user();
   $all_location       = find_all_location('location',$user['id_warehouse']);
   $status1            = status_shipment($user['id_warehouse']);
   $all_warehouse_id   = find_warehouse_id($user['id_warehouse']);
   $all_package        = find_all_package('package',$user['id_warehouse']);
   $all_categories     = find_all_categories('categories',$user['id_warehouse']);
   $join_subcategories = find_allSubcategories($user['id_warehouse']);

  //leadtime
  $warehouse_lt = find_leadtime_wh($user['id_warehouse']);
  $leadTime_po  = find_leadtime_po($warehouse_lt['for_wh'],$warehouse_lt['from_wh']);
 
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


  if (isset($_GET['search_po'])) {
    $no = $_POST['no_po'];   
  }
?>
<?php
if(isset($_POST['update_po'])){
  $req_field = array('id_subcategories','id_package','id_location');
  validate_fields($req_field);

  $status           = "Success";
  $idpo             = remove_junk($db->escape($_POST['id_po']));
  $iditem           = remove_junk($db->escape($_POST['id_item']));
  $id_location      = remove_junk($db->escape($_POST['id_location']));
  $id_package       = remove_junk($db->escape($_POST['id_package']));
  $id_subcategories = remove_junk($db->escape($_POST['id_subcategories']));
  $safety_stock     = remove_junk($db->escape($_POST['safety_stock']));

  //insert table shipment 
  $idshipment   = autonumber('id_shipment','shipment');
  $dateshipment = date("Y-m-d");
  $idpo         = remove_junk($db->escape($_POST['id_po']));
  $idwarehouse  = $user["id_warehouse"];
  $idemployer   = $user["id_employer"];

  $query2  = "INSERT INTO shipment (";
  $query2 .=" id_shipment,date_shipment,id_po,id_warehouse,id_employer";
  $query2 .=") VALUES (";
  $query2 .=" '{$idshipment}', '{$dateshipment}', '{$idpo}', '{$idwarehouse}', '{$idemployer}'";
  $query2 .=")";

  //insert table product
  $all_item = find_all_product_shipment($iditem);
  $qty_new  = insert_new_id($user['id_warehouse']);

  $id_item_new  = autonumber('id_item','item');
  $nm_item_new  = $all_item['nm_item'];
  $colour_new   = $all_item['colour'];
  $width_new    = $all_item['width'];
  $height_new   = $all_item['height'];
  $length_new   = $all_item['length'];
  $diameter_new = $all_item['diameter'];
  $weight_new   = $all_item['weight'];
  $stock_new    = $qty_new['qty'];

  $fetch_package = find_package_id($id_package);
  if($stock_new >= $fetch_package['jml_stock']){
    $session->msg('d',"QTY Package Is Not Enough");
    redirect('move_product.php', false);
  }


  if(find_by_itemName($nm_item_new,$user['id_warehouse']) === false ){

    $id_st         = get_id_product($nm_item_new,$user['id_warehouse']);
    $id_item_fetch = $id_st['id_item'];
    $stock_fetch   = get_stock($id_st['id_item']);
    $get_qty       = update_new_id($user['id_warehouse'],$iditem);
    $stock1        = $get_qty['qty']+$stock_fetch['stock'];

    $query_up  = "UPDATE item SET ";
    $query_up .= "nm_item = '{$nm_item_new}',colour = '{$colour_new}',id_subcategories = '{$id_subcategories}',width = '{$width_new}',height = '{$height_new}',length = '{$length_new}',diameter = '{$diameter_new}',weight = '{$weight_new}',stock = '{$stock1}',id_package = '{$id_package}',id_location = '{$id_location}', safety_stock = '{$safety_stock}'";
    $query_up .= "WHERE id_item = '{$id_item_fetch}' and nm_item = '{$nm_item_new}'";
    $db->query($query_up); 

  } else {
    $query3  = "INSERT INTO item (";
    $query3 .=" id_item,nm_item,colour,width,height,length,diameter,weight,stock,safety_stock,id_package,id_subcategories,id_location";
    $query3 .=") VALUES (";
    $query3 .=" '{$id_item_new}', '{$nm_item_new}', '{$colour_new}', '{$width_new}', '{$height_new}', '{$length_new}','{$diameter_new}', '{$weight_new}', '{$stock_new}','{$safety_stock}','{$id_package}','{$id_subcategories}','{$id_location}'";
    $query3 .=")";  
    $db->query($query3);
  }

  //reduce area consumed 
  $consumed     = $all_warehouse_id['heavy_consumed'];
  $heavy_max    = $all_warehouse_id['heavy_max'];
  $id_warehouse = $all_warehouse_id['id_warehouse']; 
  $reduced      = ($weight_new*$stock_new )+$consumed;

  $query4  = "UPDATE warehouse SET ";
  $query4 .= "heavy_consumed='{$reduced}' ";
  $query4 .= "WHERE id_warehouse = '{$id_warehouse}'";

  //insert table bpack
  $get_id = find_new_idItem($nm_item_new,$colour_new,$id_package,$id_subcategories,$id_location);
  $id_item2 = $get_id['id_item'];
  $id_bpack = autonumber('id_bpack','bpack');
  $count    = $weight_new*$stock_new;
  $sql2  = "INSERT INTO bpack (id_bpack,id_package,id_item,qty,total)";
  $sql2 .= " VALUES ('{$id_bpack}','{$id_package}','{$id_item2}','{$stock_new}','{$count}')";

  $pack = find_package_id($id_package);
  $up_pack = $pack['jml_stock']-$stock_new;      
  $sql3  = "UPDATE package SET jml_stock = '$up_pack'";
  $sql3 .= " WHERE id_package='{$id_package}'";

  if(empty($errors)){
        $sql = "UPDATE detil_po SET status='{$status}'";
       $sql .= " WHERE id_item='{$iditem}' and id_po = '{$idpo}'";
     $result = $db->query($sql);
     if($result) {
      $db->query($query2);
      $db->query($query4);
      $db->query($sql2);
      $db->query($sql3);
       $session->msg("s", "Successfully Approved");
       redirect('move_product.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Approved");
       redirect('move_product.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('move_product.php',false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
  <div class="col-md-13">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
            <i class="fa fa-truck"></i>
            <span>Receive Product</span>
          </strong>
        </div>
        
        <div class="panel-body">
          <table class="table table-bordered" id="datatableProduct">
           <thead>
              <tr>
               <th class="text-center" style="width: 1px;">No.</th>
                <th class="text-center"> Id PO</th>
                <th class="text-center"> From Warehouse</th>
                <th class="text-center"> Shipment Date</th>
                <th class="text-center"> ID Item </th>
                <th class="text-center"> Quantity </th>
                <th class="text-center"> Status </th>
                <th class="text-center" width="150px"> Actions </th>
                <!-- <th class="text-center"> Print </th> -->
              </tr>
            </thead>
            <tbody>
              <?php $no=1; ?>
              <?php foreach ($status1 as $po1):?>     
                <input type="hidden" name="idpo" value="<?php echo remove_junk ($po1["id_po"])?>">
               <tr>
                <td class="text-center"> <?php echo $no++.".";?></td>
                <td class="text-center"> <?php echo remove_junk($po1['id_po']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['from_wh']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['date_po']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['id_item']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['qty']); ?></td>
                <td class="text-center"><span class="label label-danger"> <?php echo remove_junk($po1['status']); ?></span></td>
                <td class="text-center">
                  <button class="btn btn-md btn-primary" name="update_status" data-toggle="modal" data-target="#leadTime<?php echo $po1['id_po']?>" title="Lead Time">
                    <i class="fa fa-clock-o"></i>
                  </button>
                  <button class="btn btn-md btn-success" name="update_status" data-toggle="modal" data-target="#status<?php echo $po1['id_item']?>" title="Approve">
                    <i class="glyphicon glyphicon-ok"></i>
                  </button>
                  <a href="report_po.php?id=<?php echo $po1['id_po'] ?>" class="btn btn-danger" role="button" title="Print PO">
                    <i class="glyphicon glyphicon-print"></i>
                  </a>
               </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>


<?php foreach($status1 as $a_location): ?>
<div class="modal fade" id="status<?php echo $a_location['id_item'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-truck"></i> Approve </h4>
      </div>
      <div class="modal-body">
      <form method="post" action="move_product.php">

        <div class="form-group">
          <label for="name" class="control-label">Select Category</label>
            <select class="form-control" id="category" name="id_categories">
              <?php if($all_categories == null) { ?>
                <option value="">-</option>
                  <?php } else { ?>
                  <?php foreach($all_categories as $row){ ?>
                  <option value="<?php echo remove_junk($row['id_categories']); ?>"><?php echo remove_junk(ucwords($row['nm_categories'])); ?></option>
                  <?php } ?>  
                  <?php } ?>
             </select>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">Select Subcategory</label>
            <select class="form-control" id="sub_category" name="id_subcategories" required>
              <?php if($join_subcategories== null) { ?>
                <option value=" ">-</option>
                  <?php } else { ?>
                  <?php foreach($join_subcategories as $row2){ ?>
                  <option class="<?php echo $row2['id_categories']; ?>" value="<?php echo remove_junk($row2['id_subcategories']); ?>"><?php echo remove_junk(ucwords($row2['nm_subcategories'])); ?>
                  </option>
                  <?php } ?>
                  <?php } ?>  
            </select>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">Select Package</label>
            <select class="form-control" name="id_package">
            <?php if($all_package == null) { ?>
              <option value="">-</option>
              <?php } else { ?>
              <?php foreach($all_package as $row3){ ?>
              <option value="<?php echo remove_junk($row3['id_package']); ?>"><?php echo remove_junk(ucwords($row3['nm_package'])); ?></option>
              <?php } ?> 
              <?php } ?> 
            </select>
        </div>
        <div class="form-group">
          <label for="name" class="control-label">Select Location Warehouse</label>
            <select class="form-control" name="id_location" required>
            <?php if($all_location == null) { ?>
              <option value="">-</option>
            <?php } else { ?>
            <?php foreach($all_location as $row){ ?>
              <option value="<?php echo remove_junk($row['id_location']); ?>"><?php echo remove_junk(ucwords($row['unit'])); ?></option>
            <?php } ?>  
            <?php } ?>
            </select>
          </div>

          <input type="hidden" value="<?php echo $a_location['id_po'];?>" name="id_po">
          <input type="hidden" value="<?php echo $id_itemCheck = $a_location['id_item'];?>" name="id_item">

          <!-- check item exst or not -->
          <?php 

            $all_item = find_all_product_shipment($id_itemCheck);
            $nm_item_new = $all_item['nm_item'];

          ?>

          <?php if(find_by_itemName($nm_item_new,$user['id_warehouse']) === true ) { ?>
            <div class="form-group">
              <label class="control-label">Safety Stock</label>
              <input type="number" min="0" class="form-control" required placeholder="Safety Stock" name="safety_stock">
            </div>
          <?php } ?>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
          <button type="submit" name="update_po" class="btn btn-success"><i class="fa fa-check"></i> Accept</button>
        </div>

        </form>
    </div>
  </div>
</div>
<?php endforeach;?>

<?php foreach($status1 as $a_location): ?>
<div class="modal fade" id="leadTime<?php echo $a_location['id_po'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-clock-o"></i> Lead Time </h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center">ID PO</th>
            <th class="text-center">From Warehouse</th>
            <th class="text-center">To Warehouse</th>
            <th class="text-center" style="width: 150px;">Distance</th>
            <th class="text-center" style="width: 150px;">Estimated Time</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; ?>
          <?php $leadTime = find_leadtime($a_location['id_po'],$user['id_warehouse'],$warehouse_lt['for_wh']);?>
          <?php foreach($leadTime as $time) { ?>
          <?php 

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
                    echo '<i>Could Not Find The Route</i>';
                }

               ?>
             </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach;?>



<script src="jquery-1.10.2.min.js"></script>
<script src="jquery.chained.min.js"></script>
<script>
  $("#sub_category").chained("#category");
</script>

<?php include_once('layouts/footer.php'); ?>