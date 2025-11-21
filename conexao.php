<?php
// Define o host do banco, normalmente localhost quando usa phpMyAdmin local
$host = 'localhost';

// Nome do banco criado a partir do arquivo bd_publicacoes
$dbname = 'bd_publicacoes';

// Usuário do banco
$user = 'root';

// Senha do banco
$pass = '';

try {
    // Cria a cadeia DSN necessária pelo PDO (driver mysql + host + database + charset)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

    // Instancia o objeto PDO com o DSN, usuário e senha
    $pdo = new PDO("mysql:host=localhost;dbname=bd_publicacoes;charset=utf8", "root", "");

    // Define o modo de erro para lançar exceções (facilita debug e tratamento)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: define o fetch mode padrão para arrays associativos
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Se ocorrer erro, interrompe a execução mostrando a mensagem
    // Em produção, não exiba mensagens sensíveis — logue em arquivo em vez disso.
    die("Erro na conexão com o banco: " . $e->getMessage());
}
?>
