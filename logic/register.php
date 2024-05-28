<?php
session_start();
require "../connexion.php";


// Check if data received from from
if (!array_filter($_POST)) {
    header('Location: ../index.php?route=inscription');
} else {
    // Format post data from form
    $pseudo = $_POST['pseudo'];
    $avatar = $_POST['avatar'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Fetch if this email is allready in DB
    $query = $db->prepare('SELECT * FROM users WHERE email = :email');
    $parameters = [
        'email' => $email,
    ];
    $query->execute($parameters);
    $isEmailFound = $query->fetch(PDO::FETCH_ASSOC);

    if ($isEmailFound) {
        //If found, redirect to register with error
        header('Location: ../index.php?route=inscription&error=' . $email);
    } else {
        // If not found, create an new user in DB
        $query = $db->prepare('INSERT INTO users(pseudo, email, password, avatar) VALUES(:pseudo, :email, :password, :avatar)');
        $parameters = [
            'email' => $email,
            'password' => $hash,
            'pseudo' => $pseudo,
            'avatar' => $avatar
        ];
        $query->execute($parameters);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        header('Location: ../index.php?route=home');
    };
};
