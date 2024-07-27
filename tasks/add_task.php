<?php
include dirname(__DIR__) . '/includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_name = trim($_POST['task_name']);
    $task_description = trim($_POST['task_description']);
    $user_id = $_SESSION['user_id'];

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

        $sql = "INSERT INTO tasks (name, description, user_id) VALUES ('$task_name', '$task_description', '$user_id')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Tâche ajoutée avec succès!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur: " . $sql . "<br>" . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
        }
    }
    header("Location: add_task.php");
    exit();
}
?>

<h2>Ajouter une Tâche</h2>
<form action="add_task.php" method="POST" onsubmit="return validateForm()">
    <div class="form-group">
        <label for="task_name">Nom de la tâche:</label>
        <input type="text" id="task_name" name="task_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="task_description">Description de la tâche:</label>
        <textarea id="task_description" name="task_description" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
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
