<?php
require_once('includes/load.php');

$id = $_GET['id'];
// memanggil library FPDF
require('fpdf/fpdf.php');
// intance object dan memberikan pengaturan halaman PDF
$pdf = new FPDF("L","cm","A4");

$pdf->SetMargins(2,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',16);
$pdf->Image('img/ikea_ori.png',2,1,2,1);
$pdf->SetX(5);            
$pdf->MultiCell(25,0.5,'IKEA International Group',0,'L');
$pdf->SetX(5);
$pdf->MultiCell(25,0.5,'',0,'L');    
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
//$pdf->MultiCell(19.5,0.5,'Jl. Sultan Agung No.31, Medan Satria, Kota Bekasi, Jawa Barat 17132',0,'L');
//$pdf->SetX(4);
$pdf->MultiCell(19.5,0.5,'Website : http://www.ikea.com/',0,'L');
$pdf->Line(1,3.1,28.5,3.1);
$pdf->SetLineWidth(0.1);      
$pdf->Line(1,3.2,28.5,3.2);   
$pdf->SetLineWidth(0);
$pdf->ln(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,0.7,'PURCHASE ORDER REPORT',0,0,'C');
$pdf->ln(1);
// Memberikan space kebawah agar tidak terlalu rapat
$pdf->Cell(1,1,'',0,1);
 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(3,0.5,'NO.PO',1,0,'C');
$pdf->Cell(3,0.5,'DATE',1,0,'C');
$pdf->Cell(6,0.5,'ITEM',1,0,'C');
$pdf->Cell(4,0.5,'FROM',1,0,'C');
$pdf->Cell(4,0.5,'TO',1,0,'C');
$pdf->Cell(2,0.5,'QTY',1,0,'C');
$pdf->Cell(4,0.5,'SUM WEIGHT',1,1,'C');
//$pdf->Cell(20,6,'TOTAL WEIGHT',1,1);

 
$pdf->SetFont('Arial','',10);
 
global $db;
$report = $db->query("SELECT po.id_po as no_po, nm_item, qty, total_weight,detil_po.id_warehouse as warehouse_from, po.id_warehouse as warehouse_to,po.date_po as datepo FROM po JOIN detil_po ON po.id_po = detil_po.id_po JOIN item on detil_po.id_item = item.id_item join warehouse on detil_po.id_warehouse = warehouse.id_warehouse WHERE po.id_po = '$id' order by po.id_po desc");
if (!$report) {
    printf("Error: %s\n", mysqli_error($connect));
    exit();
}
while ($row = $db->fetch_assoc($report))
{
    $pdf->Cell(3,0.5,$row['no_po'],1,0,'C');
    $pdf->Cell(3,0.5,$row['datepo'],1,0,'C');
    $pdf->Cell(6,0.5,$row['nm_item'],1,0,'C');
    $pdf->Cell(4,0.5,$row['warehouse_from'],1,0,'C');
    $pdf->Cell(4,0.5,$row['warehouse_to'],1,0,'C');
    $pdf->Cell(2,0.5,$row['qty'],1,0,'C');
    $pdf->Cell(4,0.5,$row['total_weight']." /Kg",1,1,'C');
}

$query=$db->query("SELECT SUM(total_weight) AS sum_weight FROM detil_po WHERE id_po = '$id'");
if (!$query) {
    printf("Error: %s\n", mysqli_error($connect));
    exit();
}
while($total = $db->fetch_assoc($query))
{
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(22,0.5,"TOTAL WEIGHT", 1, 0,'C');		
	$pdf->Cell(4,0.5, number_format($total['sum_weight'])." /kg", 1, 0,'C');	
}

 
$pdf->Output("Purchase_Order_Report.pdf","I");
?>