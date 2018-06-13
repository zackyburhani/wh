<ul>
  <li>
    <a href="home.php">
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
        <li><a href="add_warehouse.php">Add Warehouse</a></li>
        <li><a href="add_location.php">Add Location</a></li>
        <li><a href="warehouse.php">Warehouse Condition</a></li>
      </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-user"></i>
      <span>User Management</span>
    </a>
    <ul class="nav submenu">
      <li><a href="position.php">Manage Position</a> </li>
      <li><a href="users.php">Manage Employee</a> </li>
      <li><a href="superusers.php">Administrator Inter IKEA</a> </li>
   </ul>
  </li>
  
  <li>
    <a href="categories.php" >
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
        <li><a href="add_package.php">Add Package</a></li>
          <li><a href="add_bpack.php">Combine Package</a></li>
      </ul>
  </li>
  
  <li>
    <a href="product.php" class="submenu-toggle">
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
        <li><a href="move_product.php">Receive Product</a></li>
        <li><a href="lead_time.php">Lead Time</a></li>
        <li><a href="history_shipment.php">Approved History</a></li>
      </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="fa fa-handshake-o"></i>
       <span>Purchase Order</span>
      </a>
      <ul class="nav submenu">
        <li><a href="po.php">Add Purchase Order</a></li>
        <li><a href="history_po.php">Purchase History</a></li>
        <li><a href="offer_po.php">Purchase Offer <span class="label label-danger" id="jumlah"><?php $offer_po = find_all_PO_destination_admin_notif($user['id_warehouse']); if($offer_po != null) {echo $offer_po;}  ?></span></a></li>
        <?php if ($user['level_user'] =='0') { ?>
        <?php $approve1 = find_all_PO_admin_notif($user['id_warehouse']); ?>
        <li><a href="approve1_po.php">Approved Purchase Order <span class="label label-danger label-sm"><?php if($approve1 != null) {echo $approve1;} ?></span></a></li>
        <?php } ?>
        <li><a href="history_approved1.php">Approved History</a></li>
        <li><a href="canceled_po.php">Canceled Order</a></li>
      </ul>
  </li>

</ul>
