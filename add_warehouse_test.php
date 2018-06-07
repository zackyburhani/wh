<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $user = current_user();
  $warehouse = find_warehouse_id($user['id_warehouse']);
  $get_product = get_item_condition($user['id_warehouse']);
  $get_package = get_package_condition($user['id_warehouse']);

  $page_title = "Warehouse ".$warehouse["nm_warehouse"];
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
            <span class="glyphicon glyphicon-th"></span>
            <span>DETAIL Warehouse</span>
         </strong>
        </div>
        <div class="panel-body">
          <form class="form-wh" method="POST">
            <div class="form-group">
            <div class="row">
              <div class="col-md-2">
                <label class="control-label">Warehouse</label>
              </div>
              <div class="col-md-4">
                <input type="name" class="form-control" name="warehousename">
              </div>

              <div class="col-md-2">
                <label class="control-label">Country</label>
              </div>
              <div class="col-md-4">
                <input type="name" class="form-control" name="country">
              </div>

            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-2">
                <label class="control-label">Address</label>
              </div>
              <div class="col-md-4">
                <textarea type="name" class="form-control" name="address"></textarea>
              </div>

              <div class="col-md-2">
                <label class="control-label">Status</label>
              </div>
              <div class="col-md-4">
                <select class="form-control" name="status">
                  <option value="1"> Produce </option>
                  <option value="0"> Not Produce</option>
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
                <input type="number" class="form-control" name="heavymax">
              </div>
              
              <div class="col-md-2">
                <label for="name" class="control-label">Convert Heavy Max</label>
              </div>
              <div class="col-md-4">
                <select class="form-control" name="convert_max">
                  <option value="max_kilograms">Kilograms</option>
                  <option value="max_ton">Tons</option>
                </select>
              </div>

            </div>
          </div>

          <hr>

          <div class="form-group">
            <div class="row pull-right">
              <div class="col-md-12">
                <button class="btn save-wh btn-info" ><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
              </div>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Warehouse Location</span>
        </strong>
      </div>
      <form id="addressForm" action="/">
        <div class="panel-body">
          <div class="row">
            <div class="col-md-4">
              <div class="input-group">
                <input  class="form-control" type="text" name="address" id="address" />
                <span class="input-group-btn">
                  <button class="btn btn-primary" id="addressButton" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                </span>
              </div><!-- /input-group -->
            </div>
          </div>          
        </div>
        <div class="container-fluid">
          <div class="panel panel-primary" id="map">  </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $(".save-wh").click(function(){
      var data = $('.form-wh').serialize();
      $.ajax({
        type: 'POST',
        url: "create_wh.php",
        data: data,
        success: function() {
          load("tampil.php");
        }
      });
    });
  });
  </script>


 <script type="text/javascript" >
 
    (function() {
 
    // Mendefinisikan variabel global
    var map, geocoder, marker, infowindow;
 
    window.onload = function() {
 
      // Membuat map baru
      var options = {  
        zoom: 5,  
        center: new google.maps.LatLng(-0.789275, 113.92132700000002),  
        mapTypeId: google.maps.MapTypeId.ROADMAP  
      };  
 
      map = new google.maps.Map(document.getElementById('map'), options);
 
      // Mengambil referensi ke form HTML
      var form = document.getElementById('addressForm');
 
      // Menangkap event submit form
      form.onsubmit = function() {
        // Mendapatkan alamat dari input teks
        var address = document.getElementById('address').value;
 
        // Membuat panggilan Geocoder 
        getCoordinates(address);
 
        // Menghindari form dari page submit
        return false;
 
      }
 
    }
 
    // Membuat sebuah fungsi yang mengembalikan koordinat alamat
    function getCoordinates(address) {
      // Mengecek apakah terdapat 'geocoded object'. Jika tidak maka buat satu.
      if(!geocoder) {
        geocoder = new google.maps.Geocoder();  
      }
 
      // Membuat objek GeocoderRequest
      var geocoderRequest = {
        address: address
      }
 
      // Membuat rekues Geocode 
      geocoder.geocode(geocoderRequest, function(results, status) {
 
        // Mengecek apakah ststus OK sebelum proses
        if (status == google.maps.GeocoderStatus.OK) {
 
          // Menengahkan peta pada lokasi 
          map.setCenter(results[0].geometry.location);
 
          // Mengecek apakah terdapat objek marker
          if (!marker) {
            // Membuat objek marker dan menambahkan ke peta
            marker = new google.maps.Marker({
              map: map
            });
          }
 
          // Menentukan posisi marker ke lokasi returned location
          marker.setPosition(results[0].geometry.location);
 
          // Mengecek apakah terdapat InfoWindow object
          if (!infowindow) {
            // Membuat InfoWindow baru
            infowindow = new google.maps.InfoWindow();
          }
 
          // membuat konten InfoWindow ke alamat
          // dan posisi yang ditemukan
          var content = '<strong>' + results[0].formatted_address + '</strong><br />';
          content += 'Lat: ' + results[0].geometry.location.lat() + '<br />';
          content += 'Lng: ' + results[0].geometry.location.lng();
 
          // Menambahkan konten ke InfoWindow
          infowindow.setContent(content);
 
          // Membuka InfoWindow
          infowindow.open(map, marker);
        } 
 
      });
 
    }
 
  })();

  </script>

<?php include_once('layouts/footer.php'); ?>

