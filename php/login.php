<?php 
    session_start();
    include_once "config.php";
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!empty($user) && !empty($password)){
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE user = '{$user}'");
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            $user_pass = md5($password);
            $enc_pass = $row['password'];
            if($user_pass === $enc_pass){
                $status = "En línea";
                $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
                if($sql2){
                    $_SESSION['unique_id'] = $row['unique_id'];
                    echo "success";
                }else{
                    echo "Algo salió mal, intenta de nuevo.";
                }
            }else{
                echo "Usuario o contraseña incorrectos";
            }
        }else{
            echo "$user - Este usuario no existe.";
        }
    }else{
        echo "Todos los datos son requeridos.";
    }
?>