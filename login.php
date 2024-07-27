<?php
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validation côté serveur
    if (empty($username) || empty($password)) {
        echo "<p style='color:red;'>Tous les champs sont obligatoires.</p>";
    } else {
        $username = mysqli_real_escape_string($conn, $username);

        // Utiliser BINARY pour comparaison sensible à la casse
        $sql = "SELECT * FROM users WHERE BINARY username = '$username'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color:red;'>Nom d'utilisateur ou mot de passe incorrect.</p>";
        }
    }
}
?>

<h2>Connexion</h2>
<form action="login.php" method="POST" onsubmit="return validateForm()">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Se connecter</button>
</form>

<script>
function validateForm() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    if (username === '' || password === '') {
        alert('Tous les champs sont obligatoires.');
        return false;
    }
    return true;
}
</script>

<?php include 'includes/footer.php'; ?>
