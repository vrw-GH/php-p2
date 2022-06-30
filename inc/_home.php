<?php
      echo '<meta http-equiv="refresh" content="30">';
      $time = date('g:i A (e)');
      // $time = exec('time /T');
      echo '<p>';
         echo "<strong>This is the Home Page</strong><br>";
         echo "The time is : $time";
      echo '</p>';
   ?>