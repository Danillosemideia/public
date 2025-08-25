<html>
    <body>
		<link rel="stylesheet" href="style.css">
		<?php
			// iniciar uma sessão
			session_start();
			
			$hostname = "127.0.0.1";
			$user = "root";
			$password = "";
			$database = "banco_database";
		
			$conexao = new mysqli($hostname,$user,$password,$database);

			if ($conexao -> connect_errno) {
				echo "Failed to connect to MySQL: " . $conexao -> connect_error;
				exit();
			} else {
				$nome = $conexao -> real_escape_string($_POST['nome']);
				$senha = $conexao -> real_escape_string($_POST['senha']);

				$sql="SELECT `id_gerente`, `nome` FROM `gerente`
					WHERE `nome` = '".$nome."'
					AND `senha` = '".$senha."';";

				$resultado = $conexao->query($sql);
				
				if($resultado->num_rows != 0)
				{
					$row = $resultado -> fetch_array();
					$_SESSION['id_gerente'] = $row[0];  // id do gerente
					$_SESSION['nome'] = $row[1];        // nome do gerente
					$conexao -> close();
					
					header('Location: clientes.php', true, 301);
					exit();
				} else {
					
					$conexao -> close();
					header('Location: index.php', true, 301);
				}
			}
		?>
	</body>
</html>