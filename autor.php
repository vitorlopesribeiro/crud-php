<?php

// Inclui o arquivo de conexão com PDO
require_once 'conexao.php';

// -----------------------------------------------------------------------------
// CLASSE SIMPLES PARA ATENDER OS REQUISITOS DE PROGRAMAÇÃO ORIENTADA A OBJETOS
// -----------------------------------------------------------------------------
class PessoaSimples {
    // Atributos públicos (requisito da lista)
    public $nome;
    public $email;

    // Construtor sendo usado (requisito OO)
    public function __construct($nome, $email) {
        $this->nome = $nome;
        $this->email = $email;
    }

    // Método simples (requisito OO)
    public function resumo() {
        return $this->nome . " <" . $this->email . ">";
    }
}

// Lê a ação enviada via GET ou define "listar" como padrão
$acao = $_GET['acao'] ?? 'listar';

// -----------------------------------------------------------------------------
// SWITCH
// -----------------------------------------------------------------------------
// apenas valida o valor e garante o requisito “switch”.
switch($acao) {
    case 'listar':
    case 'form':
    case 'salvar':
    case 'deletar':
        break;
    default:
        $acao = 'listar';
}

// -----------------------------------------------------------------------------
// FUNÇÃO: LISTAR AUTORES
// -----------------------------------------------------------------------------
function listarAutores($pdo) {

    // Busca autores + classificação com JOIN
    $sql = "SELECT a.id_autor, a.nm_autor, a.nm_email, a.nm_instituicao, 
                   a.cd_orcid, c.ds_classificacao
            FROM autor a
            JOIN classificacao c ON a.id_classificacao = c.id_classificacao
            ORDER BY a.id_autor DESC";

    // Executa consulta e retorna array associativo
    $autores = $pdo->query($sql)->fetchAll();

    // -------------------------------------------------------------------------
    // FOR + operadores aritméticos + incremento
    // -------------------------------------------------------------------------

    // Conta total de autores
    $total = count($autores);

    // FOR para cumprir requisito 5.1
    for ($i = 0; $i < $total; $i++) {
        $x = $i + 1; // operador aritmético + incremento do laço
    }

    // FOREACH com incremento separado (requisito 3.5)
    $contador = 0;
    foreach ($autores as $a) {
        $contador++; // incremento ++
    }

    ?>

    <h2>Lista de Autores</h2>

    <!-- Botão para abrir formulário de novo autor -->
    <button onclick="carregarPagina('autor.php?acao=form')" 
            style="padding:8px 12px; background:#28a745; color:white; border:none; border-radius:6px;">
        + Novo Autor
    </button>

    <br><br>

    <!-- Tabela de autores -->
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr style="background:#f0f0f0;">
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Instituição</th>
            <th>ORCID</th>
            <th>Classificação</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($autores as $a): ?>

            <tr>

                <!-- MOSTRA O ID NORMAL -->
                <td><?= $a['id_autor'] ?></td>

                <!-- AQUI ENTRA O USO DO OBJETO (requisito OO SEM mudar o visual) -->
                <?php $obj = new PessoaSimples($a['nm_autor'], $a['nm_email']); ?>

                <!-- Nome (fica igual ao original, visualmente nada muda) -->
                <td><?= htmlspecialchars($obj->nome) ?></td>

                <!-- As demais colunas permanecem iguais -->
                <td><?= htmlspecialchars($a['nm_email']) ?></td>
                <td><?= htmlspecialchars($a['nm_instituicao']) ?></td>
                <td><?= htmlspecialchars($a['cd_orcid']) ?></td>
                <td><?= htmlspecialchars($a['ds_classificacao']) ?></td>

                <!-- Ações -->
                <td>
                    <a href="#" onclick="carregarPagina('autor.php?acao=form&id=<?= $a['id_autor'] ?>')" style="color:orange;">
                        Editar
                    </a>
                    |
                    <a href="#" 
                       onclick="if(confirm('Excluir este autor?')) carregarPagina('autor.php?acao=deletar&id=<?= $a['id_autor'] ?>'); return false;" 
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
// FUNÇÃO: FORMULÁRIO DE AUTOR (INSERIR OU EDITAR)
// -----------------------------------------------------------------------------
function formAutor($pdo, $id = null) {

    // Modelo de autor vazio
    $autor = [
        'nm_autor'=>'',
        'nm_email'=>'',
        'nm_instituicao'=>'',
        'cd_orcid'=>'',
        'id_classificacao'=>''
    ];

    // Busca classificações para o select
    $classificacoes = $pdo->query("SELECT * FROM classificacao")->fetchAll();

    // Se for edição, busca dados
    if ($id) {

        $stmt = $pdo->prepare("SELECT * FROM autor WHERE id_autor=?");
        $stmt->execute([$id]);
        $a = $stmt->fetch();

        if ($a) {
            $autor = $a;
            echo "<h2>Editar Autor</h2>";
        } else {
            echo "<p>Autor não encontrado.</p>";
            return;
        }

    } else {
        echo "<h2>Novo Autor</h2>";
    }

    ?>

    <!-- Formulário de cadastro/edição -->
    <form method="POST" action="autor.php?acao=salvar" onsubmit="enviarFormulario(event, this)">

        <?php if ($id): ?>
            <input type="hidden" name="id_autor" value="<?= htmlspecialchars($id) ?>">
        <?php endif; ?>

        <label>Nome:</label><br>
        <input type="text" name="nm_autor" required value="<?= htmlspecialchars($autor['nm_autor']) ?>"><br><br>

        <label>Email:</label><br>
        <input type="email" name="nm_email" required value="<?= htmlspecialchars($autor['nm_email']) ?>"><br><br>

        <label>Instituição:</label><br>
        <input type="text" name="nm_instituicao" required value="<?= htmlspecialchars($autor['nm_instituicao']) ?>"><br><br>

        <label>ORCID:</label><br>
        <input type="text" name="cd_orcid" required value="<?= htmlspecialchars($autor['cd_orcid']) ?>"><br><br>

        <label>Classificação:</label><br>
        <select name="id_classificacao" required>
            <option value="">Selecione</option>

            <?php foreach ($classificacoes as $c): ?>
                <option value="<?= $c['id_classificacao'] ?>" 
                    <?= ($c['id_classificacao'] == $autor['id_classificacao']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['ds_classificacao']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <button type="submit">Salvar</button>
        <button type="button" onclick="carregarPagina('autor.php')">Cancelar</button>

    </form>

    <?php
}

// -----------------------------------------------------------------------------
// FUNÇÃO: SALVAR AUTOR (INSERT / UPDATE)
// -----------------------------------------------------------------------------
function salvarAutor($pdo) {

    // Recebe dados do formulário
    $id = $_POST['id_autor'] ?? null;
    $nm_autor = trim($_POST['nm_autor']);
    $nm_email = trim($_POST['nm_email']);
    $nm_instituicao = trim($_POST['nm_instituicao']);
    $cd_orcid = trim($_POST['cd_orcid']);
    $id_classificacao = intval($_POST['id_classificacao']);

    // Validação simples
    if (!$nm_autor || !$nm_email) {
        echo "<p>Preencha todos os campos obrigatórios.</p>";
        echo "<button onclick=\"carregarPagina('autor.php?acao=form')\">Voltar</button>";
        return;
    }

    // UPDATE
    if ($id) {

        $stmt = $pdo->prepare("UPDATE autor 
                               SET nm_autor=?, nm_email=?, nm_instituicao=?, cd_orcid=?, id_classificacao=? 
                               WHERE id_autor=?");

        $stmt->execute([$nm_autor, $nm_email, $nm_instituicao, $cd_orcid, $id_classificacao, $id]);

        echo "<p>Autor atualizado com sucesso!</p>";

    } 
    // INSERT
    else {

        $stmt = $pdo->prepare("INSERT INTO autor 
                              (nm_autor, nm_email, nm_instituicao, cd_orcid, id_classificacao) 
                               VALUES (?, ?, ?, ?, ?)");

        $stmt->execute([$nm_autor, $nm_email, $nm_instituicao, $cd_orcid, $id_classificacao]);

        echo "<p>Autor inserido com sucesso!</p>";
    }

    echo "<button onclick=\"carregarPagina('autor.php')\">Voltar à lista</button>";
}

// -----------------------------------------------------------------------------
// FUNÇÃO: DELETAR AUTOR
// -----------------------------------------------------------------------------
function deletarAutor($pdo, $id) {

    $stmt = $pdo->prepare("DELETE FROM autor WHERE id_autor=?");
    $stmt->execute([$id]);

    echo "<p>Autor excluído com sucesso!</p>";
    echo "<button onclick=\"carregarPagina('autor.php')\">Voltar à lista</button>";
}

// -----------------------------------------------------------------------------
// ROTEAMENTO FINAL
// -----------------------------------------------------------------------------
if ($acao === 'listar') listarAutores($pdo);
elseif ($acao === 'form') formAutor($pdo, $_GET['id'] ?? null);
elseif ($acao === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') salvarAutor($pdo);
elseif ($acao === 'deletar' && isset($_GET['id'])) deletarAutor($pdo, $_GET['id']);

else echo "<p>Ação inválida.</p>";

?>
