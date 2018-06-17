<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<ul>
  <li>
    <a href="home.php" class="modul" id="dashboard">
      <i class="glyphicon glyphicon-home"></i>
      <span>Dashboard</span>
    </a> 
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="fa fa-university"></i>
       <span>Warehouse</span>
      </a>
      <ul class="nav submenu">
<<<<<<< HEAD
        <li><a href="add_warehouse.php" class="modul" id="warehouse">Add Warehouse</a></li>
        <li><a href="add_location.php" class="modul" id="location">Add Location</a></li>
        <li><a href="lead_time.php" class="modul" id="leadtime">Lead Time</a></li>
        <li><a href="warehouse.php" class="modul" id="condition">Warehouse Condition</a></li>
=======
        <li><a href="add_warehouse.php">Add Warehouse</a></li>
        <li><a href="add_location.php">Add Location</a></li>
        <li><a href="warehouse.php">Warehouse Condition</a></li>
>>>>>>> 731a6000cdc32aaffaa15e665316aa23f9d7b04b
      </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-user"></i>
      <span>User Management</span>
    </a>
    <ul class="nav submenu">
      <li><a href="position.php" class="modul" id="position">Manage Position</a> </li>
      <li><a href="users.php" class="modul" id="employee">Manage Employee</a> </li>
      <li><a href="superusers.php" class="modul" id="admin">Administrator Inter IKEA</a> </li>
   </ul>
  </li>
  
  <li>
    <a class="modul" id="categories">
      <i class="fa fa-tag"></i>
      <span>Categories</span>
    </a>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="fa fa-archive"></i>
       <span>Package</span>
      </a>
      <ul class="nav submenu">
        <li><a href="add_package.php" class="modul" id="package">Add Package</a></li>
          <li><a href="add_bpack.php" class="modul" id="combine">Combine Package</a></li>
      </ul>
  </li>
  
  <li>
    <a href="product.php" class="modul" id="product">
      <i class="fa fa-cubes"></i>
      <span>Products</span>
    </a>
  </li>
  
  <li>
    <a href="#" class="submenu-toggle">
      <i class="fa fa-truck"></i>
       <span>Receive Product</span>
      </a>
      <ul class="nav submenu">
<<<<<<< HEAD
        <li><a href="move_product.php" class="modul" id="receive">Receive Product</a></li>
        <li><a href="history_shipment.php" class="modul" id="history">Approved History</a></li>
=======
        <li><a href="move_product.php">Receive Product</a></li>
        <li><a href="lead_time.php">Lead Time</a></li>
        <li><a href="history_shipment.php">Approved History</a></li>
>>>>>>> 731a6000cdc32aaffaa15e665316aa23f9d7b04b
      </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="fa fa-handshake-o"></i>
       <span>Purchase Order</span>
      </a>
      <ul class="nav submenu">
<<<<<<< HEAD
        <li><a href="po.php" class="modul" id="po">Add Purchase Order</a></li>
        <li><a href="history_po.php" class="modul" id="pohistory">Purchase History</a></li>
        <li><a href="offer_po.php" class="modul" id="offer">Purchase Offer <span class="label label-danger" id="jumlah"><?php $offer_po = find_all_PO_destination_admin_notif($user['id_warehouse']); if($offer_po != null) {echo $offer_po;}  ?></span></a></li>
        <?php if ($user['id_warehouse'] =='0001') { ?>
        <?php $approve1 = find_all_PO_admin_notif($user['id_warehouse']); ?>
        <li><a href="approve1_po.php" class="modul" id="approvepo">Approved Purchase Order <span class="label label-danger label-sm"><?php if($approve1 != null) {echo $approve1;} ?></span></a></li>
=======
        <li><a href="po.php">Add Purchase Order</a></li>
        <li><a href="history_po.php">Purchase History</a></li>
        <li><a href="offer_po.php">Purchase Offer </a></li>
        <?php if ($user['level_user'] =='0') { ?>
        <?php $approve1 = find_all_PO_admin_notif($user['id_warehouse']); ?>
        <li><a href="approve1_po.php">Approved Purchase Order </a></li>
>>>>>>> 731a6000cdc32aaffaa15e665316aa23f9d7b04b
        <?php } ?>
        <li><a href="history_approved1.php" class="modul" id="approvehist">Approved History</a></li>
        <li><a href="canceled_po.php" class="modul" id="cancel">Canceled Order</a></li>
      </ul>
  </li>

</ul>


<script type="text/javascript">
  $(document).ready(function(){
    $('.modul').click(function(){
      var menu = $(this).attr('id');
      if(menu == "dashboard"){
        $('.body').load('home.php');           
      }else if(menu == "warehouse"){
        $('.body').load('add_warehouse.php');            
      }else if(menu == "location"){
        $('.body').load('add_location.php');           
      }else if(menu == "leadtime"){
        $('.body').load('lead_time.php');           
      }else if(menu == "condition"){
        $('.body').load('warehouse.php');           
      }else if(menu == "categories"){
        $('.body').load('categories.php');           
      }
    });
 
 
    // halaman yang di load default pertama kali
    //$('.body').load('home.php');           
 
  });
</script>