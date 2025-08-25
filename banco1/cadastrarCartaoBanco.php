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
			
			$hostname = "127.0.0.1";
			$user = "root";
			$password = "";
			$database = "banco_database";
		
			$conexao = new mysqli($hostname,$user,$password,$database);

			if ($conexao -> connect_errno) {
				echo "Failed to connect to MySQL: " . $conexao -> connect_error;
				exit();
			} else {
				$numero_cartao = $conexao -> real_escape_string($_POST['numero_cartao']);
				$limite = $conexao -> real_escape_string($_POST['limite']);
				$id_cliente = $conexao -> real_escape_string($_POST['id_cliente']);

				$sql = "INSERT INTO `banco_database`.`cartao_credito`
							(`numero_cartao`, `limite`, `id_cliente`)
						VALUES
							('".$numero_cartao."', '".$limite."', '".$id_cliente."');";

				$resultado = $conexao->query($sql);
				
				if($resultado) {
					echo "Cartão cadastrado com sucesso!";
				} else {
					echo "Erro ao cadastrar cartão: " . $conexao->error;
				}
				
				$conexao -> close();
				header('Location: clientes.php', true, 301);
			}
		?>
	</body>
</html>