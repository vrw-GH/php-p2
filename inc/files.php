<!-- ----------------- CONTENTS: UPLOAD ------------------- -->
<div class="PHP code">
   <?php
   $baseurl = explode('?', $_SERVER['REQUEST_URI'], 2)[0] . '?page=files';
   $upload_dir = './fileuploads/';
   $allowed_exts = array('.jpg', '.jpeg', '.png', '.gif'); // keep the '.' = needed for input "accept" filter
   $messages = array("Select or upload something.", implode(", ", $allowed_exts)); // default message
   $errors = array();

   // ---------- deleting a file ------------
   if (isset($_GET["file2kill"])) {
      if (!empty($_GET["file2kill"])) {
         $file2kill = basename($_GET["file2kill"]);
         $messages = array();
         if (!file_exists($upload_dir . $file2kill)) {
            $messages[] = "This file does not exist.";
         } elseif (!unlink($upload_dir . $file2kill)) {
            $messages[] = $file2kill . ' cannot be deleted due to an error.';
         } else {
            $messages[] = $file2kill . ' has been deleted.';
            // header("Location:" . $baseurl);
         }
         if (!empty($_GET)) {
            unset($_GET); // clear GETs buffer -clean slate
            header("Location:" . $baseurl);
         }
      }
   }

   // ---------- downloading a file ------------
   if (isset($_GET["file2download"])) {
      if (!empty($_GET["file2download"])) {
         $file2download = urldecode($_GET["file2download"]);
         $filepath = $upload_dir . $file2download;
         $type = filetype($filepath);
         $messages = array();
         if (!file_exists($filepath)) {
            $messages[] = "$file2download - invalid file (or file not found!).";
            $messages[] = "( Requested : $filepath ).";
         } else {
            ob_get_clean();
            // ob_clean();  //! throws error    ???
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file2download) . '"');
            // header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer
            readfile($filepath);
            $messages[] = "File $file2download downloaded.";
            // exit(); //! ??? why is this necessary?
         }
         if (!empty($_GET)) {
            unset($_GET); // clear GETs buffer -clean slate
            // header("Location:" . $baseurl);
         }
      }
   }

   // ----------------- uploading a file -------------------            
   if (isset($_FILES['file2upload'])) {
      // ----------------- check if file upload error ----------------
      if (!empty($_FILES['file2upload']['error'])) {
         if (empty($_FILES['file2upload']['tmp_name'])) {
            $errors[] = "Please choose a file to upload.";
         } else {
            $errors[] = "Error in file upload. <i>(ie: PHP Max-size exceeded ??.)</i>";
         }
         if (!empty($_GET)) {
            unset($_GET); // clear GETs -clean slate
         }
      } else {
         $file = $_FILES['file2upload']['name'];
         $ext = '.' . strtolower(pathinfo($file, PATHINFO_EXTENSION)); // '.' needed to match $allowed_exts
         $filenm = pathinfo($file, PATHINFO_FILENAME);
         $file = $upload_dir . preg_replace('/[^A-Za-z0-9\-\_\(\)]/', '_', $filenm) . $ext;
         if (!empty($_GET)) {
            unset($_GET); // clear GETs -clean slate
         }

         // ----------------- check if file exists -------------------      
         if (file_exists($file)) {
            $errors[] = "This file already exists.";
         }

         // ----------------- check allowed ext -------------------      
         if (!in_array($ext, $allowed_exts)) {
            $errors[] = "Cannot upload <{$ext}> file type.";
         } else {
            // ----------------- check if image -------------------
            $mime = getimagesize($_FILES['file2upload']["tmp_name"]);
            if ($mime == false) {
               $errors[] = "File is not an image!";
            }
         }

         // ----------------- check size -------------------
         $size = $_FILES['file2upload']['size'];
         if ($size > 500000) {
            $errors[] = "File must be < 500kb.";
         }

         // ----------------- save file -------------------
         if (empty($errors)) {
            move_uploaded_file($_FILES['file2upload']['tmp_name'], $file);
         }
         if (!empty($_GET)) {
            unset($_GET); // clear GETs buffer 
            header("Location:" . $baseurl);
         }
      }
   }

   ?>
</div>

<div class="div_workarea">
   <div class="div_upload">
      <div class="div_form">
         <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="file2upload" accept="<?= implode(',', $allowed_exts); ?>" />
            <br>
            <input type="submit" value="Upload" />
            <br>
         </form>
         <hr>
         <i>Uploaded Files:</i><br>
         <div class="div_fileslist">
            <ol>
               <?php
               // $filelist = scandir($upload_dir);
               $filelist = array();
               $filelist = preg_grep('/^([^.])/', scandir($upload_dir)); // removes . and .. and also .[dot] files (.keep)
               // unset($filelist[0], $filelist[1]);
               sort($filelist, SORT_STRING || SORT_FLAG_CASE);
               foreach ($filelist as $filekey => $afile) {
                  if (!is_dir($afile)) {
                     $href = './index.php?page=files&file=' . pathinfo($afile, PATHINFO_BASENAME);
                     $a = pathinfo($afile, PATHINFO_BASENAME);
                     if (isset($_GET["file"]) && $a === $_GET["file"]) {
                        $li_style = 'style="background: yellow;"';
                     } else {
                        $li_style = '';
                     }
                     echo "<li $li_style >" . '<a href="' . $href . '">' .
                        pathinfo($afile, PATHINFO_FILENAME) . '</a></li>';
                  }
               }
               ?>
            </ol>
         </div>
      </div>
   </div>

   <div class="div_viewport">
      <?php
      if (isset($_GET["file"]) || !empty($_GET["file"])) {
         $file = $_GET["file"];
         if (in_array($file, $filelist)) {
            echo '<b>', basename($file, '.' . strtolower(pathinfo($file, PATHINFO_EXTENSION))), '</b><br>';
            $mime = getimagesize($upload_dir . $file);
            echo '<small>';
            echo 'type="' . strtolower(pathinfo($file, PATHINFO_EXTENSION)) . '" ';
            echo 'size="' . number_format(filesize($upload_dir . $file) / 1000, 0) . 'Kb" ';
            echo isset($mime['3']) ? $mime['3'] : "(no size info)";
            echo '</small>';
            echo '<br><img src="' . $upload_dir . $file . '" alt="viewport-image">';
            echo '<div style="position: absolute; top: 200px;">';
            $href_file = urlencode($file);
            echo '<a href="./index.php?page=files&file2download=' . $href_file . '" style="background: rgba(10, 10, 10, 0.25); border-radius: 0 10px 3px 0;color: white; text-decoration: none;"><small>Download&nbsp;</small></a>';
            echo " ";
            echo '<a href="./index.php?page=files&file2kill=' . $href_file . '" style="background: rgba(10, 10, 10, 0.25); border-radius: 0 10px 3px 0;color: white; text-decoration: none;"><small>Delete&nbsp;</small></a>';
            echo '</div>';
         } else {
            echo '<h4>no such file.</h4>';
         }
      } elseif (isset($filenm) || isset($_FILES['file2upload'])) {
         if (!empty($errors)) {
            echo 'File upload error: <small><i>' . $_FILES['file2upload']['name'] . '</i></small><br>';
            foreach ($errors as $error) {
               echo '<p style="color: red;">&nbsp;• ', $error, '</p>';
            }
         } else {
            echo '<i>Uploaded: </i><b>', $filenm, '</b>';
            echo '<br><a href=index.php?page=' . $page . '&file=' . str_replace(" ", "_", $filenm) . $ext . '><img src="' . $file . '" width="auto" height="100" alt="thumb-image" ></a>';
            echo '<i><small>Click image for full view and details</small></i>';
         }
      } else {
         echo implode("<br>", $messages);
      }
      ?>
   </div>
</div>