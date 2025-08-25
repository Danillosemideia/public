<html>
    <body>
        <link rel="stylesheet" href="style.css">
        <?php
            session_start();
            
            // Verificar se o gerente está logado
            if (empty($_SESSION['id_gerente'])){
                header('Location: sair.php');
                exit();
            }

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
            

            $sql_excluir_cliente = "DELETE FROM `banco_database`.`cliente` WHERE `id_cliente` = '".$id_cliente."'";
                $resultado_cliente = $conexao->query($sql_excluir_cliente);

                 header('Location: clientes.php');
                exit();

            
                
        ?>
        
    </body>
</html>