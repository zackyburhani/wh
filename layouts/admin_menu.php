<?php
$all_menu = find_all1('sidebar');
?> 

<ul>
  <li>
    <a href="admin.php">
      <i class="glyphicon glyphicon-home"></i>
      <span>Dashboard</span>
    </a>
  </li>
  <?php $sql = "SELECT * FROM sidebar ORDER BY id ASC"; ?>
  <?php $menu = $db->query($sql); ?>
  <?php while($dataMenu = mysqli_fetch_assoc($menu)) { ?>
  <?php $menu_id = $dataMenu['id']; ?>
  <?php $sql2 = "SELECT * FROM sub_sidebar WHERE id_menu='$menu_id' ORDER BY id_sub ASC"; ?>
  <?php $submenu = $db->query($sql2); ?>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
        <span><?php echo $dataMenu['name'] ?></span>
    </a>       
    <ul class="nav submenu">
      <?php while($dataSubmenu = mysqli_fetch_assoc($submenu)) { ?>
      <li><a href="group.php"><?php echo $dataSubmenu['name_sub'] ?></a> </li>
      <?php } ?>
    </ul>
  </li>
<?php } ?>
  <li>
    <a href="add_menu.php" >
      <i class="glyphicon glyphicon-list-alt"></i>
        <span>Add New Menu</span>
      </a>
    </li>
</ul>    


<!-- <ul>
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
      <li><a href="group.php">Manage Groups</a> </li>
      <li><a href="users.php">Manage Users</a> </li>
   </ul>
  </li>
  <li>
    <a href="categorie.php" >
      <i class="glyphicon glyphicon-indent-left"></i>
      <span>Categories</span>
    </a>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
      <span>Products</span>
    </a>
    <ul class="nav submenu">
       <li><a href="product.php">Manage products</a> </li>
       <li><a href="add_product.php">Add product</a> </li>
   </ul>
  </li>
 
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
       <span>Add Warehouse</span>
      </a>
      <ul class="nav submenu">
        <li><a href="add_warehouse.php">Add Inventory</a></li>
        <li><a href="move_product.php">Move Quantity Warehouse</a></li>
      </ul>
  </li>

  <li>
    <a href="add_menu.php" >
      <i class="glyphicon glyphicon-list-alt"></i>
      <span>Add New Menu</span>
    </a>
  </li>
</ul>
 -->