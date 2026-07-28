<?php

$servername = "localhost"; // 127.0.0.1
$database = "crud_mundo";
$username = "root";
$password = "";
// Criar a conexão
$conexao = mysqli_connect($servername, $username, $password, $database);
// Verificar a conexão
if (!$conexao) {

    die("Falha na Conexão: " . mysqli_connect_error());

}
echo "Conexão realizada com sucesso!!!"; // Mensagem de confirmação [OPCIONAL]
mysqli_select_db($conexao, $database);

?>