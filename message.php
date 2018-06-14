<?php
  $page_title = 'Message';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $user = current_user();
   $all_warehouse = find_all_warehouse('warehouse',$user['id_warehouse']);
   $all_message   = find_all_message_from($user['id_warehouse']);


?>

<!-- ADD NEW MESSAGE -->
<?php
  if(isset($_POST['send_message'])){

   $req_fields = array('message','subject');
   validate_fields($req_fields);

   if(empty($errors)){
        $to_warehouse    = remove_junk($db->escape($_POST['to_warehouse']));
        $subject         = remove_junk($db->escape($_POST['subject']));
        $message         = remove_junk($db->escape($_POST['message']));
        $date            = date("Y-m-d");
        $status          = 0;
        $from_warehouse  = $user['id_warehouse'];
        $query  = "INSERT INTO message (";
        $query .="subject,message,to_warehouse,date,from_warehouse";
        $query .=") VALUES (";
        $query .=" '{$subject}','{$message}','{$to_warehouse}','{$date}','{$from_warehouse}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"The Message Has Been Sent !");
          redirect('message.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Sent The Message !');
          redirect('message.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('message.php',false);
   }
 }

 $all_position = find_all_position_admin2($user['id_warehouse']);
?>
<!-- END NEW MESSAGE -->

<!-- REPLY MESSAGE -->
<?php
  if(isset($_POST['reply_message'])){

   $req_fields = array('message','subject');
   validate_fields($req_fields);

   if(empty($errors)){
        $to_warehouse    = remove_junk($db->escape($_POST['to_warehouse']));
        $subject         = remove_junk($db->escape($_POST['subject']));
        $message         = remove_junk($db->escape($_POST['message']));
        $date            = date("Y-m-d");
        $from_warehouse  = $user['id_warehouse'];
        $query  = "INSERT INTO message (";
        $query .="subject,message,to_warehouse,date,from_warehouse";
        $query .=") VALUES (";
        $query .=" '{$subject}','{$message}','{$to_warehouse}','{$date}','{$from_warehouse}'";
        $query .=")";

        //update status
        $update = "UPDATE message set status = 1 WHERE id_message = '$id_message'";

        if($db->query($query)){
          $db->query($update);
          //sucess
          $session->msg('s',"The Message Has Been Sent !");
          redirect('message.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Sent The Message !');
          redirect('message.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('message.php',false);
   }
 }

 $all_position = find_all_position_admin2($user['id_warehouse']);
?>
<!-- REPLY MESSAGE -->

<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="fa fa-envelope"></span>
        <span>Message</span>
     </strong>
     <button title="Message" type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#message"><span class="glyphicon glyphicon-plus"></span> Message
        </button>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePosition">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No. </th>
            <th class="text-center">Sender</th>
            <th class="text-center">Subject</th>
            <th class="text-center">Date</th>
            <th class="text-center" style="width: 150px;">Detail</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; ?>
          <?php foreach($all_message as $wh){ ?>
          <tr>
            <td class="text-center"><?php echo $no++."."?></td>
            <td class="text-center"><?php echo remove_junk(ucwords($wh['nm_warehouse']))?></td>
            <td class="text-center"><?php echo remove_junk(ucwords($wh['subject']))?></td>
            <td class="text-center"><?php echo remove_junk(ucwords($wh['date']))?></td>
            <td class="text-center">
                <input type="hidden" id="id_message" value="<?php echo $wh['id_message'] ?>">
                <button class="btn btn-md btn-primary" data-target="#detailMessage<?php echo $wh['id_message'] ?>" data-toggle="modal" title="Detail Message"><i class="fa fa-envelope"></i>
                </button>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
     </div>
    </div>
  </div>
</div>


<!-- Entry Modal -->
<div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><span class="fa fa-envelope"></span> Send Message</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="message.php" class="clearfix">
        <div class="form-group">
          <label for="send" class="control-label">Send To</label>
          <select class="form-control" name="to_warehouse">
            <?php foreach($all_warehouse as $wh) { ?>
            <option value="<?php echo $wh['id_warehouse'] ?>"><?php echo $wh['nm_warehouse'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="subject" class="control-label">Subject</label>
          <input type="text" class="form-control" placeholder="Subject" name="subject" required>
        </div>
        <div class="form-group">
          <label for="message" class="control-label">Text</label>
          <textarea class="form-control" name="message" required style="height: 200px"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" name="send_message" title="Send" class="btn btn-primary"><span class="glyphicon glyphicon-send"></span> Send</button>
      </div>
    </form>
  </div>
</div>

  </div>
</div>

<!-- Detail Modal -->
<?php foreach($all_message as $ms){ ?>
<div class="modal fade" id="detailMessage<?php echo $ms['id_message'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><span class="fa fa-envelope"></span> Reply Message</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="message.php" class="clearfix">

        <table class="table table-bordered">
          <tr>
            <td width="60px">Sender</td>
            <td class="text-center">:</td>
            <td><strong><?php echo $ms['nm_warehouse']; ?></strong></td>
          </tr>
          <tr>
            <td>Date</td>
            <td class="text-center" style="width: 5px">:</td>
            <td><strong><?php echo $ms['date']; ?></strong></td>
          </tr>
          <tr>
            <td>Message</td>
            <td class="text-center">:</td>
            <td><strong>  <?php echo $ms['message']; ?> </strong></td>
          </tr>
        </table>

        <hr>

        <input type="hidden" name="to_warehouse" value="<?php echo $ms['from_warehouse'] ?>">
        <input type="hidden" name="id_message" value="<?php echo $ms['id_message'] ?>">

        <div class="form-group">
          <label for="subject" class="control-label">Subject</label>
          <input type="text" class="form-control" placeholder="Subject" name="subject" required>
        </div>
        <div class="form-group">
          <label for="message" class="control-label">Text</label>
          <textarea class="form-control" name="message" required style="height: 200px"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" name="reply_message" title="Send" class="btn btn-primary"><span class="glyphicon glyphicon-send"></span> Send</button>
      </div>
    </form>
  </div>
</div>
<?php } ?>



<?php include_once('layouts/footer.php'); ?>

<script type="text/javascript">
  //Update Barang

  $('#my-modal').modal({
    show: 'false'
  }); 

  $('#detail_message').on('click',function(){
    var id_message2 = document.getElementById('id_message');
    var id_message = id_message2
    console.log(id_message2);
      $.ajax({
        type : "POST",
        url  : "<?php echo base_url('index.php/barang/update_barang')?>",
        dataType : "JSON",
        data : {kobar:kobar , nabar:nabar, harga:harga},
        success: function(data){
          $('[name="kobar_edit"]').val("");
          $('[name="nabar_edit"]').val("");
          $('[name="harga_edit"]').val("");
          $('#ModalaEdit').modal('hide');
          tampil_data_barang();
        }
      });
    return false;
  });
</script>
