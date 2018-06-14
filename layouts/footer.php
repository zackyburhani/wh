     </div>
    </div>
  <script type="text/javascript" src="8bit.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript" src="libs/js/functions.js"></script>
  
  <!-- DataTables JavaScript -->
  <script src="libs/datatables/jquery.dataTables.min.js"></script>
  <script src="libs/datatables/dataTables.bootstrap.min.js"></script>
  <script src="libs/datatables/dataTables.bootstrap.js"></script>
  <!-- END DATATABLES -->

  <!-- Sweet Alert -->
  <script src="libs/js/sweetalert.min.js"></script>


<?php if(isset($db)) { $db->db_disconnect(); } ?>

<script type="text/javascript">
	$(document).ready( function () {
      $('#tableWarehouse').DataTable();
    	$('#datatableProduct').DataTable();
      $('#CategoriesTable').DataTable();
      $('#tablePosition').DataTable();
      $('#tableUser').DataTable();
      $('#SubcategoriesTable').DataTable();
      $('#tablePackage').DataTable();
      $('#tablelocation').DataTable();

  	});
</script>

<script> 
  window.setTimeout(function() {
 		$(".alert-success").fadeTo(900, 0).slideUp(900, function(){ $(this).remove(); });
 		$(".alert-danger").fadeTo(900, 0).slideUp(900, function(){ $(this).remove(); }); 
 	}, 3000); 
</script>


 </body>
</html>
