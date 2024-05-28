<?php
session_start();
require "../connexion.php";


// Check if data received from from
if (!array_filter($_POST) || !array_filter($_GET)) {
    header('Location: ../index.php?route=admin');
} else {
    // Format post data from form
    $pseudo = $_POST['pseudo'];
    $avatar = $_POST['avatar'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    //keep old email to access row to modifiy in db
    $emailREF = $_GET['email'];

    // Fetch if this email is allready in DB
    $query = $db->prepare('SELECT * FROM users WHERE email = :email');
    $parameters = [
        'email' => $emailREF,
    ];
    $query->execute($parameters);
    $isEmailFound = $query->fetch(PDO::FETCH_ASSOC);

    if ($isEmailFound) {
        // If found, modify user in DB
        $query = $db->prepare('UPDATE users SET email= :email, pseudo = :pseudo, avatar = :avatar, role = :role WHERE email = :emailREF');
        $parameters = [
            'email' => $email,
            'emailREF' => $emailREF,
            'pseudo' => $pseudo,
            'avatar' => $avatar,
            'role' => $role
        ];
        $query->execute($parameters);
        $isUserModified = $query->fetch(PDO::FETCH_ASSOC);

        header('Location: ../index.php?route=admin');
    } else {

        //If not found, redirect to admin with error
        header('Location: ../index.php?route=admin&error=' . $emailREF);
    };
};
