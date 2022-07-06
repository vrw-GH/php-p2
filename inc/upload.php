<!-- ----------------- CONTENTS: UPLOAD ------------------- -->
<div class="POST code">
   <?php
   $upload_dir = './uploads/';
   $messages = array("Select or upload something."); // default message
   $allowed_exts = array('.jpg','.jpeg','.png','.gif'); // keep the '.' = needed for input "accept" filter
   
   // ---------- deleting a file ------------
   if (!empty($_GET["file2kill"])) {
      $file2kill = basename($_GET["file2kill"]);
      unset($messages[0]);
      if (!file_exists($upload_dir.$file2kill)) {
         $messages[] = "This file does not exist.";
      } elseif (!unlink($upload_dir.$file2kill)) {
         $messages[] = $file2kill.' cannot be deleted due to an error.';
      } else {
         $messages[] = $file2kill.' has been deleted.';
      }
      if (!empty($_GET)) {
         unset($_GET); // clear GETs buffer -clean slate
      }
   }

   // ----------------- uploading a file -------------------      
   $errors = array();
   // ----------------- check if file upload error ----------------
   if (!empty($_FILES['upload-file']['error'])) {
      $errors[] = "Error in file upload.";
      if (!empty($_GET)) {
         unset($_GET);  // clear GETs -clean slate
      }
   } elseif (isset($_FILES['upload-file'])) {
      $file = $upload_dir . $_FILES['upload-file']['name'];
      if (!empty($_GET)) {
         unset($_GET);  // clear GETs -clean slate
      }

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
</div>

<div class="div_workarea">
   <div class="div_upload">
      <div class="div_form">
         <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="upload-file" accept="<?=implode(',',$allowed_exts);?>" />
            <br>
            <input type="submit" value="Upload" />
            <br>
         </form>
         <hr>
         <i>Uploaded Files:</i><br>
         <div class="div_fileslist">
            <?php
            $filelist = scandir($upload_dir);
            unset($filelist[0],$filelist[1]);
            echo '<ol>';
            foreach ($filelist as $filekey => $file) {
               $href = './index.php?page=upload&file='.str_replace(" ","%20",$file);
               echo '<li><a href='.$href.'>'.basename($file, '.'.strtolower(pathinfo($file,PATHINFO_EXTENSION))).'</a></li>';
            };
            echo '</ol>';
         ?>
         </div>
      </div>
   </div>

   <div class="div_viewport">
      <?php
      if (!empty($_GET["file"])) {
         $file = $_GET["file"];
         if (in_array($file,$filelist)) {
            echo '<b>',basename($file, '.'.strtolower(pathinfo($file,PATHINFO_EXTENSION))),'</b><br>';
            $mime = getimagesize($upload_dir . $file);
            echo '<small>';
            echo 'type="' . strtolower(pathinfo($file,PATHINFO_EXTENSION)).'" ';
            if (isset($mime['3'])) {
               echo $mime['3'];         
            }
            echo '</small>';
            $href = './index.php?page=upload&file2kill='.str_replace(" ","%20",$file);            
            echo '<br><img src="'.$upload_dir.$file.'">';
            echo '<div style="position: absolute; top: 200px; background: rgba(10, 10, 10, 0.25); border-radius: 0 10px 3px 0;">';
            echo '<a href='.$href.' style="color: white; text-decoration: none;"><small>Delete&nbsp;</small></a>';
            echo '</div>';
         } else {
            echo '<h4>no such file.</h4>';
         };
      } elseif (isset($_FILES['upload-file']) && !empty($_FILES['upload-file']['name'])) {
            if (!empty($errors)) {
               echo 'File upload error: <small><i>' . $_FILES['upload-file']['name'] . '</i></small><br>';
               foreach ($errors as $error) {
                  echo '<p style="color: red;">&nbsp;• ',$error,'</p>';
               };
            } else {
               $file = $_FILES['upload-file']['name'];
               $href = 'index.php?page='.$page.'&file='.str_replace(" ","%20",$file);
               echo '<b>',basename($file),'</b><br>';
               echo '<i>** Uploaded ** </i><small>Click image for details</small>';
               echo '<br><a href='.$href.'><img src="'.$upload_dir.$file.'" ></a>';               
            };
         } else echo implode($messages);
   ?>
   </div>
</div>