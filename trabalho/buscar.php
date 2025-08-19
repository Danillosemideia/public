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
    $sql = "SELECT * FROM `usuario` 
    WHERE `Nome` = '".$Nome."';";
    $resultado = $conexao ->query($sql);
}
    if($resultado->num_rows != 0){
        $row = $resultado -> fetch_array();
        $conexao -> close();
        echo "O nome do jogador é: $row[1]" ."<br>";
        echo "Sua posição é: $row[2]" ."<br>";
        echo "Sua camisa é: $row[3]" ."<br>";
        echo "Seu salário é: $row[4]"."<br>";
    } else {
        $conexao -> close();
        echo "Nada encontrado ou erro na consulta";
    }
    ?>
    <center>
    <a href="index.php">voltar</a>
</center>
    </body>