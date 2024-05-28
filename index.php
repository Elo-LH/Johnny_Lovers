<?php
session_start();
require "connexion.php";


//Retrieve route from url
if (!array_filter($_GET)) {
    $route = null;
} else {
    if (isset($_GET['route'])) {
        $route = $_GET['route'];
    } else {
        $route = null;
    };
}

require "layout.phtml";
