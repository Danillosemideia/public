<html>
    <head>
        <title>Painel Banco</title>
<link rel="stylesheet" href="style.css">
    </head>

    <body>

		<div class="divLogin">
			<form method="post" action="verificaLogin.php" id="formlogin" name="formlogin" >
				<label>Nome: </label>
				<input type="text" name="nome" id="nome" size="20"><br />
				
				<label>Senha: </label>
				<input type="password" name="senha" id="senha" size="20">
				<br>
				<center>
					<input type="submit" value="LOGAR"  />
				</center>
			</form>
		</div>

	<center>
		<a href="cadastrarGerente.php">Cadastrar</a>
	</center>

    </body>
</html>
