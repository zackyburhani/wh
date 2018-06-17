<?php
  require_once('includes/load.php');

//autonumber
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

//find all position
function find_all_Position($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table)."");
   }
}

//find all position
function find_all_Position_admin() { 
  return find_by_sql("SELECT * FROM position WHERE level_user != '0'  ");
}

//find all position
function find_all_Position_admin2($table) { 
  return find_by_sql("SELECT * FROM position WHERE level_user != '0' and id_warehouse = '$table' ");
}

//find id position 
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

//find all position 
function find_po($table) {
  global $db;
  if(tableExists($table))
  {
    return find_by_sql("SELECT * FROM ".$db->escape($table)." where id_po='$_GET[no_po]'");
  }
}

//find all position
function find_all_PO($id) { 
  return find_by_sql("SELECT * FROM po WHERE id_warehouse = '$id'");
}

function find_prod_warehouse($table) {
  global $db;
  if(tableExists($table))
  {

    return find_by_sql("SELECT * FROM ".$db->escape($table)." where id_warehouse='$_GET[item]'");

    return find_by_sql("SELECT * FROM ".$db->escape($table)." where id_location='$_GET[location]'");

  }
}

function find_prod_warehouse_1($table) {
  return find_by_sql("SELECT * FROM location where id_location = '$table'");
}

function find_all1($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}

function update($id) {
    global $db;
    $date = make_date();
    $sql = "UPDATE detil_po SET status='success' WHERE id_po ='{$id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
}

function find_warehouse_location($id_warehouse) {
  return find_by_sql("SELECT * FROM location WHERE id_warehouse = '$id_warehouse'");
}

function find_warehouse_name() {
   return find_by_sql("SELECT nm_warehouse FROM warehouse");
}

function find_all_chained_item() {
   global $db;
     return find_by_sql("SELECT * FROM warehouse, location,item WHERE warehouse.id_warehouse = location.id_warehouse AND item.id_location = location.id_location ORDER BY nm_warehouse");
}

function find_warehouse_po($id_warehouse) {
  return find_by_sql("SELECT * FROM warehouse WHERE id_warehouse != '$id_warehouse' and status != 0");
}

function find_warehouse_id($id_warehouse) {
  global $db;
  $sql = $db->query("SELECT * FROM warehouse WHERE id_warehouse = '$id_warehouse'");
  return $db->fetch_assoc($sql);
}

function find_package_id($id_package) {
  global $db;
  $sql = $db->query("SELECT * FROM package WHERE id_package = '$id_package'");
  return $db->fetch_assoc($sql);
}

function find_lat1_long1($id_po) {
  global $db;
  $sql = $db->query("SELECT po.id_po, nm_warehouse, latitude,longitude FROM po,warehouse WHERE warehouse.id_warehouse = po.id_warehouse AND id_po = '$id_po'");
  return $db->fetch_assoc($sql);
}

function find_lat2_long2($id_po) {
  global $db;
  $sql = $db->query("SELECT detil_po.id_po, nm_warehouse, latitude,longitude FROM detil_po,warehouse WHERE warehouse.id_warehouse = detil_po.id_warehouse AND id_po = '$id_po' GROUP by id_po");
  return $db->fetch_assoc($sql);
}


function find_leadtime($id_po,$id_warehouse_for,$id_warehouse_from) {
  return find_by_sql("
    SELECT po.id_po as id_po, po.id_warehouse as for_wh, detil_po.id_warehouse as from_wh, 
      (SELECT nm_warehouse FROM warehouse where warehouse.id_warehouse = po.id_warehouse) as for_wh_nm,
      (SELECT nm_warehouse FROM warehouse where warehouse.id_warehouse = detil_po.id_warehouse) as from_wh_nm,
      (SELECT latitude FROM po,warehouse WHERE warehouse.id_warehouse = for_wh and po.id_po = '$id_po' GROUP by for_wh) as latitude1,
      (SELECT longitude FROM po,warehouse WHERE warehouse.id_warehouse = for_wh and po.id_po = '$id_po' GROUP by for_wh) as longitude1,
      (SELECT address FROM po,warehouse WHERE warehouse.id_warehouse = for_wh and po.id_po = '$id_po' GROUP by for_wh) as address1,
      (SELECT latitude FROM detil_po,warehouse WHERE warehouse.id_warehouse = from_wh and detil_po.id_po = '$id_po' GROUP by latitude) as latitude2,
      (SELECT longitude FROM detil_po,warehouse WHERE warehouse.id_warehouse = from_wh and detil_po.id_po = '$id_po' GROUP by longitude) as longitude2,
      (SELECT address FROM detil_po,warehouse WHERE warehouse.id_warehouse = from_wh and detil_po.id_po = '$id_po' GROUP by longitude) as address2
    FROM detil_po,po,warehouse WHERE warehouse.id_warehouse = po.id_warehouse and po.id_po = detil_po.id_po and detil_po.status = 'On Destination' 
    and (po.id_warehouse = '$id_warehouse_for' or po.id_warehouse = '$id_warehouse_from') and po.id_po = '$id_po' group by po.id_po order by 1 desc  
  ");
}

function find_leadtime_from($id_po,$id_warehouse) {
  return find_by_sql("
    SELECT po.id_po as id_po, po.id_warehouse as for_wh, detil_po.id_warehouse as from_wh, 
    (SELECT nm_warehouse FROM warehouse where warehouse.id_warehouse = po.id_warehouse) as for_wh_nm,
    (SELECT nm_warehouse FROM warehouse where warehouse.id_warehouse = detil_po.id_warehouse) as from_wh_nm,
    (SELECT latitude FROM po,warehouse WHERE warehouse.id_warehouse = for_wh and po.id_po = '$id_po' GROUP by for_wh) as latitude1,
    (SELECT longitude FROM po,warehouse WHERE warehouse.id_warehouse = for_wh and po.id_po = '$id_po' GROUP by for_wh) as longitude1,
    (SELECT address FROM po,warehouse WHERE warehouse.id_warehouse = for_wh and po.id_po = '$id_po' GROUP by for_wh) as address1,
    (SELECT latitude FROM detil_po,warehouse WHERE warehouse.id_warehouse = from_wh and detil_po.id_po = '$id_po' GROUP by latitude) as latitude2,
    (SELECT longitude FROM detil_po,warehouse WHERE warehouse.id_warehouse = from_wh and detil_po.id_po = '$id_po' GROUP by longitude) as longitude2,
    (SELECT address FROM detil_po,warehouse WHERE warehouse.id_warehouse = from_wh and detil_po.id_po = '$id_po' GROUP by longitude) as address2
    FROM detil_po,po,warehouse WHERE warehouse.id_warehouse = po.id_warehouse and po.id_po = detil_po.id_po and detil_po.status = 'On Destination' and detil_po.id_warehouse = '$id_warehouse' group by po.id_po order by 1 desc
  ");
}


function find_leadtime_wh($id_warehouse) {
  global $db;
  $sql = $db->query("SELECT detil_po.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_warehouse as from_wh, detil_po.id_item,qty FROM detil_po,po where status = 'On Destination' and po.id_po = detil_po.id_po and po.id_warehouse = '$id_warehouse'");
     return $db->fetch_assoc($sql);
}

function find_leadtime_po($id_warehouse_for,$id_warehouse_from) {
  global $db;
  $sql = $db->query("SELECT detil_po.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_warehouse as from_wh, detil_po.id_item,qty FROM po,detil_po where detil_po.id_po = po.id_po and po.id_warehouse = '$id_warehouse_for' and detil_po.status = 'On Destination' and detil_po.id_warehouse = '$id_warehouse_from'");
     return $db->fetch_assoc($sql);
}

function find_leadtime_po_from($id_warehouse) {
  global $db;
  $sql = $db->query("SELECT detil_po.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_warehouse as from_wh, detil_po.id_item,qty FROM po,detil_po where detil_po.id_po = po.id_po and detil_po.status = 'On Destination' and detil_po.id_warehouse = '$id_warehouse'");
     return $db->fetch_assoc($sql);
}

function find_all1_ware($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT unit,floor,room,nm_warehouse from location,warehouse ");
   }
}

function find_all_categories($table,$id) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT nm_categories,id_categories FROM {$db->escape($table)} WHERE id_warehouse = '{$db->escape($id)}' ORDER BY nm_categories ASC");
   }
}

function find_all_location($table,$id) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT unit,floor,room,id_location,id_warehouse FROM {$db->escape($table)} WHERE id_warehouse = '{$db->escape($id)}'");
   }
}

//note on oracle
function find_all_package($table,$id) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_warehouse = '$id'");
   }
}

function find_all_bpack($id_warehouse) {
  global $db;
  return find_by_sql("SELECT * FROM bpack,package WHERE bpack.id_package = package.id_package and package.id_warehouse = '$id_warehouse'");
}


//note on oracle
function find_weight_item($id_item) {
  global $db;
     $sql = $db->query("SELECT item.weight as w_item from item where id_item = '$id_item'");
     return $db->fetch_assoc($sql);
}

function find_weight_package($id_package) {
  global $db;
     $sql = $db->query("SELECT package.weight as w_package from package where package.id_package = '$id_package'");
     return $db->fetch_assoc($sql);
}

function find_all_subcategories($table,$id) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT *FROM {$db->escape($table)},categories WHERE {$db->escape($table)}.id_categories = categories.id_categories and id_warehouse = '{$db->escape($id)}' ORDER BY nm_subcategories ASC");
   }
}

function find_all_product($id) {
   global $db;
     return find_by_sql("SELECT * FROM item,location,sub_categories,categories WHERE categories.id_categories = sub_categories.id_categories and item.id_subcategories = sub_categories.id_subcategories and item.id_location = location.id_location AND location.id_warehouse = '$id'");
}

function get_product($table,$id){
  global $db;
    return find_by_sql("SELECT id_item,nm_item,colour,stock,nm_package,nm_subcategories,unit FROM item,package,sub_categories,location,warehouse WHERE item.id_package = package.id_package AND item.id_subcategories = sub_categories.id_subcategories AND item.id_location = location.id_location AND location.id_warehouse = warehouse.id_warehouse AND location.id_warehouse = '{$db->escape($id)}'");
}


function get_item_condition($id_warehouse){
  global $db;
    return find_by_sql("SELECT * FROM item,location,warehouse where item.id_location = location.id_location and location.id_warehouse = warehouse.id_warehouse and stock < safety_Stock and warehouse.id_warehouse = '$id_warehouse'");
}

//note on oracle
function get_package_condition($id_warehouse){
  global $db;
    return find_by_sql("SELECT * FROM package where jml_stock < package.safety_stock and package.id_warehouse = '$id_warehouse'");
}

//find field with order
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

function find_all_product_shipment($id_item) {
   global $db;
    $sql = $db->query("SELECT * FROM item,location,sub_categories,categories WHERE categories.id_categories = sub_categories.id_categories and item.id_subcategories = sub_categories.id_subcategories and item.id_location = location.id_location AND item.id_item = '$id_item'");
    return $db->fetch_assoc($sql);
}

function find_po_warehouse($id_warehouse) {
   global $db;
     $sql = $db->query("SELECT nm_warehouse FROM employer JOIN warehouse ON employer.id_warehouse = warehouse.id_warehouse WHERE employer.id_warehouse = '{$db->escape($id_warehouse)}'");
     return $db->fetch_assoc($sql);
}

function move_stock($id_item,$id_po) {
   global $db;
     $sql = $db->query("SELECT * FROM detil_po where id_item = '{$db->escape($id_item)}' and status = 'Approved' and id_po = '$id_po'");
     return $db->fetch_assoc($sql);
}

function find_product_fetch($id_item) {
   global $db;
     $sql = $db->query("SELECT * FROM item  WHERE id_item = '$id_item'");
     return $db->fetch_assoc($sql);
}

function find_package_fetch($id_package) {
   global $db;
     $sql = $db->query("SELECT * FROM package  WHERE id_package = '$id_package'");
     return $db->fetch_assoc($sql);
}

function find_bpack_fetch($id_bpack) {
   global $db;
     $sql = $db->query("SELECT * FROM bpack WHERE id_bpack = '$id_bpack'");
     return $db->fetch_assoc($sql);
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

//find all employee 
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
      $sql = "SELECT employer.id_employer,nm_employer,username,position.nm_position,employer.status,employer.last_login,warehouse.nm_warehouse,position.id_position,warehouse.id_warehouse,image FROM employer JOIN position ON employer.id_position=position.id_position JOIN warehouse ON employer.id_warehouse = warehouse.id_warehouse WHERE position.level_user = '1' ORDER BY employer.nm_employer ASC";
      $result = find_by_sql($sql);
      return $result;
  }

function notification($id_warehouse){
  global $db;
  $sql = $db->query("SELECT po.id_po,po.date_po,po.id_warehouse as For_wh,detil_po.date_po,qty,status,detil_po.id_warehouse as From_wh,total_weight,id_item FROM po JOIN detil_po WHERE po.id_po = detil_po.id_po AND po.id_warehouse = '$id_warehouse' and status = 'On Process' GROUP by po.id_po");
    $result = $db->num_rows($sql);
  return $result;
}

function find_all_detailPO($id_warehouse,$id_po){
  global $db;
  $results = array();
  $sql = "SELECT po.id_po,po.date_po,po.id_warehouse,status,detil_po.id_item,total_weight,qty,detil_po.id_warehouse as from_wh FROM po JOIN detil_po ON po.id_po = detil_po.id_po WHERE po.id_warehouse = '$id_warehouse' and po.id_po = '$id_po'";
  $result = find_by_sql($sql);
  return $result;
}

function find_all_detailPO_admin($id_po){
  global $db;
  $results = array();
  $sql = "SELECT po.id_po,po.date_po,po.id_warehouse,status,detil_po.id_item,total_weight,qty,detil_po.id_warehouse as From_wh FROM po JOIN detil_po ON po.id_po = detil_po.id_po WHERE po.id_po = '$id_po' order by po.id_po desc";
  $result = find_by_sql($sql);
  return $result;
}

  function find_all_listPO($id_warehouse){
      global $db;
      $results = array();
      $sql = "SELECT po.id_po,po.date_po as date_po,po.id_warehouse as For_wh,detil_po.date_po as date_send,qty,status,detil_po.id_warehouse as From_wh,total_weight,id_item FROM po JOIN detil_po WHERE po.id_po = detil_po.id_po AND po.id_warehouse = '$id_warehouse' and status = 'On Process' GROUP by po.id_po";
      $result = find_by_sql($sql);
      return $result;
  }

  function find_all_PO_destination($id_warehouse){
      global $db;
      $results = array();
      $sql = "SELECT detil_po.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_item,qty,employer.id_employer as id_emp FROM detil_po,employer,po WHERE po.id_po = detil_po.id_po and employer.id_warehouse = detil_po.id_warehouse and employer.id_warehouse = '$id_warehouse' and detil_po.status = 'Approved'";
      $result = find_by_sql($sql);
      return $result;
  }

  function find_all_PO_destination_notif($id_warehouse){
      global $db;
      $results = array();
      $sql = $db->query("SELECT detil_po.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_item,qty FROM detil_po,employer,po WHERE po.id_po = detil_po.id_po and employer.id_warehouse = detil_po.id_warehouse and employer.id_warehouse = '$id_warehouse' and detil_po.status = 'Approved'");
      $result = $db->num_rows($sql);
      return $result;
  }

 function find_all_PO_shipment_notif($id_warehouse){
      global $db;
      $results = array();
      $sql = $db->query("SELECT po.id_po,po.date_po as date_po,qty,status,po.id_warehouse as for_wh,total_weight,id_item, detil_po.date_po as date_sent, detil_po.id_warehouse as from_wh from detil_po,po WHERE po.id_po = detil_po.id_po and status = 'On Destination' and po.id_warehouse = '$id_warehouse' ORDER by 1 desc");
      $result = $db->num_rows($sql);
      return $result;
  }

  function find_all_item_under_stock( $id_warehouse){
      global $db;
      $results = array();
      $sql = $db->query("SELECT * FROM item,location,warehouse WHERE item.id_location = location.id_location and warehouse.id_warehouse = location.id_warehouse and warehouse.id_warehouse = '$id_warehouse' and stock < safety_stock ORDER by 1 DESC");
      $result = $db->num_rows($sql);
      return $result;
  }

  function find_all_package_under_stock( $id_warehouse){
      global $db;
      $results = array();
      $sql = $db->query("SELECT * FROM package WHERE package.id_warehouse = '$id_warehouse' and jml_stock < safety_stock ORDER by 1 DESC");
      $result = $db->num_rows($sql);
      return $result;
  }

function find_all_shippment($id_warehouse){
      global $db;
      $results = array();
      $sql = "SELECT shippment.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_item,qty,employer.id_employer as id_emp FROM detil_po,employer,po WHERE po.id_po = detil_po.id_po and employer.id_warehouse = detil_po.id_warehouse and employer.id_warehouse = '$id_warehouse' and detil_po.status = 'Approved'";
      $result = find_by_sql($sql);
      return $result;
  }
  function find_all_shippment_notif($id_warehouse){
      global $db;
      $results = array();
      $sql = $db->query("SELECT detil_po.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_item,qty FROM detil_po,employer,po WHERE po.id_po = detil_po.id_po and employer.id_warehouse = detil_po.id_warehouse and employer.id_warehouse = '$id_warehouse' and detil_po.status = 'Approved'");
      $result = $db->num_rows($sql);
      return $result;
  }
  function find_all_PO_destination_admin($id_warehouse){
      global $db;
      $results = array();
      $sql = "SELECT detil_po.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_item,qty,employer.id_employer as id_emp  FROM detil_po,employer,po WHERE po.id_po = detil_po.id_po and employer.id_warehouse = detil_po.id_warehouse and employer.id_warehouse = '$id_warehouse' and detil_po.status = 'Approved' GROUP by detil_po.id_item ";
      $result = find_by_sql($sql);
      return $result;
  }

  function find_all_PO_destination_admin_notif($id_warehouse){
      global $db;
      $results = array();
      $sql = $db->query("SELECT detil_po.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_item,qty FROM detil_po,employer,po WHERE po.id_po = detil_po.id_po and employer.id_warehouse = detil_po.id_warehouse and employer.id_warehouse = '$id_warehouse' and detil_po.status = 'Approved' GROUP by detil_po.id_item");
      $result = $db->num_rows($sql);
      return $result;
  }

  function find_all_historyPO($id_warehouse){
      global $db;
      $results = array();
      $sql = "SELECT * FROM po WHERE id_warehouse = '$id_warehouse' order by id_po desc";
      $result = find_by_sql($sql);
      return $result;
  }

    function find_all_canceledPO_detil($id_warehouse,$id_po){
      global $db;
      $results = array();
      $sql = "SELECT po.id_po as id_po, po.date_po as date_po, detil_po.date_po as date_send, id_item, qty,po.id_warehouse as for_wh, detil_po.id_warehouse as from_wh, status FROM po,detil_po WHERE po.id_po = detil_po.id_po and status = 'Canceled' and po.id_warehouse = '$id_warehouse' and po.id_po = '$id_po' order by 1 desc";
      $result = find_by_sql($sql);
      return $result;
  }

  function find_all_canceledPO($id_warehouse){
    global $db;
    $results = array();
    $sql = "SELECT po.id_po as id_po, po.date_po as date_po, detil_po.date_po as date_send, detil_po.id_warehouse as from_wh,po.id_warehouse as for_wh,detil_po.id_item FROM po,detil_po WHERE po.id_po = detil_po.id_po and po.id_warehouse = '$id_warehouse' and status = 'Canceled' GROUP by po.id_po order by 1 desc";
    $result = find_by_sql($sql);
    return $result;
  }

  function find_all_canceled_notif($id_warehouse){
      global $db;
      $results = array();
      $sql = $db->query("SELECT po.id_po as id_po, po.date_po as date_po, detil_po.date_po as date_send, id_item, qty,po.id_warehouse as for_wh, detil_po.id_warehouse as from_wh, status FROM po,detil_po WHERE po.id_po = detil_po.id_po and status = 'Canceled' and po.id_warehouse = '$id_warehouse' group by po.id_po order by 1 DESC");
      $result = $db->num_rows($sql);
      return $result;
  }

  function find_all_history_shipment($id_warehouse){
      global $db;
      $results = array();
      $sql = "SELECT * FROM shipment where id_warehouse='$id_warehouse'";
      $result = find_by_sql($sql);
      return $result;
  }


  function find_all_PO_approved2($id_warehouse){
      global $db;
      $results = array();
      $sql = "SELECT detil_po.id_po,po.date_po as date_po,detil_po.date_po as date_send, detil_po.status,po.id_warehouse as for_wh, detil_po.id_item,qty FROM detil_po,employer,po WHERE po.id_po = detil_po.id_po and employer.id_warehouse = detil_po.id_warehouse and employer.id_warehouse = '$id_warehouse' and detil_po.status = 'On Destination' order by detil_po.id_po desc";
      $result = find_by_sql($sql);
      return $result;
  }

  function find_all_PO_approved1(){
      global $db;
      $results = array();
      $sql = "SELECT po.id_po,po.date_po as date_po, detil_po.date_po as date_send,status, po.id_warehouse as for_wh, detil_po.id_item,detil_po.qty FROM po,detil_po WHERE detil_po.id_po = po.id_po group by po.id_po order by po.id_po desc";
      $result = find_by_sql($sql);
      return $result;
  }

  function find_all_PO_admin(){
      global $db;
      $results = array();
      $sql = "SELECT po.id_po,po.date_po as date_po,po.id_warehouse as For_wh,detil_po.date_po as date_send,qty,status,detil_po.id_warehouse as From_wh,total_weight,id_item FROM po JOIN detil_po WHERE po.id_po = detil_po.id_po and status = 'On Process' GROUP by po.id_po";
      $result = find_by_sql($sql);
      return $result;
  }

  function find_all_PO_admin_notif($id_warehouse){
      global $db;
      $results = array();
      $sql = $db->query("SELECT po.id_po,po.date_po as date_po,po.id_warehouse as For_wh,detil_po.date_po as date_send,qty,detil_po.status,detil_po.id_warehouse as From_wh,total_weight,id_item FROM po,detil_po,employer WHERE po.id_po = detil_po.id_po and employer.id_warehouse = '$id_warehouse' and detil_po.status = 'On Process' GROUP by po.id_po");
      $result = $db->num_rows($sql);
      return $result;
  }

  function countDetail($id_po){
      global $db;
      $results = array();
      $sql = $db->query("SELECT sum(total_weight) as total FROM detil_po WHERE id_po = '$id_po' ");
      $result = $db->fetch_assoc($sql);
      return $result;
  }

  function find_adminName($id_warehouse){
      global $db;
      $results = array();
      $sql = "SELECT nm_position,id_position FROM position WHERE level_user = '1' and id_warehouse = '$id_warehouse' ";
      $result = find_by_sql($sql);
      return $result;
  }

  //validation connected foreign key 
  function find_all_idPosition($field){
      global $db;
      $results = array();
      $sql = "SELECT id_position FROM employer WHERE id_position = '{$db->escape($field)}'";
      $result = find_by_sql($sql);
      return $result;
  }

  //validation connected foreign key 
  function find_all_idEmployee($field){
      global $db;
      $results = array();
      $sql = "SELECT id_employer FROM shipment WHERE id_employer = '{$db->escape($field)}'";
      $result = find_by_sql($sql);
      return $result;
  }

  //validation connected foreign key
  function find_all_idItem($field){
      global $db;
      $results = array();
      $sql = "SELECT id_item FROM detil_po WHERE id_item = '{$db->escape($field)}'";
      $result = find_by_sql($sql);
      return $result;
  }

  //validation connected foreign key 
  function find_all_idItemPackage($field){
      global $db;
      $results = array();
      $sql = "SELECT id_item FROM bpack WHERE id_item = '{$db->escape($field)}'";
      $result = find_by_sql($sql);
      return $result;
  }

  //validation connected foreign key 
  function find_all_idPo($field){
      global $db;
      $results = array();
      $sql = "SELECT id_po FROM po WHERE id_po = '{$db->escape($field)}'";
      $result = find_by_sql($sql);
      return $result;
  }

  //validation connected foreign key 
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
  $id = $idwarehouse;
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
          $sql = $db->query("SELECT id_employer,username,nm_employer,{$db->escape($table)}.id_position,last_login,status,image,{$db->escape($table)}.id_warehouse,nm_position,level_user,password,image FROM {$db->escape($table)} JOIN position WHERE position.id_position = {$db->escape($table)}.id_position and id_employer='{$db->escape($id)}' LIMIT 1");
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

//delete function for all method 
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
/* Function for Count id  By table name
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

function count_by_id_pro($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id_item) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}

function count_by_warehouse($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id_warehouse) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}

function count_total_bpack($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT sum(total) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}

function count_by_all($id,$table,$id_warehouse){
  global $db;
    $sql    = "SELECT COUNT($id) AS total FROM $table WHERE id_warehouse = '$id_warehouse'";
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
}

function count_all_admin(){
  global $db;
  $sql = "SELECT COUNT(id_employer) as total FROM employer JOIN position WHERE employer.id_position = position.id_position and level_user = '1'";
  $result = $db->query($sql);
  return($db->fetch_assoc($result));
}

function count_by_all_employer($id_warehouse){
  global $db;
  $sql    = "SELECT COUNT(id_employer) as total FROM employer join position on position.id_position = employer.id_position and level_user != '0' where employer.id_warehouse = '$id_warehouse'";
  $result = $db->query($sql);
  return($db->fetch_assoc($result));
}

function count_superuser(){
  global $db;
  $sql    = "SELECT COUNT(*) FROM employer JOIN position ON employer.id_position = position.id_position WHERE position.level_user = '0'";
  $result = $db->query($sql);
  return($db->fetch_assoc($result));
}

function count_all_item($id_warehouse){
  global $db;
  $sql = "SELECT count(id_item) as total FROM item join location on item.id_location = location.id_location WHERE location.id_warehouse = '$id_warehouse'";
  $result = $db->query($sql);
  return($db->fetch_assoc($result));
}

function count_all_subcategories($id_warehouse){
  global $db;
  $sql = "SELECT COUNT(id_subcategories) as total FROM sub_categories JOIN categories on categories.id_categories = sub_categories.id_categories WHERE id_warehouse = '$id_warehouse'";
  $result = $db->query($sql);
  return($db->fetch_assoc($result));
}

function count_all_bpack($id_warehouse){
  global $db;
  $sql = "SELECT COUNT(bpack.id_bpack) as total FROM package JOIN bpack on package.id_package = bpack.id_package join item on item.id_item = bpack.id_item WHERE package.id_warehouse = '$id_warehouse'";
  $result = $db->query($sql);
  return($db->fetch_assoc($result));
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
  //authenticate for login 
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
  /* Find current log in user by session id 
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
  //find all employees 
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
      $sql = "SELECT id_item,nm_item,colour,item.width,item.height,item.length,item.weight,item.stock,item.id_package,item.id_subcategories,sub_categories.nm_subcategories,sub_categories.id_categories,package.id_package,package.nm_package,package.height as p_height,package.lenght as p_lenght,package.weight as p_weight,package.width as p_width,package.jml_stock,item.id_location FROM item,sub_categories,package WHERE item.id_package = package.id_package and item.id_subcategories = sub_categories.id_subcategories";
      $result = find_by_sql($sql);
      return $result;
  }
  
  function find_all_item_po(){
      global $db;
      $results = array();
      $sql = "SELECT * FROM item JOIN location ON item.id_location = location.id_location JOIN warehouse ON location.id_warehouse = warehouse.id_warehouse JOIN employer ON employer.id_warehouse=warehouse.id_warehouse AND employer.id_warehouse = '$id_warehouse' ";
      $result = find_by_sql($sql);
      return $result;
  }

  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/
  //update last login 
  function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE employer SET last_login='{$date}' WHERE id_employer ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

function status_shipment($id_warehouse)
{
  $sql="SELECT po.id_po,po.date_po as date_po,qty,status,po.id_warehouse as for_wh,total_weight,id_item, detil_po.date_po as date_sent, detil_po.id_warehouse as from_wh from detil_po,po WHERE po.id_po = detil_po.id_po and status = 'On Destination' and po.id_warehouse = '$id_warehouse'";
  return find_by_sql($sql);
}

function insert_new_id($id_warehouse)
{ 
  global $db;
     $sql = $db->query("SELECT po.id_po,po.date_po as date_po,qty,status,po.id_warehouse as for_wh,total_weight,id_item, detil_po.date_po as date_sent, detil_po.id_warehouse as from_wh from detil_po,po WHERE po.id_po = detil_po.id_po and status = 'On Destination' and po.id_warehouse = '$id_warehouse'");
     return $db->fetch_assoc($sql);
}

function update_new_id($id_warehouse,$id_item)
{ 
  global $db;
     $sql = $db->query("SELECT qty from detil_po,po WHERE po.id_po = detil_po.id_po and status = 'On Destination' and po.id_warehouse = '$id_warehouse' and id_item = '$id_item'");
     return $db->fetch_assoc($sql);
}

function get_stock($id_item1)
{ 
  global $db;
     $sql = $db->query("SELECT stock from item where id_item = '$id_item1'");
     return $db->fetch_assoc($sql);
}

function get_id_product($nm_item,$id_warehouse)
{ 
  global $db;
     $sql = $db->query("SELECT id_item FROM item,location where nm_item = '$nm_item' and item.id_location = location.id_location and location.id_warehouse = '$id_warehouse'");
     return $db->fetch_assoc($sql);
}

//for message
function find_all_warehouse($table,$id) {
   global $db;
   if(tableExists($table))
   {
      $result = array();
     $result = find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_warehouse != '{$db->escape($id)}' ORDER BY nm_warehouse ASC");
    return $result;
   }
}

function find_all_message_from($id_warehouse) {
   global $db;
     $result = array();
     $result = find_by_sql("SELECT id_message,subject,message,date,to_warehouse,message.status, from_warehouse,nm_warehouse
      FROM message,warehouse where warehouse.id_warehouse = message.from_warehouse and to_warehouse = '$id_warehouse' order by 1 desc");
    return $result;
}

function find_all_message_history($id_warehouse) {
   global $db;
     $result = array();
     $result = find_by_sql("SELECT id_message,subject,message,date,to_warehouse as to_wh,message.status, from_warehouse,(SELECT nm_warehouse from warehouse where id_warehouse = to_wh GROUP by nm_warehouse) as nm_warehouse
      FROM message,warehouse where warehouse.id_warehouse = message.from_warehouse and from_warehouse = '$id_warehouse' order by 1 desc");
    return $result;
}

//notif
function find_all_message_from_notif($id_warehouse) {
  global $db;
  $results = array();
  $sql = $db->query("SELECT id_message,subject,message,date,to_warehouse, from_warehouse,nm_warehouse
      FROM message,warehouse where warehouse.id_warehouse = message.from_warehouse and to_warehouse = '$id_warehouse' and message.status = '0' order by 1 desc");
  $result = $db->num_rows($sql);
  return $result;
}

  /*--------------------------------------------------------------*/
  /* Find all position name 
  /*--------------------------------------------------------------*/
  function find_by_positionName($val,$id_warehouse)
  {
    global $db;
    $sql = "SELECT nm_position FROM position WHERE nm_position = '{$db->escape($val)}' AND id_warehouse = '$id_warehouse' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }

  //find categoryName
  function find_by_categoryName($val)
  {
    global $db;
    $sql = "SELECT nm_categories FROM categories WHERE nm_categories = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }

  //find itemName
  function find_by_itemName($val,$id_warehouse)
  {
    global $db;
    $sql = "SELECT nm_item FROM item,location,warehouse WHERE item.id_location = location.id_location and location.id_warehouse = warehouse.id_warehouse and nm_item = '{$db->escape($val)}' and warehouse.id_warehouse = '$id_warehouse' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }

  function max_warehouse(){
    global $db;
     $sql = $db->query("SELECT max(id_warehouse) as max from warehouse");
     return $db->fetch_assoc($sql);
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
   function page_require_level($require_level)
   {
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['level_user']);
     //if user not login

     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Please login...');
            redirect('index.php', false);
      //cheackin log in User level and Require level is Less than or equal to
        endif;
     }
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
    $sql  =" SELECT * from location";
    return find_by_sql($sql);

   }

  function join_po_table(){
     global $db;
    $sql  =" SELECT * from detil_po";
    return find_by_sql($sql);

   }

   function join_po_table1(){
    global $db;
   $sql  =" SELECT * from detil_po WHERE id_po=$_GET[no_po]";
   return find_by_sql($sql);

  }

  function join_product_table1(){
    global $db;
   $sql  =" SELECT * from location WHERE id_warehouse=$_GET[id_location]";
   return find_by_sql($sql);

  }

?>
