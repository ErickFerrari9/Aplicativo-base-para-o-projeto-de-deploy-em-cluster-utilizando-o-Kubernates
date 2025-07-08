<?
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

$id =  rand(1, 999); // Gera um ID aleatório (não ideal para produção, pois pode gerar colisões)
$nome = $_POST["nome"]; // Pega o nome do POST
$email = $_POST["email"]; // Pega o email do POST
$comentario = $_POST["comentario"]; // Pega o comentário do POST

$query = "INSERT INTO mensagens(id, nome, email, comentario) VALUES ('$id', '$nome', '$email', '$comentario')"; // Query SQL para inserir os dados

// Executa a query e verifica se foi bem-sucedida
if ($link->query($query) === TRUE) {
  echo "New record created successfully"; // Mensagem de sucesso
} else {
  echo "Error: " . $link->error; // Mensagem de erro com detalhes do erro
}
?>