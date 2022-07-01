<?php
   // $randomcolor=dechex(rand(0, 10000000)); // also produces dark colors :(
   $randomcolor = sprintf('#%06X', mt_rand(intval(0xFFFFFF / 1.005), 0xFFFFFF));
   $server = $_SERVER['HTTP_HOST'];
   $thisphp = $_SERVER['PHP_SELF'];
   $root = 'document_root/' . substr($thisphp,1,strrpos($thisphp,'/'));
   // $query = $_SERVER ['QUERY_STRING'];
   $time = date('g:i A (e)');
   // $time = exec('time /T');
?>

<!-- ----------------- WORK AREA ------------------- -->
<div>
   <div class="div_home" style="background-color: <?=$randomcolor; ?>; ">
      <meta http-equiv="refresh" content="30">
      <strong>This is the Home Page</strong>
      <br>
      The time is : <?=$time; ?>
      <p>
         Apache Host: &nbsp; <?=$server; ?>
         <br>
         Project Folder: <?=$root ?>
         <br>
      </p>

      <div class="div_details">
         Details of this project: <i>hover to expand...</i>
      </div>

      <div class="div_descr">
         <?php
            $descfile='./README.md';  //         $descfile='./details.txt';
            if (file_exists($descfile)) {
               $file_contents = file_get_contents($descfile);
               $contents_clean = htmlentities($file_contents, ENT_QUOTES | ENT_IGNORE, "UTF-8");
            } else {
               $contents_clean = " (No project README found) ";
            }
         ?>
         <pre>
            <?php echo $contents_clean ?>
         </pre>
      </div>
   </div>
</div>