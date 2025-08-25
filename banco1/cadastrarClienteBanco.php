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
				$nome = $conexao -> real_escape_string($_POST['nome']);
				$cidade = $conexao -> real_escape_string($_POST['cidade']);
				$endereco = $conexao -> real_escape_string($_POST['endereco']);
				$estado = $conexao -> real_escape_string($_POST['estado']);
				$cep = $conexao -> real_escape_string($_POST['cep']);
				$numero_conta = $conexao -> real_escape_string($_POST['numero_conta']);
				$id_gerente = $_SESSION['id_gerente'];

				$sql = "INSERT INTO `banco_database`.`cliente`
							(`nome`, `cidade`, `endereco`, `estado`, `cep`, `numero_conta`, `id_gerente`)
						VALUES
							('".$nome."', '".$cidade."', '".$endereco."', '".$estado."', '".$cep."', '".$numero_conta."', '".$id_gerente."');";

				$resultado = $conexao->query($sql);
				
				if($resultado) {
					echo "Cliente cadastrado com sucesso!";
				} else {
					echo "Erro ao cadastrar cliente: " . $conexao->error;
				}
				
				$conexao -> close();
				header('Location: clientes.php', true, 301);
			}
		?>
	</body>
</html>