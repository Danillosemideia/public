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

            echo "<h3>Você tem certeza que deseja excluir este cliente?</h3>";
            echo "<br><a href='excluirCliente.php?cliente=".$id_cliente."'>Sim</a>";
            echo "<br><a href='clientes.php'>Não</a>";
        ?>
        
    </body>
</html>