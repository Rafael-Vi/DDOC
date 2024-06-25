<?php
require 'includes/header.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = (string) $_POST['user'];
    $pass = (string) $_POST['pass'];

    $db_conn = db_connect();
    $result = executeQuery($db_conn, "SELECT * FROM users WHERE user_name = ?", [$user]);

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        if (password_verify($pass, $user_data['user_password'])) {
            if($user_data['is_admin'] == 1) {
                $_SESSION['admin_id'] = $user_data['id_users'];
                $_SESSION['admin_nome'] = $user_data['user_name'];
                header('Location: index.php');
            } else {
                echo '<div class="toast">';
                echo '
                    <div class="alert alert-error">
                        <span>Não tem autorização para entrar</span>
                    </div>
                ';
                echo '</div>';
            }
        } else {
            echo '<div class="toast">';
            echo '
                <div class="alert alert-error">
                    <span>Credenciais Inválidas</span>
                </div>
            ';
            echo '</div>';
        }
    } else {
        echo '<div class="toast">';
        echo '
            <div class="alert alert-error">
                <span>Credenciais Inválidas</span>
            </div>
        ';
        echo '</div>';
    }

    mysqli_close($db_conn);
}
?>
<div class="w-screen h-[90vh] p-2 flex items-center justify-center">
    <div class="card card-compact w-96 bg-gray-900 shadow-xl">
        <figure><img src="./assets/logo.png" class="w-full bg-gray-200" alt="GENTL LOGO"/></figure>
        <div class="card-body">
            <h2 class="card-title">Login</h2>
            <form action="" method="post">
                <div class="overflow-auto">
                    <div class="form-control w-full max-w-xs">
                        <label for="user" class="label">Username</label>
                        <input type="text" id="user" name="user" class="input input-bordered" placeholder="Escreve o teu username" required>
                    </div>
                    <div class="form-control w-full max-w-xs">
                        <label for="pass" class="label">Password</label>
                        <input type="password" id="pass" name="pass" class="input input-bordered" placeholder="Escreve a tua password" required>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="card-actions justify-center">
                    <button type="submit" class="btn bg-orange-700 hover:bg-orange-800 w-full text-white">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    require 'includes/footer.inc.php';
