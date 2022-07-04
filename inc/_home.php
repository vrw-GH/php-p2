<!-- ----------------- HOME ------------------- -->

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