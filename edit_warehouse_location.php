  <?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $id          = $_GET['id'];
  $user        = current_user();
  $warehouse   = find_warehouse_id($user['id_warehouse']);
  $get_product = get_item_condition($user['id_warehouse']);
  $get_package = get_package_condition($user['id_warehouse']);

  $warehouse1  = find_warehouse_id($id);

  $page_title = "Edit Warehouse ".$warehouse["nm_warehouse"];;
  
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
            <i class="fa fa-university"></i>
            <span>Add New Warehouse</span>
         </strong>
        </div>
        <div class="panel-body">
          <form id="addressForm" method="POST">
            <div class="form-group">
            <div class="row">
              <div class="col-md-2">
                <label class="control-label">Warehouse</label>
              </div>
              <div class="col-md-4">
                <input type="name" value="<?php echo $warehouse1['nm_warehouse'] ?>" placeholder="Warehouse Name" class="form-control" name="warehousename" id="warehousename">
              </div>

              <div class="col-md-2">
                <label class="control-label">Status</label>
              </div>
              <div class="col-md-4">
                <select class="form-control" name="status" id="status">
                  <option <?php if($warehouse1['status'] == '1'  ): echo "selected"; endif; ?> value="1"> Produce </option>
                  <option <?php if($warehouse1['status'] == '0'  ): echo "selected"; endif; ?> value="0"> Not Produce</option>
                </select>
              </div>

            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-2">
                <label class="control-label">Heavy Max</label>
              </div>
              <div class="col-md-4">
                <input type="number" placeholder="heavy Max" value="<?php echo $warehouse1['heavy_max'] ?>" class="form-control" name="heavymax" id="heavymax">
              </div>
              
              <div class="col-md-2">
                <label for="name" class="control-label">Convert Heavy Max</label>
              </div>
              <div class="col-md-4">
                <select class="form-control" name="convert_max" id="convert_max">
                  <option value="max_kilograms">Kilograms</option>
                  <option value="max_ton">Tons</option>
                </select>
              </div>

            </div>
          </div>

          <hr>
          
          <div class="form-group">
            <div class="row">  
              <div class="col-md-6">
                <div class="input-group">
                  <input  class="form-control" placeholder="Search..." type="text" name="address" id="address" />
                  <span class="input-group-btn">
                    <button class="btn btn-primary" id="addressButton" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                  </span>
                </div><!-- /input-group -->
              </div>
              <div class="col-md-6">
                <button title="Save" id="save_wh" class="btn btn-primary btn-md btn-block" ><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
              </div>
            </div>
          </div>
        
            <div class="form-group">
              <div class="row"> 
                <div class="container-fluid">
                  <div class="panel panel-primary" id="map">  </div>
                </div>
              </div>
            </div>

            <input type="hidden" id="get_address"  value="<?php echo $warehouse1['address'] ?>">
            <input type="hidden" id="latitude" value="<?php echo $warehouse1['latitude'] ?>">
            <input type="hidden" id="longitude" value="<?php echo $warehouse1['longitude'] ?>">

          </form>
        </div>
      </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>

<script type="text/javascript">
  (function() {
 
    // Define Global Variable
    var map, geocoder, marker, infowindow;
 
    window.onload = function() {
 
      var latitude    = document.getElementById('latitude').value;
      var longitude   = document.getElementById('longitude').value;
      var get_address = document.getElementById('get_address').value;

      // Create New Map
      var options = {  
        zoom: 5,  
        center: new google.maps.LatLng(latitude, longitude),  
        mapTypeId: google.maps.MapTypeId.ROADMAP ,
      };  
 
      map = new google.maps.Map(document.getElementById('map'), options);

      // Get reference from HTML
      var form = document.getElementById('addressForm');
 
      getCoordinates(get_address);

      // Catch The Event
      form.onsubmit = function() {
        // Mendapatkan alamat dari input teks
        var address = document.getElementById('address').value;
 
        // Get Geocoder 
        getCoordinates(address);
 
        // prevent page submit
        return false;
      }
    }
 
    // create function to return address
    function getCoordinates(address) {
      //check wether geocode objet exist or not. if not create one
      if(!geocoder) {
        geocoder = new google.maps.Geocoder();  
      }
 
      // create object GeocoderRequest
      var geocoderRequest = {
        address: address
      }
 
      // create request Geocode 
      geocoder.geocode(geocoderRequest, function(results, status) {
 
        // chect status
        if (status == google.maps.GeocoderStatus.OK) {
          // map center 
          map.setCenter(results[0].geometry.location);
          // check objek marker
          if (!marker) {
            marker = new google.maps.Marker({
              map: map,
              animation: google.maps.Animation.DROP,
              icon: 'img/maps/maps.ico'
            });
          }
          // set position
          marker.setPosition(results[0].geometry.location);
          if (!infowindow) {
            infowindow = new google.maps.InfoWindow();
          }

          var address1 = results[0].formatted_address;
          var latt     = results[0].geometry.location.lat();
          var long     = results[0].geometry.location.lng();

          for (var i = 0; i < results[0].address_components.length; i++)
          {
            var longname = results[0].address_components[i].long_name;
            var type = results[0].address_components[i].types;
            if (type.indexOf("administrative_area_level_1") != -1)
            {
              region = longname;
            }
              if (type.indexOf("country") != -1)
              {
               var country = longname;
              }
            }

           // console.log(address1);
          var content = '<strong>' + address1 + '</strong><br />';
          content += 'Lat: ' + latt + '<br />';
          content += 'Lng: ' + long;
 
          infowindow.setContent(content);
 
          infowindow.open(map, marker);

        } 

        //Save Location
          $('#save_wh').on('click',function(){

            var address_AJX     = address1;
            var latt_AJX        = latt;
            var long_AJX        = long;
            var country2         = country;
            var warehousename   = $("#warehousename").val();
            var status          = $("#status").val();
            var heavymax        = $("#heavymax").val();
            var convert_max     = $("#convert_max").val();
              $.ajax({
                type: "POST",
                url: "insert_warehouse.php",
                data: {warehousename:warehousename, status:status,heavymax:heavymax,convert_max:convert_max,address_AJX: address_AJX, latt_AJX: latt_AJX, long_AJX: long_AJX, country2:country2},
                success: function(data) {
                  location.reload();
                  $('[name="warehousename"]').val("");
                  $('[name="heavymax"]').val("");
                  $('[name="address"]').val("");
                  alert('Success Added Warehouse');
                }
              });
                return false;
          });

      });
    }
 
  })();   
</script>

