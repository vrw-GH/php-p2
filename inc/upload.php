<!-- ----------------- UPLOAD ------------------- -->
<?php
   $allowed_exts = array('.jpg', '.jpeg', '.png', '.gif'); // with '.' = needed for input "accept" filter

   if (isset($_FILES['upload-file']) && !empty($_FILES['upload-file']['name'])) {
      $file = './uploads/' . $_FILES['upload-file']['name'];
      $errors = array();

      // ----------------- check if file exists -------------------      
      if (file_exists($file)) {
         $errors[] = "This file already exists.";
       }
      
      // ----------------- check allowed ext -------------------      
      // $ext = strtolower(substr($file,strrpos($file,'.')));
      $ext = '.' . strtolower(pathinfo($file,PATHINFO_EXTENSION));  // '.' needed for $allowed_exts
      if (!in_array($ext, $allowed_exts)) {
         $errors[] = "Cannot upload <{$ext}> file type.";
      } else {
         // ----------------- check if image -------------------
         $mime = getimagesize($_FILES['upload-file']["tmp_name"]);
         if ($mime == false) {
            $errors[] = "File is not an image!";
         }
      }
      
      // ----------------- check size -------------------
      $size = $_FILES['upload-file']['size'];
      if ($size>500000) {
         $errors[] = "File must be < 500kb.";
      }

      // ----------------- save file -------------------
      if (empty($errors)) {
         move_uploaded_file($_FILES['upload-file']['tmp_name'],$file);
      }
   }
?>

<div class="div_upload">
   <div class="div_form">
      <form action="" method="post" enctype="multipart/form-data">
         <input type="file" name="upload-file" accept="<?=implode(',',$allowed_exts);?>" />
         <br>
         <input type="submit" value="Upload" />
         <br>
      </form>
      <?php
         if (isset($_FILES['upload-file']) && !empty($_FILES['upload-file']['name'])) {
            if (!empty($errors)) {
               echo '<p style="color: red">';
               echo '<small><i>File upload error: </i></small><br>';
               foreach ($errors as $error) {
                  echo '&nbsp;• ',$error,'<br>';
               };
               echo '</p>';
            } else {
               echo '<div  style="width: 80px;"> ** Uploaded **'; // class="div_thumbview" 
               echo '<a href="./uploads/', $_FILES['upload-file']['name'], '"><img src="./uploads/', $_FILES['upload-file']['name'], '"></a>';
               echo '</div>';
            };
         }
      ?>
      <hr>

      <i>Uploaded Files:</i>
      <?php
         $filelist = scandir('./uploads');
         unset($filelist[0],$filelist[1]);
         $page = $_GET['page'];

         echo '<p>';
            foreach ($filelist as $filekey => $file) {
               $href = 'index.php?page='.$page.'&file='.$file;
               echo '<a href='.$href.'>'.$file.'</a><br>';
            };
         echo '</p>';
      ?>
   </div>
   <div class="div_fileview">
      <?php      
         if (!empty($_GET["file"])) {
            $file = $_GET["file"];
            echo '<h4 style="margin-top:0">',$file,'</h4>';
            if (in_array($file,$filelist)) {
               echo '<img src="uploads/',$file,'" >';
            } else {
               echo '<h4>no such file.</h4>';
            };            
         };
      ?>
   </div>
</div>