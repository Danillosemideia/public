<html>
    <head>
        <title>Cadastrar Cartão</title>
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
			
			// Buscar clientes do gerente para o select
			$hostname = "127.0.0.1";
			$user = "root";
			$password = "";
			$database = "banco_database";
		
			$conexao = new mysqli($hostname,$user,$password,$database);
			
			$sql = "SELECT `id_cliente`, `nome` FROM `banco_database`.`cliente`
					WHERE `id_gerente` = '".$_SESSION['id_gerente']."';";
			$resultado = $conexao->query($sql);
			
			// Verificar se veio de um cliente específico
			$cliente_selecionado = isset($_GET['cliente']) ? $_GET['cliente'] : '';
		?>
		
		<form method="post" action="cadastrarCartaoBanco.php" id="formcartao" name="formcartao">
			<label>Número do Cartão: </label>
			<input type="text" name="numero_cartao" id="numero_cartao" size="20" maxlength="16" required><br /><br />
			
			<label>Limite: </label>
			<input type="number" name="limite" id="limite" step="0.01" min="0" required><br /><br />
			
			<label>Cliente: </label>
			<select name="id_cliente" id="id_cliente" required>
				<option value="">Selecione um cliente...</option>
				<?php
					if($resultado->num_rows > 0) {
						while($row = $resultado->fetch_assoc()) {
							$selected = ($row['id_cliente'] == $cliente_selecionado) ? 'selected' : '';
							echo "<option value='".$row['id_cliente']."' ".$selected.">".$row['nome']."</option>";
						}
					}
				?>
			</select><br />
			
			<center>
				<input type="submit" value="CADASTRAR CARTÃO" />
				<br>
				<a href="clientes.php">voltar</a>
			</center>
		</form>
		
		<?php
			$conexao -> close();
		?>
	</body>
</html>