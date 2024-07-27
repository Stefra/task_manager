<?php
include 'config/database.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les tâches de l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tasks WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
?>

<h2>Liste des Tâches</h2>
<table>
    <thead>
        <tr>
            <th>Nom de la tâche</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($task = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($task['name']); ?></td>
            <td><?php echo htmlspecialchars($task['description']); ?></td>
            <td>
                <a href="tasks/edit_task.php?id=<?php echo $task['id']; ?>">Modifier</a>
                <a href="tasks/delete_task.php?id=<?php echo $task['id']; ?>">Supprimer</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
include 'includes/footer.php';
?>
