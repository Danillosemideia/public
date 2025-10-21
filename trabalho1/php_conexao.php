<?php
/**
 * Arquivo: conexao.php
 * Descrição: Conexão com banco de dados MySQL
 * Banco: escola_db
 */

// Configurações de conexão
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'escola_db');

try {
    // Criar conexão usando PDO (mais seguro)
    $conn = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        )
    );
} catch (PDOException $e) {
    die('Erro de conexão com banco de dados: ' . $e->getMessage());
}
?>