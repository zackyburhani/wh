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
   </ul>
  </li>
  
  <li>
    <a href="categories.php" class="submenu-toggle">
      <i class="glyphicon glyphicon-indent-left"></i>
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
        <?php $warehouse = find_by_id_warehouse('warehouse',$user['id_warehouse']); ?>
        <?php if($warehouse['status'] != 0) { ?> 
          <li><a href="lead_time.php">Lead Time</a></li>
        <?php } ?>
        <li><a href="history_shipment.php">Approved History</a></li>
      </ul>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
       <i class="fa fa-handshake-o"></i>
       <span>Purchase Order <span class="label label-danger" id="jumlah"></span></span>
      </a>
      <ul class="nav submenu">
        <li><a href="po.php">Add Purchase Order</a></li>
        <li><a href="history_po.php">Purchase History</a></li>
        <?php $warehouse = find_by_id_warehouse('warehouse',$user['id_warehouse']); ?>
        <?php if($warehouse['status'] != 0) { ?>
          <li><a href="approve2_po.php">Approved Purchase Order </a></li>
           <li><a href="history_approved2.php">Approved History </a></li>
        <?php } ?>
        <li><a href="canceled_po.php">Canceled Order</a></li>
      </ul>
  </li>
</ul>
