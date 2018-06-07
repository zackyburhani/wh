<?php
  $page_title = 'Add Warehouse';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_categories = find_all1('warehouse');
?>

<!-- INSERT WAREHOUSE -->
<?php
 if(isset($_POST['add_warehouse'])){
   $req_field  = array('warehousename','country','address','status','heavymax');
   validate_fields($req_field);
   $id_wh     = autonumber('id_warehouse','warehouse');
   $cat_name  = remove_junk($db->escape($_POST['warehousename']));
   $country   = remove_junk($db->escape($_POST['country']));
   $address   = remove_junk($db->escape($_POST['address']));
   $status    = remove_junk($db->escape($_POST['status']));
   $heavymax  = remove_junk($db->escape($_POST['heavymax']));

   $convert_max    = remove_junk($db->escape($_POST['convert_max']));

  //convert weight
  if($convert_max == "max_kilograms"){
    $heavymax = $heavymax;
  } else if($convert_max == "max_ton") {
    $heavymax = $heavymax*1000;
  } 

   $consumed  = 0;
   if(empty($errors)){
      $sql  = "INSERT INTO warehouse (id_warehouse,nm_warehouse,country,address,status,heavy_max,heavy_consumed)";
      $sql .= " VALUES ('{$id_wh}','{$cat_name}','{$country}','{$address}','{$status}','{$heavymax}','{$consumed}')";

      $getAllWarehouseName = "SELECT nm_warehouse FROM warehouse where nm_warehouse = '$cat_name'";
      $ada=$db->query($getAllWarehouseName) or die(mysql_error());
      if(mysqli_num_rows($ada)>0)
      { 
        $session->msg("d", "Warehouse Is Exist");
        redirect('add_warehouse.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added Warehouse");
          redirect('add_warehouse.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('add_warehouse.php',false);
        }
      }
   } else {
     $session->msg("d", $errors);
     redirect('add_warehouse.php',false);
   }
 }
?>
<!-- END INSERT WAREHOUSE -->

<!-- UPDATE WAREHOUSE -->
<?php
if(isset($_POST['update_warehouse'])){
  $req_field   = array('warehousename','country','address','status','heavymax','idwarehouse');
  validate_fields($req_field);
  $cat_name    = remove_junk($db->escape($_POST['warehousename']));
  $country     = remove_junk($db->escape($_POST['country']));
  $address     = remove_junk($db->escape($_POST['address']));
  $status      = remove_junk($db->escape($_POST['status']));
  $heavymax    = remove_junk($db->escape($_POST['heavymax']));
  $idwarehouse = remove_junk($db->escape($_POST['idwarehouse']));
  if(empty($errors)){
        $sql = "UPDATE warehouse SET nm_warehouse='{$cat_name}',country='{$country}',address='{$address}',status='{$status}',heavy_max='{$heavymax}'";
       $sql .= " WHERE id_warehouse='{$idwarehouse}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Warehouse");
       redirect('add_warehouse.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('add_warehouse.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_warehouse.php',false);
  }
}
?>
<!-- END UPDATE WAREHOUSE -->

<!-- DELETE WAREHOUSE -->
<?php
  if(isset($_POST['delete_warehouse'])){
  $req_field = array('idwarehouse');
  validate_fields($req_field);
  $idwarehouse = remove_junk($db->escape($_POST['idwarehouse']));

  if(empty($errors)){
        $sql = "DELETE FROM warehouse WHERE id_warehouse='{$idwarehouse}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully delete Warehouse");
       redirect('add_warehouse.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Delete");
       redirect('add_warehouse.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_warehouse.php',false);
  }
}
?>
<!-- END DELETE WAREHOUSE -->

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
        <span class="glyphicon glyphicon-th"></span>
        <span>WAREHOUSE</span>
     </strong>
       <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#addWarehouse" title="Add New Warehouse"><span class="glyphicon glyphicon-plus"></span> Add New Warehouse
        </button>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tableWarehouse">
        <thead>
          <tr>
            <th class="text-center" style="width: 5px;">No</th>
            <th class="text-center" style="width: 30px;">Warehouse</th>
            <th class="text-center" style="width: 50px;">Country</th>
            <th class="text-center" style="width: 50px;">Address</th>
            <th class="text-center" style="width: 50px;">Status</th>
            <th class="text-center" style="width: 50px;">Heavy Max</th>
            <th class="text-center" style="width: 50px;">Area Consumed</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_categories as $a_warehouse): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_warehouse['nm_warehouse']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_warehouse['country']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_warehouse['address']))?></td>
           <td class="text-center">
            <?php if($a_warehouse['status'] === '1'): ?>
            <span class="label label-success"><?php echo "Produce"; ?></span>
            <?php else: ?>
            <span class="label label-danger"><?php echo "Not Produce"; ?></span>
            <?php endif;?>
           </td>
           <td class="text-center"><?php echo remove_junk(ucwords(number_format($a_warehouse['heavy_max'])))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords(number_format($a_warehouse['heavy_consumed'])))?></td>
           <td class="text-center">
                <button data-target="#updateWarehouse<?php echo $a_warehouse['id_warehouse'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </button>
                <button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#deleteWarehouse<?php echo $a_warehouse['id_warehouse'];?>" title="Delete"><i class="glyphicon glyphicon-trash"></i>
                </button>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>

<!-- MODAL ADD NEW WAREHOUSE -->
<div class="modal fade" id="addWarehouse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Entry New Warehouse</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_warehouse.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Warehouse</label>
          <input type="name" class="form-control" name="warehousename">
        </div>
        <div class="form-group">
          <label class="control-label">Country</label>
          <input type="name" class="form-control" name="country">
        </div>  
        <div class="form-group">
          <label class="control-label">Address</label>
          <textarea type="name" class="form-control" name="address"></textarea>
        </div>  
        <div class="form-group">
          <label for="status">Status</label>
            <select class="form-control" name="status">
              <option value="1">Produce</option>
              <option value="0">Not Produce</option>
            </select>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Heavy Max</label>
              <input type="name" class="form-control" name="heavymax">
            </div>
          </div>
          <div class="col-md-6">
            <label for="name" class="control-label">Convert Heavy Max</label>
              <select class="form-control" name="convert_max">
                <option value="max_kilograms">Kilograms</option>
                <option value="max_ton">Tons</option>
              </select>
          </div>
        </div>      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" name="add_warehouse" title="Save" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
  </div>
</div>
<!-- END MODAL ADD NEW WAREHOUSE -->

<!-- MODAL UPDATE WAREHOUSE -->
<?php foreach($all_categories as $a_warehouse): ?> 
<div class="modal fade" id="updateWarehouse<?php echo (int)$a_warehouse['id_warehouse'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Update Data Warehouse</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_warehouse.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Warehouse</label>
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['id_warehouse'])); ?>" name="idwarehouse">
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['nm_warehouse'])); ?>" name="warehousename">
        </div>
        <div class="form-group">
          <label class="control-label">Country</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['country'])); ?>" name="country">
        </div>  
        <div class="form-group">
          <label class="control-label">Address</label>
          <textarea type="name" class="form-control" name="address"><?php echo remove_junk(ucwords($a_warehouse['address'])); ?></textarea>
        </div>  
        <div class="form-group">
          <label for="status">Status</label>
            <select class="form-control" name="status">
                <option <?php if($a_warehouse['status'] === '1') echo 'selected="selected"';?> value="1"> Produce </option>
                <option <?php if($a_warehouse['status'] === '0') echo 'selected="selected"';?> value="0"> Not Produce</option>
              </select>
        </div>
        <div class="form-group">
          <label class="control-label">Heavy Max</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['heavy_max'])); ?>" name="heavymax">
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" name="update_warehouse" title="Update" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Update</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL UPDATE WAREHOUSE -->

<!-- MODAL DELETE WAREHOUSE -->
<?php foreach($all_categories as $a_warehouse): ?>
<div class="modal fade" id="deleteWarehouse<?php echo (int)$a_warehouse['id_warehouse'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Delete Warehouse</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_warehouse.php" class="clearfix">
        <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['id_warehouse'])); ?>" name="idwarehouse">
        <p>Are You Sure to Delete Warehouse <b><?php echo remove_junk(ucwords($a_warehouse['nm_warehouse'])); ?></b> From <b><?php echo remove_junk(ucwords($a_warehouse['country'])); ?></b>?</p>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="delete_warehouse" class="btn btn-danger">Delete</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL DELETE WAREHOUSE -->



<script>
      // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 52.158946, lng: 4.473859},
          zoom: 13,
          mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }

    </script>



<?php include_once('layouts/footer.php'); ?>
