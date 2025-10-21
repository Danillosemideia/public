<?php
/**
 * Arquivo: cadastro_produtos.php
 * Descrição: Interface de Cadastro de Produtos
 * RF-006 a RF-011: Requisitos de Cadastro
 */

require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

verificarAutenticacao();
$usuario = obterUsuarioLogado($conn);

$mensagem = '';
$tipo_mensagem = '';
$produtos = obterProdutos($conn);
$modo_edicao = false;
$produto_edicao = null;

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'criar') {
        $nome = sanitizar($_POST['nome'] ?? '');
        $descricao = sanitizar($_POST['descricao'] ?? '');
        $quantidade = $_POST['quantidade'] ?? '';
        $estoque_minimo = $_POST['estoque_minimo'] ?? '';
        
        // Validações
        $erros = [];
        
        if (empty($nome)) {
            $erros[] = 'Nome é obrigatório';
        } elseif (strlen($nome) > 100) {
            $erros[] = 'Nome não pode exceder 100 caracteres';
        }
        
        if (empty($descricao)) {
            $erros[] = 'Descrição é obrigatória';
        } elseif (strlen($descricao) > 500) {
            $erros[] = 'Descrição não pode exceder 500 caracteres';
        }
        
        if (!validarQuantidade($quantidade)) {
            $erros[] = 'Quantidade deve ser um número inteiro maior ou igual a zero';
        }
        
        if (!validarQuantidade($estoque_minimo)) {
            $erros[] = 'Estoque mínimo deve ser um número inteiro maior ou igual a zero';
        }
        
        if (empty($erros)) {
            try {
                if (criarProduto($conn, $nome, $descricao, $quantidade, $estoque_minimo, $_SESSION['usuario_id'])) {
                    $mensagem = 'Produto cadastrado com sucesso!';
                    $tipo_mensagem = 'sucesso';
                    $produtos = obterProdutos($conn);
                } else {
                    $mensagem = 'Erro ao cadastrar produto. Nome pode estar duplicado.';
                    $tipo_mensagem = 'erro';
                }
            } catch (Exception $e) {
                $mensagem = 'Erro ao cadastrar produto: ' . $e->getMessage();
                $tipo_mensagem = 'erro';
            }
        } else {
            $mensagem = implode('<br>', $erros);
            $tipo_mensagem = 'erro';
        }
    } 
    elseif ($acao === 'editar') {
        $id = $_POST['id'] ?? '';
        $nome = sanitizar($_POST['nome'] ?? '');
        $descricao = sanitizar($_POST['descricao'] ?? '');
        $quantidade = $_POST['quantidade'] ?? '';
        $estoque_minimo = $_POST['estoque_minimo'] ?? '';
        
        // Validações
        $erros = [];
        
        if (empty($id)) {
            $erros[] = 'ID do produto inválido';
        }
        
        if (empty($nome)) {
            $erros[] = 'Nome é obrigatório';
        } elseif (strlen($nome) > 100) {
            $erros[] = 'Nome não pode exceder 100 caracteres';
        }
        
        if (empty($descricao)) {
            $erros[] = 'Descrição é obrigatória';
        } elseif (strlen($descricao) > 500) {
            $erros[] = 'Descrição não pode exceder 500 caracteres';
        }
        
        if (!validarQuantidade($quantidade)) {
            $erros[] = 'Quantidade deve ser um número inteiro maior ou igual a zero';
        }
        
        if (!validarQuantidade($estoque_minimo)) {
            $erros[] = 'Estoque mínimo deve ser um número inteiro maior ou igual a zero';
        }
        
        if (empty($erros)) {
            try {
                if (atualizarProduto($conn, $id, $nome, $descricao, $quantidade, $estoque_minimo)) {
                    $mensagem = 'Produto atualizado com sucesso!';
                    $tipo_mensagem = 'sucesso';
                    $produtos = obterProdutos($conn);
                } else {
                    $mensagem = 'Erro ao atualizar produto.';
                    $tipo_mensagem = 'erro';
                }
            } catch (Exception $e) {
                $mensagem = 'Erro ao atualizar produto: ' . $e->getMessage();
                $tipo_mensagem = 'erro';
            }
        } else {
            $mensagem = implode('<br>', $erros);
            $tipo_mensagem = 'erro';
        }
    }
    elseif ($acao === 'deletar') {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $mensagem = 'ID do produto inválido';
            $tipo_mensagem = 'erro';
        } else {
            try {
                if (deletarProduto($conn, $id)) {
                    $mensagem = 'Produto deletado com sucesso!';
                    $tipo_mensagem = 'sucesso';
                    $produtos = obterProdutos($conn);
                } else {
                    $mensagem = 'Erro ao deletar produto.';
                    $tipo_mensagem = 'erro';
                }
            } catch (Exception $e) {
                $mensagem = 'Erro ao deletar produto: ' . $e->getMessage();
                $tipo_mensagem = 'erro';
            }
        }
    }
}

// Processar busca via AJAX
if (isset($_GET['busca'])) {
    $termo =