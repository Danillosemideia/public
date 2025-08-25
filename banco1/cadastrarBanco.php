<html>	
    <body>
		<head>
    <link rel="stylesheet" href="style.css">
</head>
		<?php
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
				$email = $conexao -> real_escape_string($_POST['email']);
				$senha = $conexao -> real_escape_string($_POST['senha']);

				$sql = "INSERT INTO `banco_database`.`gerente`
							(`nome`, `email`, `senha`)
						VALUES
							('".$nome."', '".$email."', '".$senha."');";

				$resultado = $conexao->query($sql);
				
				if($resultado) {
					echo "Gerente cadastrado com sucesso!";
				} else {
					echo "Erro ao cadastrar gerente: " . $conexao->error;
				}
				
				$conexao -> close();
				header('Location: index.php', true, 301);
			}
		?>
	</body>
</html>