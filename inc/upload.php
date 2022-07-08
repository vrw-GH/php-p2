<!-- ----------------- CONTENTS: UPLOAD ------------------- -->
<div class="POST code">
   <?php
      $upload_dir = './myuploads/';
      $allowed_exts = array('.jpg', '.jpeg', '.png', '.gif'); // keep the '.' = needed for input "accept" filter
      $messages = array("Select or upload something.", implode(", ",$allowed_exts)); // default message
      $errors = array();

      // ---------- deleting a file ------------
      if (!empty($_GET["file2kill"])) {
         $file2kill = basename($_GET["file2kill"]);
         $messages = array();
         if (!file_exists($upload_dir . $file2kill)) {
            $messages[] = "This file does not exist.";
         }
         elseif (!unlink($upload_dir . $file2kill)) {
            $messages[] = $file2kill . ' cannot be deleted due to an error.';
         }
         else {
            $messages[] = $file2kill . ' has been deleted.';
         }
         if (!empty($_GET)) {
            unset($_GET); // clear GETs buffer -clean slate
         }
      }

      // ---------- downloading a file ------------
      if (!empty($_GET["file2download"])) {
         $file2download = basename($_GET["file2download"]);
         $messages = array();
         if (!file_exists($upload_dir . $file2download)) {
            $messages[] = "$file2download file not found.";
         }
         else {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            // header("Content-Type: ".getimagesize($upload_dir . $file2download)['mime']);
            header('Content-Disposition: attachment; filename="'. $file2download .'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($upload_dir . $file2download));
            flush(); // Flush system output buffer
            readfile($file2download);
            $messages[] = "File $file2download downloaded.";
            exit(); //! ??? why is this necessary?
         }
         if (!empty($_GET)) {
            unset($_GET); // clear GETs buffer -clean slate
         }
      }
     
      // ----------------- uploading a file -------------------            
      // ----------------- check if file upload error ----------------
      if (!empty($_FILES['upload-file']['error'])) {
         $errors[] = "Error in file upload. <i>(ie: PHP Max-size exceeded.)</i>";
         if (!empty($_GET)) {
            unset($_GET); // clear GETs -clean slate
         }
      }
      elseif (isset($_FILES['upload-file'])) {
         $file = $upload_dir . $_FILES['upload-file']['name'];
         if (!empty($_GET)) {
            unset($_GET); // clear GETs -clean slate
         }

         // ----------------- check if file exists -------------------      
         if (file_exists($file)) {
            $errors[] = "This file already exists.";
         }

         // ----------------- check allowed ext -------------------      
         // $ext = strtolower(substr($file,strrpos($file,'.')));
         $ext = '.' . strtolower(pathinfo($file, PATHINFO_EXTENSION)); // '.' needed to match $allowed_exts
         if (!in_array($ext, $allowed_exts)) {
            $errors[] = "Cannot upload <{$ext}> file type.";
         }
         else {
            // ----------------- check if image -------------------
            $mime = getimagesize($_FILES['upload-file']["tmp_name"]);
            if ($mime == false) {
               $errors[] = "File is not an image!";
            }
         }

         // ----------------- check size -------------------
         $size = $_FILES['upload-file']['size'];
         if ($size > 500000) {
            $errors[] = "File must be < 500kb.";
         }

         // ----------------- save file -------------------
         if (empty($errors)) {
            move_uploaded_file($_FILES['upload-file']['tmp_name'], $file);
         }
      }

      ?>
</div>

<div class="div_workarea">
   <div class="div_upload">
      <div class="div_form">
         <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="upload-file" accept="<?= implode(',', $allowed_exts); ?>" />
            <br>
            <input type="submit" value="Upload" />
            <br>
         </form>
         <hr>
         <i>Uploaded Files:</i><br>
         <div class="div_fileslist">
            <?php
               $filelist = scandir($upload_dir);
               unset($filelist[0], $filelist[1]);
               echo '<ol>';
               foreach ($filelist as $filekey => $file) {
                  $href = './index.php?page=upload&file=' . str_replace(" ", "%20", $file);
                  echo '<li><a href=' . $href . '>' . basename($file, '.' . strtolower(pathinfo($file, PATHINFO_EXTENSION))) . '</a></li>';
               }
               ;
               echo '</ol>';
               ?>
         </div>
      </div>
   </div>

   <div class="div_viewport">
      <?php
         if (!empty($_GET["file"])) {
            $file = $_GET["file"];
            if (in_array($file, $filelist)) {
               echo '<b>', basename($file, '.' . strtolower(pathinfo($file, PATHINFO_EXTENSION))), '</b><br>';
               $mime = getimagesize($upload_dir . $file);
               echo '<small>';
                  echo 'type="' . strtolower(pathinfo($file, PATHINFO_EXTENSION)) . '" ';
                  echo 'size="' . number_format(filesize($upload_dir . $file)/1000,0) . 'Kb" ';
                  echo isset($mime['3']) ? $mime['3'] : "(no size info)" ;
               echo '</small>';
               echo '<br><img src="' . $upload_dir . $file . '">';
               echo '<div style="position: absolute; top: 200px;">';
                  $href_file = urlencode($file);
                  echo '<a href="./index.php?page=upload&file2download=' . $href_file . '" style="background: rgba(10, 10, 10, 0.25); border-radius: 0 10px 3px 0;color: white; text-decoration: none;"><small>Download&nbsp;</small></a>';
                  echo " ";
                  echo '<a href="./index.php?page=upload&file2kill=' . $href_file . '" style="background: rgba(10, 10, 10, 0.25); border-radius: 0 10px 3px 0;color: white; text-decoration: none;"><small>Delete&nbsp;</small></a>';
               echo '</div>';
            }
            else {
               echo '<h4>no such file.</h4>';
            }            
         }
         elseif (isset($_FILES['upload-file']) && !empty($_FILES['upload-file']['name'])) {
            if (!empty($errors)) {
               echo 'File upload error: <small><i>' . $_FILES['upload-file']['name'] . '</i></small><br>';
               foreach ($errors as $error) {
                  echo '<p style="color: red;">&nbsp;• ', $error, '</p>';
               }
            }
            else {
               $file = $_FILES['upload-file']['name'];
               $href = 'index.php?page=' . $page . '&file=' . str_replace(" ", "%20", $file);
               echo '<b>', basename($file), '</b><br>';
               echo '<i>** Uploaded ** </i><small>Click image for details</small>';
               echo '<br><a href=' . $href . '><img src="' . $upload_dir . $file . '" ></a>';
            }
         }
         else {
            echo implode("<br>",$messages);
         }
      ?>
   </div>
</div>