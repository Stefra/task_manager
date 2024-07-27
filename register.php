<?php
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validation côté serveur
    $errors = [];

    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est obligatoire.";
    }

    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire.";
    }

    if (!empty($username) && strlen($username) < 5) {
        $errors[] = "Le nom d'utilisateur doit comporter au moins 5 caractères.";
    }

    if (!empty($password)) {
        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit comporter au moins 8 caractères.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Le mot de passe doit comporter au moins une lettre majuscule.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Le mot de passe doit comporter au moins une lettre minuscule.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Le mot de passe doit comporter au moins un chiffre.";
        }
    }

    if (empty($errors)) {
        $username = mysqli_real_escape_string($conn, $username);
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Vérifier si le nom d'utilisateur existe déjà
        $check_sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Le nom d'utilisateur est déjà pris. Veuillez en choisir un autre.";
        } else {
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

            if (mysqli_query($conn, $sql)) {
                echo "<p style='color:green;'>Inscription réussie! Vous serez redirigé vers la page de connexion dans 3 secondes.</p>";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000);
                      </script>";
            } else {
                $errors[] = "Erreur: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>

<h2>Inscription</h2>
<form action="register.php" method="POST" onsubmit="return validateForm()">
    <div class="form-group">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">S'inscrire</button>
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

