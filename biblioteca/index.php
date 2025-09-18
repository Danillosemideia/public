
<html> 
<head>
    <title>Biblioteca SAEP</title>
</head>
<body>
    <h1> Biblioteca SAEP </h1>
    <form action="pesquisarLivro.php" method="POST">
        <input type="text" name="livro" id="livro" placeholder="Digite o nome ou ID do livro" required>
        <br>
        <p>Pesquisar por:</p>
        <input type="radio" name="escolhaid" value="1" id="porID" required>
        <label for="porID">ID</label>
        <input type="radio" name="escolhaid" value="0" id="porNome" required>
        <label for="porNome">Livro</label>
        <br><br>
        <input type="submit" value="Pesquisar">
        </form>
        <a href="cliente.php">buscar clientes</a>
</body>
</html>
