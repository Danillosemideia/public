<body>
<?php 
$hostname = "127.0.0.1";
$user = "root";
$password= "";
$database = "rede";

$conexao = new mysqli($hostname,$user,$password,$database);

if ($conexao -> connect_errno)
{
    echo "failed to connect to mySQL: " . $conexao ->  connect_error;
    exit();
} else{

    $Nome = $conexao -> real_escape_string ($_POST['Nome']);
    $Posicao = $conexao -> real_escape_string ($_POST['Posicao']);
    $Camisa = $conexao -> real_escape_string ($_POST['Camisa']);
    $Salario = $conexao -> real_escape_string ($_POST['Salario']);

    $sql = "INSERT INTO `rede`.`usuario`
    (`Nome`, `Posicao`, `Camisa`, `Salario`)
    VALUES
    ('".$Nome."','".$Posicao."','".$Camisa."','".$Salario."')";

    $resultado = $conexao ->query($sql);

    $conexao -> close();

    header ('location: index.php', true, 301);
}
?>
</body>