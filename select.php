<html>  
    <head>
        <title>Pilihan Berantai</title>
    </head>
    <body> 
        <?php
//        koneksi ke database
        $koneksi = mysqli_connect('localhost', 'root', '');
        mysqli_select_db($koneksi,'inventory');

        $page_title = 'Add Product';
          require_once('includes/load.php');
          // Checkin What level user has permission to view this page
          page_require_level(2);
           $all_subcategories  = find_all1('sub_categories');
           $all_categories  = find_all1('categories');
           $join_subcategories  = find_allSubcategories();

        ?>
            <!--provinsi-->
            <select id="category2" name="provinsi">
                <option value="">Please Select</option>
                <?php
                foreach($all_categories as $row) {
                ?>
                    <option value="<?php echo $row['id_categories']; ?>">
                        <?php echo $row['nm_categories']; ?>
                    </option>
                <?php
                }
                ?>
            </select>
            
            <!--kota-->
            <select id="sub_category2" name="kota">
                <option value="">Please Select</option>
                <?php
                foreach($join_subcategories as $row) {
                ?>
                    <option id="kota2" class="<?php echo $row['id_categories']; ?>" value="<?php echo $row['id_subcategories']; ?>">
                        <?php echo $row['nm_subcategories']; ?>
                    </option>
                <?php
                }
                ?>
            </select>

            <div class="col-md-3">
                <select id="category" name="id_categories">
                    <option value="">-</option>
                    <?php foreach($all_categories as $row){ ?>
                    <option value="<?php echo $row['id_categories']; ?>"><?php echo remove_junk(ucwords($row['nm_categories'])); ?></option>
                    <?php } ?>  
                </select>
            </div>
            
            <div class="col-md-3">
                <select id="sub_category" name="id_subcategories">
                    <option value="">-</option>
                    <?php foreach($join_subcategories as $row2){ ?>
                    <option class="<?php echo $row2['id_categories']; ?>" value="<?php echo remove_junk($row2['id_categories']); ?>"><?php echo remove_junk(ucwords($row2['nm_subcategories'])); ?></option>
                    <?php } ?>  
                </select>
            </div>
      
        <script src="jquery-1.10.2.min.js"></script>
        <script src="jquery.chained.min.js"></script>
        <script>
            $("#sub_category").chained("#category");
        </script>
        
    </body>
</html>