<?php

// Inclui o arquivo de conexão PDO (cria a variável $pdo)
require_once 'conexao.php';

// Lê a ação enviada via URL (listar, form, salvar, deletar)
$acao = $_GET['acao'] ?? 'listar';


// -----------------------------------------------------------------------------
// FUNÇÃO: LISTAR CONVIDADOS
// -----------------------------------------------------------------------------
function listarConvidados($pdo) {

    // Consulta todos os convidados ordenando do mais recente para o mais antigo
    $convs = $pdo->query("SELECT * FROM convidado ORDER BY id_convidado DESC")->fetchAll();
    ?>

    <!-- Título da página -->
    <h2>Lista de Convidados</h2>

    <!-- Botão que abre o formulário para cadastrar um novo convidado -->
    <button onclick="carregarPagina('convidado.php?acao=form')" 
            style="padding:8px 12px; background:#28a745; color:white; border:none; border-radius:6px;">
        + Novo Convidado
    </button>

    <br><br>

    <!-- Tabela com os dados dos convidados -->
    <table border="1" cellpadding="8" cellspacing="0" width="100%">

        <!-- Cabeçalho da tabela -->
        <tr style="background:#f0f0f0;">
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Instituição</th>
            <th>Cargo</th>
            <th>Ações</th>
        </tr>

        <!-- Loop que percorre cada convidado encontrado -->
        <?php foreach ($convs as $c): ?>
            <tr>
                <!-- Exibe cada campo do banco -->
                <td><?= $c['id_convidado'] ?></td>
                <td><?= htmlspecialchars($c['nm_convidado']) ?></td>
                <td><?= htmlspecialchars($c['nm_email']) ?></td>
                <td><?= htmlspecialchars($c['nm_instituicao']) ?></td>
                <td><?= htmlspecialchars($c['nm_cargo']) ?></td>

                <!-- Ações de editar e excluir -->
                <td>
                    <!-- Editar -->
                    <a href="#" onclick="carregarPagina('convidado.php?acao=form&id=<?= $c['id_convidado'] ?>')" style="color:orange;">
                        Editar
                    </a> |

                    <!-- Excluir com confirmação -->
                    <a href="#"
                       onclick="if(confirm('Excluir este convidado?')) carregarPagina('convidado.php?acao=deletar&id=<?= $c['id_convidado'] ?>'); return false;"
                       style="color:red;">
                        Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>
    <?php
}


// -----------------------------------------------------------------------------
// FUNÇÃO: FORMULÁRIO DE CADASTRO E EDIÇÃO
// -----------------------------------------------------------------------------
function formConvidado($pdo, $id = null) {

    // Modelo de dados caso seja novo cadastro
    $conv = [
        'id_convidado' => '',
        'nm_convidado' => '',
        'nm_email' => '',
        'nm_instituicao' => '',
        'nm_cargo' => ''
    ];

    // Se for edição, busca o convidado no banco
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM convidado WHERE id_convidado=?");
        $stmt->execute([$id]);
        $f = $stmt->fetch();

        // Se encontrou o registro, carrega no formulário
        if ($f) {
            $conv = $f;
            echo "<h2>Editar Convidado</h2>";
        } else {
            // Se não encontrou, exibe erro
            echo "<p>Convidado não encontrado.</p>";
            return;
        }

    } else {
        echo "<h2>Novo Convidado</h2>";
    }
    ?>

    <!-- Formulário: envia via POST -->
    <form method="POST" action="convidado.php?acao=salvar" onsubmit="enviarFormulario(event, this)">

        <!-- Campo oculto para ID quando estiver editando -->
        <?php if ($id): ?>
            <input type="hidden" name="id_convidado" value="<?= htmlspecialchars($conv['id_convidado']) ?>">
        <?php endif; ?>

        <!-- Campo nome -->
        <label>Nome:</label><br>
        <input type="text" name="nm_convidado" required value="<?= htmlspecialchars($conv['nm_convidado']) ?>"><br><br>

        <!-- Campo e-mail -->
        <label>E-mail:</label><br>
        <input type="email" name="nm_email" required value="<?= htmlspecialchars($conv['nm_email']) ?>"><br><br>

        <!-- Campo instituição -->
        <label>Instituição:</label><br>
        <input type="text" name="nm_instituicao" value="<?= htmlspecialchars($conv['nm_instituicao']) ?>"><br><br>

        <!-- Campo cargo -->
        <label>Cargo:</label><br>
        <input type="text" name="nm_cargo" value="<?= htmlspecialchars($conv['nm_cargo']) ?>"><br><br>

        <!-- Botões -->
        <button type="submit">Salvar</button>
        <button type="button" onclick="carregarPagina('convidado.php')">Cancelar</button>

    </form>

    <?php
}


// -----------------------------------------------------------------------------
// FUNÇÃO: SALVAR (INSERIR/ATUALIZAR)
// -----------------------------------------------------------------------------
function salvarConvidado($pdo) {

    // Recebe dados do formulário
    $id = $_POST['id_convidado'] ?? null;
    $nome = trim($_POST['nm_convidado'] ?? '');
    $email = trim($_POST['nm_email'] ?? '');
    $inst = trim($_POST['nm_instituicao'] ?? '');
    $cargo = trim($_POST['nm_cargo'] ?? '');

    // Validação simples
    if (!$nome || !$email) {
        echo "<p>Nome e e-mail são obrigatórios.</p>";
        return;
    }

    // Atualiza se tiver ID
    if ($id) {

        $stmt = $pdo->prepare("UPDATE convidado 
                               SET nm_convidado=?, nm_email=?, nm_instituicao=?, nm_cargo=? 
                               WHERE id_convidado=?");

        $stmt->execute([$nome, $email, $inst, $cargo, $id]);

        echo "<p>Convidado atualizado!</p>";

    } 
    // Insere novo registro
    else {

        $stmt = $pdo->prepare("INSERT INTO convidado 
                               (nm_convidado, nm_email, nm_instituicao, nm_cargo) 
                               VALUES (?, ?, ?, ?)");

        $stmt->execute([$nome, $email, $inst, $cargo]);

        echo "<p>Convidado cadastrado!</p>";
    }

    echo "<button onclick=\"carregarPagina('convidado.php')\">Voltar</button>";
}


// -----------------------------------------------------------------------------
// FUNÇÃO: DELETAR REGISTRO
// -----------------------------------------------------------------------------
function deletarConvidado($pdo, $id) {

    // Exclui o convidado pelo ID
    $stmt = $pdo->prepare("DELETE FROM convidado WHERE id_convidado=?");
    $stmt->execute([$id]);

    echo "<p>Convidado excluído!</p>";
    echo "<button onclick=\"carregarPagina('convidado.php')\">Voltar</button>";
}


// -----------------------------------------------------------------------------
// ROTEAMENTO (decide qual função executar)
// -----------------------------------------------------------------------------
if ($acao == 'listar') listarConvidados($pdo);
elseif ($acao == 'form') formConvidado($pdo, $_GET['id'] ?? null);
elseif ($acao == 'salvar' && $_SERVER['REQUEST_METHOD'] == 'POST') salvarConvidado($pdo);
elseif ($acao == 'deletar' && isset($_GET['id'])) deletarConvidado($pdo, $_GET['id']);
else echo "<p>Ação inválida.</p>";

?>
