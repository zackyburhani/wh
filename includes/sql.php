<?php
  require_once('includes/load.php');


//autonumber (zacky)
function autonumber($id, $table){
  global $db;
  $query = 'SELECT MAX(RIGHT('.$id.', 4)) as max_id FROM '.$table.' ORDER BY '.$id;
  $result = $db->query($query);
  $data = $db->fetch_assoc($result);
  $id_max = $data['max_id'];
  $sort_num = (int) substr($id_max, 1, 4);
  $sort_num++;
  $new_code = sprintf("%04s", $sort_num);
  return $new_code;
}


/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table)." where id='$_GET[id]'");
   }
}

//find all position (zacky)
function find_all_Position($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table)."");
   }
}


//find all position (zacky)
function find_all_Position_admin() { 
  return find_by_sql("SELECT * FROM position WHERE level_user != '0'  ");
}

//find all position (zacky)
function find_all_Position_admin2($table) { 
  return find_by_sql("SELECT * FROM position WHERE level_user != '0' and id_warehouse = '$table' ");
}

//find id position (zacky)
function find_by_id_position($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_position='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_prod_warehouse($table) {
  global $db;
  if(tableExists($table))
  {
    return find_by_sql("SELECT * FROM ".$db->escape($table)." where warehouse_id='$_GET[product_warehouse]'");
  }
}

function find_all1($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}

function find_all_categories($table,$id) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT nm_categories,id_categories FROM {$db->escape($table)} WHERE id_warehouse = '{$db->escape($id)}'");
   }
}

function find_all_subcategories($table,$id) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT *FROM {$db->escape($table)},categories WHERE {$db->escape($table)}.id_categories = categories.id_categories and id_warehouse = '{$db->escape($id)}' ORDER BY nm_subcategories");
   }
}


//find field with order (zacky)
function find_all_order($table,$order,$id) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_warehouse = '{$db->escape($id)}' ORDER BY {$db->escape($order)}");
   }
}

function find_allSubcategories($id) {
   global $db;
   return find_by_sql("SELECT * FROM categories INNER JOIN sub_categories ON sub_categories.id_categories = categories.id_categories and id_warehouse = '{$db->escape($id)}' ORDER BY nm_categories");
}



function find_warehouse($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table)." whare nm_warehouse='$_GET[nm_warehouse]'");
   }
}

function find_package($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table)." whare nm_package='$_GET[nm_package]'");
   }
}

function find_all2($table) {
  global $db;
  if(tableExists($table))
  {
    return find_by_sql("SELECT * FROM ".$db->escape($table)." where id_warehouse='$_GET[id]'");
  }
}

// function find_all3($table) {
//   global $db;
//   if(tableExists($table))
//   {
//     return find_by_sql("SELECT * FROM ".$db->escape($table)." where id_location ='$_GET[id_location]'");
//   }
// }


//find all employee (zacky)
function find_all_employee($id_warehouse){
      global $db;
      $results = array();
      $sql = "SELECT u.id_employer,u.username,u.nm_employer,u.id_position,u.last_login,u.status,u.id_warehouse, g.nm_position, g.level_user FROM employer u LEFT JOIN position g ON g.id_position=u.id_position WHERE u.id_warehouse = '$id_warehouse' AND g.level_user !='0' ORDER BY u.nm_employer ASC";
      $result = find_by_sql($sql);
      return $result;
  }

function find_all_admin(){
      global $db;
      $results = array();
      $sql = "SELECT employer.id_employer,nm_employer,username,position.nm_position,employer.status,employer.last_login,warehouse.nm_warehouse,position.id_position,warehouse.id_warehouse FROM employer JOIN position ON employer.id_position=position.id_position JOIN warehouse ON employer.id_warehouse = warehouse.id_warehouse WHERE position.level_user = '1' ORDER BY employer.nm_employer ASC";
      $result = find_by_sql($sql);
      return $result;
  }

function find_adminName(){
      global $db;
      $results = array();
      $sql = "SELECT nm_position,id_position FROM position WHERE level_user = '1' group by nm_position ";
      $result = find_by_sql($sql);
      return $result;
  }

  //validation connected foreign key (zacky)
  function find_all_idPosition($field){
      global $db;
      $results = array();
      $sql = "SELECT id_position FROM employer WHERE id_position = '{$db->escape($field)}'";
      $result = find_by_sql($sql);
      return $result;
  }

  //validation connected foreign key (zacky)
  function find_all_id($table,$field,$row){
      global $db;
      $results = array();
      $sql = "SELECT {$db->escape($row)} FROM {$db->escape($table)} WHERE {$db->escape($row)} = '{$db->escape($field)}'";
      $result = find_by_sql($sql);
      return $result;
  }


/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id warehouse
/*--------------------------------------------------------------*/
function find_by_id_warehouse($table,$idwarehouse)
{
  global $db;
  $id = (int)$idwarehouse;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_warehouse='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_by_employer($table,$id)
{
  global $db;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_employer='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_by_employerPosition($table,$id)
{
  global $db;
    if(tableExists($table)){
          $sql = $db->query("SELECT id_employer,username,nm_employer,{$db->escape($table)}.id_position,last_login,status,image,{$db->escape($table)}.id_warehouse,nm_position,level_user FROM {$db->escape($table)} JOIN position WHERE position.id_position = {$db->escape($table)}.id_position and id_employer='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_by_id_pro($table,$id)
{
  global $db;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_item='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_by_id_cat($table,$id_categories)
{
  global $db;
  $id = (int)$id_categories;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_categories='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_idwarehouse($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_warehouse='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id warehouse
/*--------------------------------------------------------------*/
function delete_by_id_warehouse($table,$idwarehouse)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id_warehouse=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}

//delete function for all method (zacky)
function delete($field,$table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE ".$db->escape($field)."=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}

function delete_by_id_pro($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id_item=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}

function delete_by_id_cat($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id_categories=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}

/*--------------------------------------------------------------*/
/* Function for Count id  By table name (zacky)
/*--------------------------------------------------------------*/
function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id_employer) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
function count_by_id_cat($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id_categories) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
function count_by_id_pro($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id_item) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  //authenticate for login (zacky)
  function authenticate($username='', $password='') {
    global $db;
      $username = $db->escape($username);
      $password = $db->escape($password);
      $sql  = sprintf("SELECT id_employer,username,password,id_position,status FROM employer WHERE username ='%s' LIMIT 1", $username);

      $result = $db->query($sql);
      if($db->num_rows($result)){
        $user = $db->fetch_assoc($result);
        $password_request = sha1($password);
        if($password_request === $user['password'] ){
          return $user;
        }
      }
    return false;
  }
  
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id (coded by zacky)
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['id_employer'])):
             $user_id[] = $_SESSION['id_employer'];
             $pick_id = $user_id[0]['id_employer'];
             $current_user = find_by_employerPosition('employer',$pick_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  //find all employees (zacky)
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id_employer,u.username,u.nm_employer,u.id_position,u.last_login,u.status";
      $sql .="g.nm_position ";
      $sql .="FROM employer u ";
      $sql .="LEFT JOIN position g ";
      $sql .="ON g.id_position=u.id_position ORDER BY u.nm_employer ASC";
      $result = find_by_sql($sql);
      return $result;
  }

  //function for find item and sub_categories (AMETH)
  function find_all_item(){
      global $db;
      $results = array();
      $sql = "SELECT * FROM item,sub_categories,package WHERE item.id_package = package.id_package and item.id_subcategories = sub_categories.id_subcategories";
      $result = find_by_sql($sql);
      return $result;
  }
  
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/
  //update last login (zacky)
  function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE employer SET last_login='{$date}' WHERE id_employer ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all position name (zacky)
  /*--------------------------------------------------------------*/
  function find_by_positionName($val)
  {
    global $db;
    $sql = "SELECT nm_position FROM position WHERE nm_position = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }

  //find categoryName (zacky)
  function find_by_categoryName($val)
  {
    global $db;
    $sql = "SELECT nm_categories FROM categories WHERE nm_categories = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT level_user FROM position WHERE level_user = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['level_user']);
     //if user not login

     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Please login...');
            redirect('index.php', false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['id_position'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "Sorry! you dont have permission to view the page.");
            redirect('home.php', false);
        endif;

     }
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
    $sql  =" SELECT * from q_produk";
    return find_by_sql($sql);

   }

  function join_product_table1(){
    global $db;
   $sql  =" SELECT * from q_produk WHERE warehouse_id=$_GET[id]";
   return find_by_sql($sql);

  }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   /*global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,c.name,w.name_warehouse AS categorie,";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN warehouse w ON w.id = p.warehouse_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);*/
   global $db;
    $sql  =" SELECT * from q_produk";
    return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.name,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailySales($year,$month){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function  monthlySales($year){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.product_id";
  $sql .= " ORDER BY date_format(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}

?>
