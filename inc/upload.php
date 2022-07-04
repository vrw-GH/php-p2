<!-- ----------------- UPLOAD ------------------- -->
<?php
   // keep '.' = needed for input "accept" filter
   $upload_dir = './uploads/';
   $allowed_exts = array('.jpg', '.jpeg', '.png', '.gif'); 

   if (isset($_FILES['upload-file']) && !empty($_FILES['upload-file']['tmp_name'])) {
      $file = $upload_dir . $_FILES['upload-file']['name'];
      $errors = array();

      // echo $_FILES['upload-file']["name"],'<br>'; //! check
      // echo $_FILES['upload-file']["tmp_name"],'<br>'; //! check
      // echo $_FILES['upload-file']["size"],'<br>'; //! check

      // ----------------- check if file exists -------------------      
      if (file_exists($file)) {
         $errors[] = "This file already exists.";
      }
      
      // ----------------- check allowed ext -------------------      
      // $ext = strtolower(substr($file,strrpos($file,'.')));
      $ext = '.' . strtolower(pathinfo($file,PATHINFO_EXTENSION));  // '.' needed to match $allowed_exts
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
         <!-- <br> -->
         <input type="submit" value="Upload" />
         <br>
      </form>

      <div class="div_thumbview">
         <p style="color: red">
            <?php
               if (isset($_FILES['upload-file']) && !empty($_FILES['upload-file']['name'])) {
                  if (!empty($errors)) {
                     echo '<small><i>File upload error: </i></small><br>';
                     foreach ($errors as $error) {
                        echo '&nbsp;• ',$error,'<br>';
                     };
                  } else {
                     $file = $_FILES['upload-file']['name'];
                     $href = 'index.php?page='.$page.'&file='.$file;
                     echo '<a href='.$href.'><img src="'.$upload_dir.$file.'" height="200"></a>';
                     echo '<br><i>** Uploaded **</i>';
                  };
               }
            ?>
         </p>
      </div>
   </div>
   <hr>
   <i>Uploaded Files:</i><br>
   <div class="div_filelist">

      <?php
         $filelist = scandir($upload_dir);
         unset($filelist[0],$filelist[1]);
            foreach ($filelist as $filekey => $file) {
               $href = './index.php?page=upload&file='.str_replace(" ","%20",$file);
               echo '<a href='.$href.'>'.$file.'</a><br>';
            };
         
      ?>

   </div>
</div>
<!-- <div class="div_fileview"> -->
<?php      
      if (!empty($_GET["file"])) {
         $file = $_GET["file"];
         echo '<b>',$file,'</b><br>';
         $mime = getimagesize($upload_dir . $file);
         if (isset($mime['3'])) echo $mime['3'];
         if (in_array($file,$filelist)) {
            echo '<div class="div_fileview">';
            echo '<img src="'.$upload_dir.$file.'" >';
            echo '</div>';
         } else {
            echo '<h4>no such file.</h4>';
         };            
      };
   ?>
<!-- </div> -->