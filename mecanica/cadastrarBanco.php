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
    $Quantidade = $conexao -> real_escape_string ($_POST['Quantidade']);
    $Preço = $conexao -> real_escape_string ($_POST['Preço']);

    $sql = "INSERT INTO `mecanica`.`peça`
    (`Nome`, `Quantidade`, `Preço`)
    VALUES
    ('".$Nome."','".$Quantidade."','".$Preço."')";

    $resultado = $conexao ->query($sql);

    $conexao -> close();

    header ('location: index.php', true, 301);
}
?>
</body>