<?php
session_start();
require "../connexion.php";

//check if user is identified and admin
if (array_filter($_SESSION) && $_SESSION['user']['role'] === 'admin') {

    // if delete route, delete the user with selected email 
    if (isset($_GET['delete'])) {
        $email = $_GET['delete'];
        $query = $db->prepare('DELETE FROM users WHERE email = :email');
        $parameters = [
            'email' => $email,
        ];
        $query->execute($parameters);
        $isUserDeleted = $query->fetch(PDO::FETCH_ASSOC);

        // if toggleAdmin route, add or supress admin rights depending on selected user role
    } else if (isset($_GET['toggleAdmin'])) {
        $isAdmin = $_GET['toggleAdmin'];
        $email = $_GET['email'];

        //If user is admin, suppress role
        if ($isAdmin === "true") {

            $query = $db->prepare('UPDATE users SET role= null WHERE email = :email');
            $parameters = [
                'email' => $email,
            ];
            $query->execute($parameters);
            $isAdminDeleted = $query->fetch(PDO::FETCH_ASSOC);

            //If user is not admin, add role
        } else {
            $query = $db->prepare('UPDATE users SET role= "admin" WHERE email = :email');
            $parameters = [
                'email' => $email,
            ];
            $query->execute($parameters);
            $isAdminAdded = $query->fetch(PDO::FETCH_ASSOC);
        }
    } else if (isset($_GET['modify'])) {
        $email = $_GET['modify'];
    }
}

if (isset($_GET['modify'])) {
    header('Location: ../index.php?route=modify&email=' . $email);
} else {
    header('Location: ../index.php?route=admin');
}
