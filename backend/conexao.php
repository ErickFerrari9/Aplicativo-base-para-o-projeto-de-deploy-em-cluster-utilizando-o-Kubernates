<?php
$servername = "mysql-service"; // O nome do serviço MySQL no Kubernetes
$username = "root";
$password = "Senha123"; // A senha definida no mysql-secret
$database = "meubanco";

// Criar conexão


$link = new mysqli($servername, $username, $password, $database);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>