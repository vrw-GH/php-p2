<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="./style.php" media="screen">
   <link rel="icon" href="../../resources/favicon-apache.png">
   <title>Projects: P1</title>
</head>

<?php 
   // $randomcolor=dechex(rand(0, 10000000)); // also produces dark colors :(
   $randomcolor = sprintf('#%06X', mt_rand(intval(0xFFFFFF / 1.005), 0xFFFFFF));
?>

<body>

   <!-- ----------------- TITLE ------------------- -->
   <div id="title" class="div_title" style="background-color: <?=$randomcolor; ?>; ">
      <h1>My first PHP project</h1>
   </div>

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

   <!-- ----------------- MENU ------------------- -->
   <div id="menu" class="div_menu">
      <?php
         $inc_dir = "inc";
         $pages = scandir($inc_dir,0);
         unset($pages[0],$pages[1]); // remove . and ..
         
         // get only php files (for menu) - removes sub folders
         $pages = array_filter($pages, static function ($element) {
            return str_ends_with($element,'.php');
         });
         
         // find _* as first menu item (if one is set)
         $home = array_filter($pages, static function ($element) {
            return str_starts_with($element,'_');
         });
         $home = implode($home);
         if (isset($home)) {
            $key = array_search($home, $pages);
            unset($pages[$key]); // remove the original entry
            array_unshift($pages,$home); // add same to beginning of array
         };
         // print_r($pages);
         
         // create the menu
         foreach ($pages as $pagekey => $page) {
            $menulink = substr_replace($page,'',-4); // gets the menu list
            $menuitem = (str_starts_with($menulink,"_")) ? substr($menulink,1) : $menulink; // remove the "_"
            $menuitem = strtoupper(substr($menuitem,0,1)).substr($menuitem,1); // capitalize fisrt char
            echo '<a href="index.php?page='.strtolower($menulink).'">'.$menuitem.'</a>';
            $seperator = ($pagekey==array_key_last($pages)) ? ' ':' &spades; '; // add seperator if not last element
            echo "&nbsp;$seperator&nbsp;";          
         };
      ?>
   </div>

   <hr>

   <!-- ----------------- CONTENTS ------------------- -->
   <div id="contents" class="div_contents">
      <?php
         if (!empty($_GET["page"])){
            $page = $_GET["page"];
            if (in_array($page.'.php',$pages)) {
               include($inc_dir.'/'.$page.'.php');
            } else {
               echo '<h3>Page does\'nt exist.</h3>';
            };
         } else {
            include($inc_dir.'/_home.php');
         };
      ?>
   </div>

   <hr>

   <!-- ----------------- FOOTER ------------------- -->
   <div id="footer" class="div_footer">
      <a href="..\">Go Back (Projects List)</a>
   </div>

</body>

</html>