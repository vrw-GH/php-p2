<?php
header("Content-type: text/css");
?>

body {
font-family: "Arial";
background-color: lightgrey;
}

.div_title {
color: black;
background-color: lightgreen;
}

.div_menu {
display: inlineflex;
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
width: 100%;
align-items: center;
justify-content: center;
<!-- overflow: scroll; -->
}

.div_workarea {
padding: 10px;
}
.div_workarea > iframe {
background-color: silver;
width: 100%;
height: 40vh;
}
.div_workarea > iframe:hover {
height: 70vh;
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
padding: 0px 10px 20px 10px;
}
.div_descr:hover {
height:50vh;
overflow: scroll;
}

.div_upload {
<!-- display: flex; -->
width: 95%;
}
.div_upload > div_form {
display: flex;
}

.div_thumbview > img {
display: flex;
width: 80px;
max-width: 80px;
max-height: 60px;
}

.div_filelist {
height: 100px;
overflow: auto;
}
.div_filelist:hover {
<!-- height: 150px; -->
overflow: auto;
}

.div_fileview {
background-color: silver;
margin: 2px;
padding: 10px;
max-height: 300px;
overflow: auto;
}
.div_fileview > img {
display: flexbox;
max-width: 100%;
}

.div_footer {
display: flex;
margin: 20px 0 20px;
}