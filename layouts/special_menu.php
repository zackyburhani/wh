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
      <i class="glyphicon glyphicon-th-large"></i>
      <span>Products</span>
    </a>
  </li>
 
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
       <span>Warehouse</span>
      </a>
      <ul class="nav submenu">
        <li><a href="move_product.php">Move Quantity Warehouse</a></li>
      </ul>
  </li>

  <li>
    <a href="add_package.php" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
       <span>Add Package</span>
      </a>
  </li>

  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
       <span>Purchase Order <span class="label label-danger" id="jumlah"></span></span>
      </a>
      <ul class="nav submenu">
        <li><a href="po.php">Add Purchase Order</a></li>
        <li><a href="history_po.php">History Purchase Order</a></li>
        <?php   $warehouse = find_by_id_warehouse('warehouse',$user['id_warehouse']); ?>
        <?php if($warehouse['status'] != 0) { ?>
          <li><a href="approve2_po.php">Offer Purchase Order <span class="label label-danger" id="jumlah"><?php echo $notif = find_all_PO_destination_notif($user['id_warehouse']);  ?></span></a></li>
           <li><a href="history_approved2.php">History Approved</a></li>
        <?php } ?>
      </ul>
  </li>
</ul>
