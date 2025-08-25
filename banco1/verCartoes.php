<html>
    <head>
        <title>Cartões do Cliente</title>
        <link rel="stylesheet" href="style.css">
    </head>
    
    <body>
        <?php
            session_start();
            
            // Verificar se o gerente está logado
            if (empty($_SESSION['id_gerente'])){
                header('Location: sair.php');
                exit();
            }
            
            // Verificar se foi passado o ID do cliente
            if (!isset($_GET['cliente']) || empty($_GET['cliente'])) {
                header('Location: clientes.php');
                exit();
            }
            
            $id_cliente = $_GET['cliente'];
            
            $hostname = "127.0.0.1";
            $user = "root";
            $password = "";
            $database = "banco_database";
        
            $conexao = new mysqli($hostname,$user,$password,$database);
            
            if ($conexao->connect_errno) {
                echo "Failed to connect to MySQL: " . $conexao->connect_error;
                exit();
            }
            
            // Verificar se o cliente pertence ao gerente logado
            $sql_verifica = "SELECT `nome`, `numero_conta` FROM `banco_database`.`cliente`
                            WHERE `id_cliente` = '".$id_cliente."' AND `id_gerente` = '".$_SESSION['id_gerente']."';";
            
            $resultado_verifica = $conexao->query($sql_verifica);
            
            if ($resultado_verifica->num_rows == 0) {
                echo "Cliente não encontrado ou você não tem permissão para visualizar.";
                echo "<br><a href='clientes.php'>Voltar para clientes</a>";
                exit();
            }
            
            $cliente_info = $resultado_verifica->fetch_assoc();
            
            // Buscar cartões do cliente
            $sql_cartoes = "SELECT `id_cartao`, `numero_cartao`, `limite` FROM `banco_database`.`cartao_credito`
                           WHERE `id_cliente` = '".$id_cliente."';";
            
            $resultado_cartoes = $conexao->query($sql_cartoes);
        ?>
        
        <h2>Cartões do Cliente: <?php echo $cliente_info['nome']; ?></h2>
        <p><strong>Conta:</strong> <?php echo $cliente_info['numero_conta']; ?></p>
        
        <br>
        
        <?php
            if($resultado_cartoes->num_rows > 0) {
                echo "<h3>Lista de Cartões:</h3>";
                echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse;'>";
                echo "<tr style='background-color: #f0f0f0;'>";
                echo "<th>ID Cartão</th>";
                echo "<th>Número do Cartão</th>";
                echo "<th>Limite</th>";
                echo "</tr>";
                
                while($cartao = $resultado_cartoes->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$cartao['id_cartao']."</td>";
                    echo "<td>****-****-****-".substr($cartao['numero_cartao'], -4)."</td>";
                    echo "<td>R$ ".number_format($cartao['limite'], 2, ',', '.')."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: #666;'>Este cliente ainda não possui cartões cadastrados.</p>";
            }
            
            $conexao->close();
        ?>
        
        <br><br>
        <center>
            <a href="clientes.php">Voltar</a>
        </center>
        
    </body>
</html>