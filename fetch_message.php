<?php
require_once('includes/connect.php');

if(isset($_POST['view'])){

// $con = mysqli_connect("localhost", "root", "", "notif");

if($_POST["view"] != '')
{
    $update_query = $bd->query("UPDATE message SET status = 1 WHERE status=0");
    mysqli_query($con, $update_query);
}
$query = $db->query("SELECT * FROM message ORDER BY id_message DESC LIMIT 5");
$result = mysqli_query($con, $query);
$output = '';
if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_array($result))
 {
   $output .= '';

 }
}
else{
     $output .= '
     <li><a href="message.php" class="text-bold text-italic">No Noti Found</a></li>';
}

$status_query = $db->query("SELECT * FROM message WHERE status=0");
$result_query = mysqli_query($con, $status_query);
$count = mysqli_num_rows($result_query);
$data = array(
    'notification' => $output,
    'unseen_notification'  => $count
);

echo json_encode($data);

}

?>