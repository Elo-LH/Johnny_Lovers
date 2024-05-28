<?php
session_start();
require "../connexion.php";

// Check if data received from from
if (!array_filter($_POST)) {
    echo "no data from from";
} else {
    print_r($_POST);
    // Format post data from register form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Fetching if this email is in th db
    $query = $db->prepare('SELECT * FROM users WHERE email = :email');
    $parameters = [
        'email' => $email,
    ];
    $query->execute($parameters);
    $user = $query->fetch(PDO::FETCH_ASSOC);


    if (!$user) {
        echo "user not found";
    } else {
        //Si l'email est enregistré dans la DB, vérifier que le mot de passe correspond
        var_dump($user);
        $isPasswordCorrect = password_verify($password, $user['password']);
        if ($isPasswordCorrect) {
            //Si le mot de passe est bon, enregistrer l'user connecté en session
            echo "user found";
            $_SESSION['user'] = [
                'pseudo' => $user['pseudo'],
                'avatar' => $user['avatar'],
                'email' => $user['email'],
                'favoriteSong' => $user['favoriteSong'],
                'role' => $user['role']
            ];
        } else {
            echo "wrong pasword";
        };
    }
};
header('Location: ../index.php?route=song');
