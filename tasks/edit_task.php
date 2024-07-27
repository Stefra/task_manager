<?php
include dirname(__DIR__) . '/includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM tasks WHERE id = $task_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $task = mysqli_fetch_assoc($result);

    if (!$task) {
        $_SESSION['message'] = "Tâche non trouvée.";
        $_SESSION['message_type'] = "error";
        header("Location: list_tasks.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $task_name = trim($_POST['task_name']);
        $task_description = trim($_POST['task_description']);

        // Validation côté serveur
        if (empty($task_name) || empty($task_description)) {
            $_SESSION['message'] = "Tous les champs sont obligatoires.";
            $_SESSION['message_type'] = "error";
        } elseif (strlen($task_name) < 3) {
            $_SESSION['message'] = "Le nom de la tâche doit comporter au moins 3 caractères.";
            $_SESSION['message_type'] = "error";
        } else {
            $task_name = mysqli_real_escape_string($conn, $task_name);
            $task_description = mysqli_real_escape_string($conn, $task_description);

            $sql = "UPDATE tasks SET name = '$task_name', description = '$task_description' WHERE id = $task_id AND user_id = $user_id";

            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Tâche modifiée avec succès!";
                $_SESSION['message_type'] = "success";
                header("Location: list_tasks.php");
                exit();
            } else {
                $_SESSION['message'] = "Erreur: " . $sql . "<br>" . mysqli_error($conn);
                $_SESSION['message_type'] = "error";
            }
        }
    }
} else {
    $_SESSION['message'] = "Erreur: ID de tâche non fourni.";
    $_SESSION['message_type'] = "error";
    header("Location: list_tasks.php");
    exit();
}
?>

<h2>Modifier une Tâche</h2>
<form action="edit_task.php?id=<?php echo $task_id; ?>" method="POST" onsubmit="return validateForm()">
    <label for="task_name">Nom de la tâche:</label>
    <input type="text" id="task_name" name="task_name" value="<?php echo htmlspecialchars($task['name']); ?>" required>
    <br>
    <label for="task_description">Description de la tâche:</label>
    <textarea id="task_description" name="task_description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
    <br>
    <button type="submit">Modifier</button>
</form>

<script>
function validateForm() {
    var taskName = document.getElementById('task_name').value;
    var taskDescription = document.getElementById('task_description').value;

    if (taskName === '' || taskDescription === '') {
        alert('Tous les champs sont obligatoires.');
        return false;
    }
    if (taskName.length < 3) {
        alert('Le nom de la tâche doit comporter au moins 3 caractères.');
        return false;
    }
    return true;
}
</script>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
