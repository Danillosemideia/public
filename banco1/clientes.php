<html>
    <head>
        <title>Tela Principal - Clientes</title>
		<link rel="stylesheet" href="style.css">
    </head>
	
    <body>
		<?php
			session_start();

			if (empty($_SESSION['id_gerente'])){
				header('Location: sair.php');
				exit();
			} else {
				echo '<h3>Bem vindo, '.$_SESSION['nome'].'</h3><br><br>';
			}

			$hostname = "127.0.0.1";
			$user = "root";
			$password = "";
			$database = "banco_database";
		
			$conexao = new mysqli($hostname,$user,$password,$database);

			$sql="SELECT `id_cliente`, `nome`, `cidade`, `estado`, `numero_conta` FROM `banco_database`.`cliente`
					WHERE `id_gerente` = '".$_SESSION['id_gerente']."';";

			$resultado = $conexao->query($sql);
			
			// Exibir lista de clientes
			if($resultado->num_rows > 0) {
				echo "<h3>Seus Clientes:</h3>";
				echo "<table border='1' cellpadding='5' cellspacing='0'>";
				echo "<tr><th>ID</th><th>Nome</th><th>Cidade</th><th>Estado</th><th>Conta</th><th>Ações</th></tr>";
				
				while($row = $resultado->fetch_assoc()) {
					echo "<tr>";
					echo "<td>".$row['id_cliente']."</td>";
					echo "<td>".$row['nome']."</td>";
					echo "<td>".$row['cidade']."</td>";
					echo "<td>".$row['estado']."</td>";
					echo "<td>".$row['numero_conta']."</td>";
					echo "<td><a href='cadastrarCartao.php?cliente=".$row['id_cliente']."'>Adicionar Cartão</a></td>";
					echo "<td><a href='verCartoes.php?cliente=".$row['id_cliente']."'>Ver Cartoes do cliente</a></td>";
					echo "<td><a href='certeza.php?cliente=".$row['id_cliente']."'>Excluir Cliente</a></td>";
					echo "</tr>";
				}
				echo "</table><br>";
			} else {
				echo "<p>Nenhum cliente cadastrado ainda.</p>";
			}
			
			$conexao -> close();
		?>
		
		<center>
			<a href="cadastrarCliente.php">Cadastrar Novo Cliente</a> <br><br>
			<a href="sair.php" class='sair'>Sair</a>
		</center>
		
	</body>
</html>