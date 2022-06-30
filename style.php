<?php
header("Content-type: text/css");
?>

body {
font-family: helvetica;
background-color: lightgrey;
}

.div_title {
color: black;
}

.div_details {
color: blue;
margin: 20px 0px 6px;
}

.div_details i {
color: black;
font-size: 0.8rem;
padding-left: 2rem;
}

.div_descr {
display: inlineflex;
overflow-y: hidden;
background-color: white;
height: 50px;
padding: 0px 10px 120px 10px;
margin: 5px;
}

.div_descr:hover {
height:20vh;
overflow: scroll;
}

.div_menu {
display: flex;
align-items: center;
justify-content: left;
color: yellow;
background-color: grey;
padding: 15px;
}

.div_menu a {
color: lightyellow;
}

.div_contents {
display: inlineflex;
width: 98%;
padding: 1%;
align-items: center;
justify-content: center;
overflow: scroll;
}

.div_contents, iframe {
background-color: silver;
width: 98%;
}

.div_footer {
display: flex;
margin: 20px 0 20px;
}