<?php
/**
 * Arquivo: gestao_estoque.php
 * Descrição: Interface de Gestão de Estoque
 * RF-012 a RF-018: Requisitos de Gestão
 */

require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

verificarAutenticacao();
$usuario = obterUsuarioLogado($conn);

$mensagem = '';
$tipo_mensagem = '';
$alerta_estoque = null;

// Obter produtos em ordem alfabética (Bubble Sort)
$sqlProdutos = "SELECT id, nome, quantidade_atual, estoque_minimo FROM produtos WHERE ativo = 1";
$stmtProdutos = $conn->prepare($sqlProdutos);
$stmtProdutos->execute();
$produtos = $stmtProdutos->fetchAll();

// Aplicar Bubble Sort
bubbleSort($produtos, 'nome');

// Processar formulário de movimentação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = $_POST['produto_id'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $quantidade = $_POST['quantidade'] ?? '';
    $data_movimentacao = $_POST['data_movimentacao'] ?? '';
    $observacoes = sanitizar($_POST['observacoes'] ?? '');
    
    // Validações
    $erros = [];
    
    if (empty($produto_id)) {
        $erros[] = 'Selecione um produto';
    }
    
    if (empty($tipo) || !in_array($tipo, ['entrada', 'saida'])) {
        $erros[] = 'Selecione um tipo válido de movimentação';
    }
    
    if (!validarQuantidadeMovimentacao($quantidade)) {
        $erros[] = 'Quantidade deve ser um número inteiro maior que zero';
    }
    
    if (empty($data_movimentacao)) {
        $erros[] = 'Data da movimentação é obrigatória';
    } elseif (!validarData($data_movimentacao)) {
        $erros[] = 'Data não pode ser futura';
    }
    
    // Validações específicas para saída
    if ($tipo === 'saida') {
        $produtoAtual = obterProduto($conn, $produto_id);
        
        if (!$produtoAtual) {
            $erros[] = 'Produto não encontrado';
        } elseif ($produtoAtual['quantidade_atual'] < $quantidade) {
            $erros[] = 'Quantidade de saída não pode exceder o estoque disponível (Disponível: ' . $produtoAtual['quantidade_atual'] . ' | Solicitado: ' . $quantidade . ')';
        }
    }
    
    if (empty($erros)) {
        try {
            if (registrarMovimentacao($conn, $produto_id, $_SESSION['usuario_id'], $tipo, $quantidade, $data_movimentacao, $observacoes)) {
                $mensagem = 'Movimentação registrada com sucesso!';
                $tipo_mensagem = 'sucesso';
                
                // Verificar estoque mínimo após saída
                if ($tipo === 'saida') {
                    $alerta = verificarEstoqueMinimo($conn, $produto_id);
                    if ($alerta['abaixo']) {
                        $produtoNome = obterProduto($conn, $produto_id)['nome'];
                        $alerta_estoque = array(
                            'produto' => $produtoNome,
                            'atual' => $alerta['atual'],
                            'minimo' => $alerta['minimo']
                        );
                    }
                }
                
                // Atualizar lista de produtos
                $stmtProdutos = $conn->prepare($sqlProdutos);
                $stmtProdutos->execute();
                $produtos = $stmtProdutos->fetchAll();
                bubbleSort($produtos, 'nome');
            }
        } catch (Exception $e) {
            $mensagem = 'Erro ao registrar movimentação: ' . $e->getMessage();
            $tipo_mensagem = 'erro';
        }
    } else {
        $mensagem = implode('<br>', $erros);
        $tipo_mensagem = 'erro';
    }
}

// Obter histórico de movimentações
$historico = obterHistorico($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Estoque - Sistema de Gestão de Estoque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-principal">
        <!-- Cabeçalho -->
        <header class="cabecalho">
            <div class="cabecalho-esquerda">
                <h1>📦 Sistema de Gestão de Estoque</h1>
            </div>
            <div class="cabecalho-direita">
                <span class="usuario-info">Bem-vindo, <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong></span>
                <a href="dashboard.php?acao=logout" class="btn btn-secundario btn-pequeno">Logout</a>
            </div>
        </header>
        
        <!-- Navegação -->
        <nav class="navegacao">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="cadastro_produtos.php">Cadastro de Produtos</a></li>
                <li><a href="gestao_estoque.php" class="ativo">Gestão de Estoque</a></li>
            </ul>
        </nav>
        
        <!-- Conteúdo Principal -->
        <main class="conteudo">
            <section class="secao">
                <h2>Gestão de Estoque</h2>
                <p>Registre entradas e saídas de produtos e consulte o histórico de movimentações.</p>
            </section>
            
            <!-- Mensagens -->
            <?php if (!empty($mensagem)): ?>
                <div class="alerta alerta-<?php echo $tipo_mensagem; ?>">
                    <strong><?php echo ucfirst($tipo_mensagem); ?>:</strong> <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>
            
            <!-- Alerta de Estoque Mínimo -->
            <?php if ($alerta_estoque): ?>
                <div class="alerta alerta-aviso" style="background-color: #fff3cd; border-color: #ffc107; color: #856404;">
                    <strong>⚠️ ALERTA DE ESTOQUE MÍNIMO:</strong>
                    <br>O produto <strong><?php echo htmlspecialchars($alerta_estoque['produto']); ?></strong> 
                    está com estoque abaixo do mínimo!
                    <br>Estoque Mínimo: <?php echo $alerta_estoque['minimo']; ?> | 
                    Estoque Atual: <?php echo $alerta_estoque['atual']; ?>
                </div>
            <?php endif; ?>
            
            <div class="layout-gestao">
                <!-- Formulário de Movimentação -->
                <section class="formulario-secao">
                    <h3>Registrar Movimentação</h3>
                    
                    <form method="POST" class="formulario">
                        <div class="grupo-entrada">
                            <label for="produto_id">Produto: <span class="obrigatorio">*</span></label>
                            <select id="produto_id" name="produto_id" required>
                                <option value="">-- Selecione um produto --</option>
                                <?php foreach ($produtos as $prod): ?>
                                    <option value="<?php echo $prod['id']; ?>">
                                        <?php echo htmlspecialchars($prod['nome']); ?> 
                                        (Estoque: <?php echo $prod['quantidade_atual']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="linha-entrada">
                            <div class="grupo-entrada">
                                <label for="tipo">Tipo de Movimentação: <span class="obrigatorio">*</span></label>
                                <select id="tipo" name="tipo" required onchange="atualizarLabelQuantidade()">
                                    <option value="">-- Selecione --</option>
                                    <option value="entrada">➕ Entrada</option>
                                    <option value="saida">➖ Saída</option>
                                </select>
                            </div>
                            
                            <div class="grupo-entrada">
                                <label for="quantidade">Quantidade: <span class="obrigatorio">*</span></label>
                                <input 
                                    type="number" 
                                    id="quantidade" 
                                    name="quantidade" 
                                    min="1"
                                    step="1"
                                    placeholder="0"
                                    required
                                >
                            </div>
                        </div>
                        
                        <div class="grupo-entrada">
                            <label for="data_movimentacao">Data da Movimentação: <span class="obrigatorio">*</span></label>
                            <input 
                                type="date" 
                                id="data_movimentacao" 
                                name="data_movimentacao"
                                value="<?php echo date('Y-m-d'); ?>"
                                required
                            >
                        </div>
                        
                        <div class="grupo-entrada">
                            <label for="observacoes">Observações:</label>
                            <textarea 
                                id="observacoes" 
                                name="observacoes" 
                                rows="3"
                                placeholder="Observações opcionais sobre a movimentação..."
                            ></textarea>
                        </div>
                        
                        <div class="botoes-formulario">
                            <button type="submit" class="btn btn-primario">
                                Registrar Movimentação
                            </button>
                        </div>
                    </form>
                </section>
                
                <!-- Produtos com Estoque Baixo -->
                <section class="info-secao">
                    <h3>⚠️ Produtos com Estoque Baixo</h3>
                    
                    <div class="lista-estoque-baixo">
                        <?php 
                        $produtosBaixos = array_filter($produtos, function($p) {
                            return $p['quantidade_atual'] < $p['estoque_minimo'];
                        });
                        
                        if (empty($produtosBaixos)): 
                        ?>
                            <p style="text-align: center; padding: 20px; color: #666;">
                                ✓ Todos os produtos estão com estoque adequado!
                            </p>
                        <?php else: ?>
                            <?php foreach ($produtosBaixos as $prod): ?>
                                <div class="item-estoque-baixo">
                                    <strong><?php echo htmlspecialchars($prod['nome']); ?></strong>
                                    <div class="barra-progresso">
                                        <?php 
                                        $percentual = ($prod['quantidade_atual'] / $prod['estoque_minimo']) * 100;
                                        $percentual = min($percentual, 100);
                                        ?>
                                        <div class="barra-preenchida" style="width: <?php echo $percentual; ?>%; background-color: #d9534f;"></div>
                                    </div>
                                    <small>
                                        Atual: <?php echo $prod['quantidade_atual']; ?> / 
                                        Mínimo: <?php echo $prod['estoque_minimo']; ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
            
            <!-- Histórico de Movimentações -->
            <section class="historico-secao">
                <h3>📋 Histórico de Movimentações</h3>
                
                <div class="tabela-responsiva">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produto</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                                <th>Data</th>
                                <th>Usuário</th>
                                <th>Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historico as $mov): ?>
                                <tr class="<?php echo $mov['tipo'] === 'entrada' ? 'linha-entrada' : 'linha-saida'; ?>">
                                    <td><?php echo $mov['id']; ?></td>
                                    <td><?php echo htmlspecialchars($mov['nome']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $mov['tipo'] === 'entrada' ? 'badge-entrada' : 'badge-saida'; ?>">
                                            <?php echo ucfirst($mov['tipo']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $mov['quantidade']; ?></td>
                                    <td><?php echo formatarData($mov['data_criacao']); ?></td>
                                    <td><?php echo htmlspecialchars($mov['usuario_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($mov['observacoes'] ?: '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
        
        <!-- Rodapé -->
        <footer class="rodape">
            <p>&copy; 2024 Sistema de Gestão de Estoque - Todos os direitos reservados</p>
        </footer>
    </div>
    
    <script>
        function atualizarLabelQuantidade() {
            const tipo = document.getElementById('tipo').value;
            const label = document.querySelector('label[for="quantidade"]');
            
            if (tipo === 'entrada') {
                label.innerHTML = 'Quantidade a Adicionar: <span class="obrigatorio">*</span>';
            } else if (tipo === 'saida') {
                label.innerHTML = 'Quantidade a Remover: <span class="obrigatorio">*</span>';
            } else {
                label.innerHTML = 'Quantidade: <span class="obrigatorio">*</span>';
            }
        }
        
        // Limitar data máxima ao dia de hoje
        document.addEventListener('DOMContentLoaded', function() {
            const dataInput = document.getElementById('data_movimentacao');
            dataInput.max = new Date().toISOString().split('T')[0];
        });
    </script>
</body>
</html>