   <head>
    <link rel="stylesheet" href="style.css">
 </head>
   <body>
<?php
$hostname = "127.0.0.1";
$user = "root";
$password= "";
$database = "mecanica";

$conexao = new mysqli($hostname,$user,$password,$database);

if ($conexao -> connect_errno)
{
    echo "failed to connect to mySQL: " . $conexao ->  connect_error;
    exit();
} else{
    $Nome = $conexao -> real_escape_string ($_POST['Nome']);
    $sql = "SELECT * FROM `peça` 
    WHERE `Nome` = '".$Nome."';";
    $resultado = $conexao ->query($sql);
}
    if($resultado->num_rows != 0){
        $row = $resultado -> fetch_array();
        $conexao -> close();
        echo "Essa é a peça que você queria?" ."<br>";
        echo "O nome da peça é: $row[1]" ."<br>";
        echo "Sua quantidade em estoque é: $row[2]" ."<br>";
        echo "Seu preço é: $row[3]" ."<br>";
    } else {
        $conexao -> close();
        echo "Nada encontrado ou erro na consulta";
    }
    ?>
    <center>
    <a href="index.php">voltar</a>
</center>
    </body>