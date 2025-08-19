<html>
<head>
    <link rel="stylesheet" href="style.css">
 </head>
<body>

<h2>Bem vindo a mecânica jailson mendes, clique no botão para cadastrar ou no outro botão para buscar a peça</h2> <br>
<div class="logo">
<img src="mecanica_jailson.png" alt="Logo da Empresa" width="300" height="300">
</div>
<form method="post" action="cadastrar.php">
        <input type="submit" value="Cadastrar" />
    </form>
<div>
    <form method="post" action="buscar.php">
        <label>Buscar por nome da peça:</label>
    <input type="text" name="Nome" size="20"><br />
    <br>
        <input type="submit" value="Buscar" />
    </form>
</body>
</html>