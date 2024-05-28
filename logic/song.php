<?php
session_start();
require "../connexion.php";

$email = $_SESSION['user']['email'];


// if toggleSong route, add or supress favorite song =
if (isset($_GET['toggleSong'])) {
    $isFavorite = $_GET['toggleSong'];
    $songId = intval($_GET['song_id']);

    //If song is allready fav, remove it
    if ($isFavorite === "true") {

        $query = $db->prepare('UPDATE users SET favoriteSong= null WHERE email = :email');
        $parameters = [
            'email' => $email,
        ];
        $query->execute($parameters);
        $isFavSongRemoved = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user']['favoriteSong'] = null;

        //If song is not in fav, add it
    } else {
        $query = $db->prepare('UPDATE users SET favoriteSong= :songId WHERE email = :email');
        $parameters = [
            'email' => $email,
            'songId' => $songId
        ];
        $query->execute($parameters);
        $isFavSongAdded = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user']['favoriteSong'] = $songId;
    }
};

header('Location: ../index.php?route=song');
