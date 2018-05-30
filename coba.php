<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="style.css">
</head>
<body>

<?php 

$page_title = 'Coba';
require_once('includes/load.php');

$sql = 'SELECT * FROM item';
$result  = $db->query($sql); 
 ?>
<h2> Select the items: </h2>
 <table border="1">
 <tr>
 	<th>id_item</th>
	<th>nm_item</th>
	 <th>colour</th>
	  <th>width</th>
	  <th>length</th>
	  <th>weight</th>
	  <th>stock</th>
	  <th>id_package</th>
	  <th>id_subcategories</th>
	  <th>id_location</th>
	  <th>Coba</th>
 </tr>
 	<?php while($product = mysqli_fetch_object($result)) { ?> 
	<tr>
		<td> <?php echo $product->id_item; ?> </td>
		<td> <?php echo $product->nm_item; ?> </td>
		<td> <?php echo $product->colour; ?> </td>
		<td> <?php echo $product->width; ?> </td>
		<td> <?php echo $product->length; ?> </td>
		<td> <?php echo $product->weight; ?> </td>
		<td> <?php echo $product->stock; ?> </td>
		<td> <?php echo $product->id_package; ?> </td>
		<td> <?php echo $product->id_subcategories; ?> </td>
		<td> <?php echo $product->id_location; ?> </td>
		<td> <a href="po.php?id_item=<?php echo $product->id_item; ?>">Order Now</a> </td>
	</tr>
	<?php } ?>
 </table>
</body>

 </html>