<ul>
  <li>
    <a href="admin.php">
      <i class="glyphicon glyphicon-home"></i>
      <span>Dashboard</span>
    </a> 
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
      <i class="glyphicon glyphicon-indent-left"></i>
      <span>Categories</span>
    </a>
  </li>
  <li>
    <a href="product.php" class="submenu-toggle">
      <i class="fa fa-cubes"></i>
      <span>Products</span>
    </a>
  </li>
 
  <li>
    <a href="#" class="submenu-toggle">
      <i class="fa fa-university"></i>
       <span>Warehouse</span>
      </a>
      <ul class="nav submenu">
        <li><a href="add_warehouse.php">Add Inventory</a></li>
          <li><a href="add_location.php">Add Location</a></li>

        <li><a href="move_product.php">Move Quantity Warehouse</a></li>
        <li><a href="warehouse.php">Warehouse Condition</a></li>

      </ul>
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
    <a href="#" class="submenu-toggle">
      <i class="fa fa-handshake-o"></i>
       <span>Purchase Order</span>
      </a>
      <ul class="nav submenu">
        <li><a href="po.php">Add Purchase Order</a></li>
        <li><a href="history_po.php">History Purchase Order</a></li>
        <li><a href="offer_po.php">Offer Purchase Order <span class="label label-danger" id="jumlah"><?php $offer_po = find_all_PO_destination_admin_notif($user['id_warehouse']); if($offer_po != null) {echo $offer_po;}  ?></span></a></li>
        <?php if ($user['id_warehouse'] =='0001') { ?>
        <?php $approve1 = find_all_PO_admin_notif($user['id_warehouse']); ?>
        <li><a href="approve1_po.php">Approve Purchase Order <span class="label label-danger label-sm"><?php if($approve1 != null) {echo $approve1;} ?></span></a></li>
        <?php } ?>
        <li><a href="history_approved1.php">History Approved</a></li>
      </ul>
  </li>

</ul>
