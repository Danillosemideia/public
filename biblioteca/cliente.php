
<html> 
<head>
    <title>Biblioteca SAEP</title>
</head>
<body>
    <h1> Busca de clientes</h1>
    <form action="pesquisarCliente.php" method="POST">
        <input type="text" name="livro" id="livro" placeholder="Digite o nome ou ID do cliente" required>
        <br>
        <p>Pesquisar por:</p>
        <input type="radio" name="escolhaid" value="1" id="porID" required>
        <label for="porID">ID</label>
        <input type="radio" name="escolhaid" value="0" id="porNome" required>
        <label for="porNome">Nome</label>
        <br><br>
        <input type="submit" value="Pesquisar">
    </form>
    <a href="index.php">Busca por livros.</a>
    </body>
</html>
