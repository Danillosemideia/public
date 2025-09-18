<?php
session_start();

$hostname = "127.0.0.1";
$user = "root";
$password = "";
$database = "biblioteca_sistema";

$conexao = new mysqli($hostname, $user, $password, $database);
$conexao->set_charset("utf8");
if ($conexao->connect_errno) {
    echo "Falha na conexão: " . $conexao->connect_error;
    exit();
}

$escolhaid = isset($_POST['escolhaid']) ? $_POST['escolhaid'] : '';
$cliente_input = isset($_POST['livro']) ? trim($_POST['livro']) : '';

if ($escolhaid === '' || $cliente_input === '') {
    echo "Por favor, preencha todos os campos.";
    echo "<br><a href='buscarCliente.php'>Voltar para a busca</a>";
    exit();
}

if ($escolhaid == '1') {
    $cliente_id = intval($cliente_input);
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
} else {
    $cliente_nome = "%" . $cliente_input . "%";
    $sql = "SELECT * FROM clientes WHERE nome LIKE ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $cliente_nome);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Nenhum cliente encontrado para essa pesquisa.";
    echo "<br><a href='buscarCliente.php'>Voltar para a busca</a>";
    exit();
}

while ($cliente = $result->fetch_assoc()) {
    echo "<h2>Cliente: " . htmlspecialchars($cliente['nome']) . " (ID: " . $cliente['id'] . ")</h2>";
    echo "Endereço: " . htmlspecialchars($cliente['endereco']) . "<br>";
    echo "Cidade: " . htmlspecialchars($cliente['cidade']) . "<br>";
    echo "Estado: " . htmlspecialchars($cliente['estado']) . "<br>";
    echo "Telefone: " . htmlspecialchars($cliente['telefone']) . "<br>";
    echo "Status: " . $cliente['status'] . "<br><hr>";

    $sql_emprestimos = "
    SELECT l.id AS livro_id, l.nome AS livro_nome, e.data_emprestimo, e.data_devolucao, e.status
    FROM emprestimos e
    JOIN livros l ON e.livro_id = l.id
    WHERE e.cliente_id = ?
    ORDER BY e.data_emprestimo DESC";
    $stmt_emprestimos = $conexao->prepare($sql_emprestimos);
    $stmt_emprestimos->bind_param("i", $cliente['id']);
    $stmt_emprestimos->execute();
    $result_emprestimos = $stmt_emprestimos->get_result();

    if ($result_emprestimos->num_rows > 0) {
        echo "<h3>Livros já retirados:</h3><ul>";
        while ($emprestimo = $result_emprestimos->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($emprestimo['livro_nome']) . " (ID Livro: " . $emprestimo['livro_id'] . ") - Empréstimo: " . $emprestimo['data_emprestimo'] .
                 " - Devolução: " . ($emprestimo['data_devolucao'] ?? 'Não devolvido') .
                 " - Status: " . $emprestimo['status'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Este cliente ainda não retirou livros.</p>";
    }
    echo "<hr>";
    $stmt_emprestimos->close();
}

echo "<br><a href='cliente.php'>Voltar para a busca</a>";

$stmt->close();
$conexao->close();
?>
