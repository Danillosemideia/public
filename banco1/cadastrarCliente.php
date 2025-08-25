<html>
    <head>
        <title>Cadastrar Cliente</title>
		<link rel="stylesheet" href="style.css">
	</head>
	
    <body>
		<?php
			session_start();
			if (empty($_SESSION['id_gerente'])){
				header('Location: sair.php');
				exit();
			}
		?>
		
		<h2>Cadastrar Novo Cliente</h2>
		<form method="post" action="cadastrarClienteBanco.php" id="formcliente" name="formcliente">
			<label>Nome: </label>
			<input type="text" name="nome" id="nome" size="30" required><br /><br />
			
			<label>Cidade: </label>
			<input type="text" name="cidade" id="cidade" size="30" required><br /><br />
			
			<label>Endereço: </label>
			<input type="text" name="endereco" id="endereco" size="40" required><br /><br />
			
			<label>Estado: </label>
			<input type="text" name="estado" id="estado" size="20" maxlength="2" required><br /><br />
			
			<label>CEP: </label>
			<input type="text" name="cep" id="cep" size="10" maxlength="9" required><br /><br />
			
			<label>Número da Conta: </label>
			<input type="text" name="numero_conta" id="numero_conta" size="20" required><br /><br />
			
			<center>
				<input type="submit" value="CADASTRAR CLIENTE" />
				<br><br>
				<a href="clientes.php">voltar</a>
			</center>
		</form>
	</body>
</html>