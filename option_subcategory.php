<?php
// Load file koneksi.php
include "includes/config.php";
// Ambil data ID Provinsi yang dikirim via ajax post
$id_categories = $_POST['categories'];
// Buat query untuk menampilkan data kota dengan provinsi tertentu (sesuai yang dipilih user pada form)
$sql = "SELECT * FROM sub_categories WHERE id_categories= $id_categories ORDER BY nm_subcategories";
$html = "<option value=''>Pilih</option>";
while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql
  $html .= "<option value='".$data['id']."'>".$data['nama']."</option>"; // Tambahkan tag option ke variabel $html
}
$callback = array('data_subcategory'=>$html); // Masukan variabel html tadi ke dalam array $callback dengan index array : data_kota
echo json_encode($callback); // konversi varibael $callback menjadi JSON
?>