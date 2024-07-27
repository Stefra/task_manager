<?php
include dirname(__DIR__) . '/includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM tasks WHERE id = $task_id AND user_id = $user_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Tâche supprimée avec succès!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Erreur: " . $sql . "<br>" . mysqli_error($conn);
        $_SESSION['message_type'] = "error";
    }
} else {
    $_SESSION['message'] = "Erreur: ID de tâche non fourni.";
    $_SESSION['message_type'] = "error";
}

header("Location: list_tasks.php");
exit();
