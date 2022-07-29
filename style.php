<?php
header("Content-type: text/css");
// $randomcolor=dechex(rand(0, 10000000)); //* also produces dark colors :(
$randomcolor = sprintf('#%06X', mt_rand(intval(0xFFFFFF / 1.006), 0xFFFFFF)); //* good for a lighter color-set
?>

body {
font-family: "Arial";
background-color: lightgrey;
}

.div_title {
color: black;
border-radius: 10px;
padding: 0 10px 0 10px;
background-color: lightgreen;
}

.div_menu {
display: inlineflex;
border-radius: 10px;
align-items: center;
justify-content: left;
color: yellow;
background-color: grey;
padding: 10px;
}
.div_menu > a {
display: inlineflex;
color: white;
align-items: center;
text-align: center;
}

.div_contents {
display: inlineflex;
background-color: <?= $randomcolor; ?>;
border-radius: 10px;
padding: 10px;
max-height: 80%;
align-items: center;
justify-content: center;
}
.div_contents > iframe {
background-color: silver;
width: 100%;
height: 40vh;
min-height: 40vh;
}
.div_contents > iframe:hover {
height: calc(80vh - 150px);
overflow: scroll;
}

.div_details {
color: blue;
margin: 20px 0px 6px;
}
.div_details > i {
color: black;
font-size: 0.8rem;
padding-left: 2rem;
}

.div_descr {
display: inlineflex;
overflow-y: hidden;
background-color: white;
height: 20vh;
min-height: 20vh;
padding: 0px 10px 20px 10px;
}
.div_descr:hover {
height: calc(60vh - 150px);
overflow: scroll;
}

.div_workarea {
display: flex;
}

.div_upload {
display: flexbox;
width: 30%;
max-width: 200px;
}
.div_upload > div_form {
display: flexbox;
overflow: auto;
}

.div_viewport {
display: flexbox;
width: 100%;
max-height: 70vh;
background-color: silver;
margin: 2px;
padding: 10px;
overflow: auto;
}
.div_viewport > a, img {
position: relative;
display: flex;
max-width: 100%;
<!-- max-height: 80vh; -->
}

.div_fileslist {
display: flexbox;
font-size: 0.8rem;
max-height: 70vh;
overflow: auto;
}
.div_fileslist > ol {
padding-left: 1rem;
}
.div_fileslist > ol li {
font-size: 0.8rem;
margin-bottom: 0.2rem;
margin: 5px;
}
.div_fileslist > ol li::marker {
font-size: 0.6rem;
}

.div_footer {
display: flex;
margin: 20px 0 20px;
}