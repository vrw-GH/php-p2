<?php
      echo '<meta http-equiv="refresh" content="30">';
      $time = date('g:i A (e)');
      // $time = exec('time /T');
      echo '<p>';
         echo "<strong>This is the Home Page</strong><br>";
         echo "The time is : $time";
      echo '</p>';
   ?>



<!-- ----------------- DESCRIPTION ------------------- -->
<div>
   <div class="div_details">
      Details of this project: <i>hover to expand...</i>
   </div>
   <?php
            $descfile='./README.md';  //         $descfile='./details.txt';
            if (file_exists($descfile)) {
               $file_contents = file_get_contents($descfile);
               $contents_clean = htmlentities($file_contents, ENT_QUOTES | ENT_IGNORE, "UTF-8");
            } else {
               $contents_clean = " (No project README found) ";
            }
         ?>
   <div class="div_descr">
      <pre>
         <?php echo $contents_clean ?>
         </pre>
   </div>
</div>