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
$livro_input = isset($_POST['livro']) ? trim($_POST['livro']) : '';

if ($escolhaid === '' || $livro_input === '') {
    echo "Por favor, preencha todos os campos.";
    echo "<br><a href='index.php'>Voltar para o menu de busca</a>";
    exit();
}

if ($escolhaid == '1') {
    $livro_id = intval($livro_input);
    $sql = "
    SELECT l.*, f.nome AS funcionario_nome, f.id AS funcionario_id
    FROM livros l
    JOIN funcionarios f ON l.funcionario_cadastro_id = f.id
    WHERE l.id = ?
    ";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $livro_id);
} else {
    $livro_nome = "%" . $livro_input . "%";
    $sql = "
    SELECT l.*, f.nome AS funcionario_nome, f.id AS funcionario_id
    FROM livros l
    JOIN funcionarios f ON l.funcionario_cadastro_id = f.id
    WHERE l.nome LIKE ?
    ";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('s', $livro_nome);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Nenhum livro encontrado para essa pesquisa.";
    echo "<br><a href='index.php'>Voltar para o menu de busca</a>";
    exit();
}

while ($livro = $result->fetch_assoc()) {
    echo "<h2>Livro: " . htmlspecialchars($livro['nome']) . " (ID: " . $livro['id'] . ")</h2>";
    echo "Autor: " . htmlspecialchars($livro['autor']) . "<br>";
    echo "Ano: " . $livro['ano'] . "<br>";
    echo "Gênero: " . htmlspecialchars($livro['genero']) . "<br>";
    echo "Editora: " . htmlspecialchars($livro['editora']) . "<br>";
    echo "Páginas: " . $livro['paginas'] . "<br>";
    echo "Status: " . $livro['status'] . "<br>";
    echo "<br> Cadastrado por funcionário: " . htmlspecialchars($livro['funcionario_nome']) . " (ID: " . $livro['funcionario_id'] . ")<hr>";

    $sql_emp = "
    SELECT c.id AS cliente_id, c.nome AS cliente_nome, e.data_emprestimo, e.data_devolucao, e.status
    FROM emprestimos e
    JOIN clientes c ON e.cliente_id = c.id
    WHERE e.livro_id = ?
    ORDER BY e.data_emprestimo DESC
    ";
    $stmt_emp = $conexao->prepare($sql_emp);
    $stmt_emp->bind_param("i", $livro['id']);
    $stmt_emp->execute();
    $resultado_emp = $stmt_emp->get_result();

    if ($resultado_emp->num_rows > 0) {
        echo "<h3>Histórico de Empréstimos deste livro:</h3>";
        echo "<ul>";
        while ($emprestimo = $resultado_emp->fetch_assoc()) {
            echo "<li>Cliente: " . htmlspecialchars($emprestimo['cliente_nome']) . 
                 " (ID: " . $emprestimo['cliente_id'] . ") - Empréstimo: " . $emprestimo['data_emprestimo'] . 
                 " - Devolução: " . ($emprestimo['data_devolucao'] ?? 'Não devolvido') .
                 " - Status: " . $emprestimo['status'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhum empréstimo registrado para este livro.</p>";
    }
    echo "<hr>";
    $stmt_emp->close();
}

echo "<br><a href='index.php'>Voltar para o menu de busca</a>";

$stmt->close();
$conexao->close();
?>
