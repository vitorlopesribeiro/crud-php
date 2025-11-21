<?php
require_once 'conexao.php';
$acao = $_GET['acao'] ?? 'listar';

// ---------- LISTAR ----------
function listarDivulgacoes($pdo) {
    // inclui o nome do convidado ao listar
    $sql = "SELECT d.*, c.nm_convidado 
            FROM divulgacao d
            LEFT JOIN convidado c ON d.id_convidado = c.id_convidado
            ORDER BY d.id_divulgacao DESC";
    $divs = $pdo->query($sql)->fetchAll();
    ?>
    <h2>Lista de Divulgações</h2>
    <button onclick="carregarPagina('divulgacao.php?acao=form')" 
            style="padding:8px 12px; background:#28a745; color:white; border:none; border-radius:6px;">
        + Nova Divulgação
    </button>
    <br><br>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr style="background:#f0f0f0;">
            <th>ID</th>
            <th>Evento</th>
            <th>Local</th>
            <th>Data</th>
            <th>Convidado</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($divs as $d): ?>
            <tr>
                <td><?= $d['id_divulgacao'] ?></td>
                <td><?= htmlspecialchars($d['nm_evento']) ?></td>
                <td><?= htmlspecialchars($d['nm_local']) ?></td>
                <td><?= htmlspecialchars($d['dt_evento']) ?></td>
                <td><?= htmlspecialchars($d['nm_convidado'] ?? '-') ?></td>
                <td>
                    <a href="#" onclick="carregarPagina('divulgacao.php?acao=form&id=<?= $d['id_divulgacao'] ?>')" style="color:orange;">Editar</a> |
                    <a href="#" onclick="if(confirm('Excluir esta divulgação?')) carregarPagina('divulgacao.php?acao=deletar&id=<?= $d['id_divulgacao'] ?>'); return false;" style="color:red;">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

// ---------- FORM ----------
function formDivulgacao($pdo, $id = null) {
    // busca os convidados cadastrados
    $convidados = $pdo->query("SELECT id_convidado, nm_convidado FROM convidado ORDER BY nm_convidado")->fetchAll();

    $div = ['id_divulgacao' => '', 'nm_evento' => '', 'nm_local' => '', 'dt_evento' => '', 'id_convidado' => ''];

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM divulgacao WHERE id_divulgacao = ?");
        $stmt->execute([$id]);
        $f = $stmt->fetch();
        if ($f) {
            $div = $f;
            echo "<h2>Editar Divulgação</h2>";
        } else {
            echo "<p>Divulgação não encontrada.</p>";
            return;
        }
    } else {
        echo "<h2>Nova Divulgação</h2>";
    }
    ?>
    <form method="POST" action="divulgacao.php?acao=salvar" onsubmit="enviarFormulario(event, this)">
        <?php if ($id): ?>
            <input type="hidden" name="id_divulgacao" value="<?= htmlspecialchars($div['id_divulgacao']) ?>">
        <?php endif; ?>

        <label>Nome do Evento:</label><br>
        <input type="text" name="nm_evento" required value="<?= htmlspecialchars($div['nm_evento']) ?>"><br><br>

        <label>Local:</label><br>
        <input type="text" name="nm_local" value="<?= htmlspecialchars($div['nm_local']) ?>"><br><br>

        <label>Data:</label><br>
        <input type="date" name="dt_evento" value="<?= htmlspecialchars($div['dt_evento']) ?>"><br><br>

        <label>Convidado:</label><br>
        <select name="id_convidado" required>
            <option value="">Selecione um convidado</option>
            <?php foreach ($convidados as $c): ?>
                <option value="<?= $c['id_convidado'] ?>" <?= ($c['id_convidado'] == $div['id_convidado']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nm_convidado']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Salvar</button>
        <button type="button" onclick="carregarPagina('divulgacao.php')">Cancelar</button>
    </form>
    <?php
}

// ---------- SALVAR ----------
function salvarDivulgacao($pdo) {
    $id = $_POST['id_divulgacao'] ?? null;
    $evento = trim($_POST['nm_evento'] ?? '');
    $local = trim($_POST['nm_local'] ?? '');
    $data = $_POST['dt_evento'] ?? null;
    $id_convidado = $_POST['id_convidado'] ?? null;

    if (!$evento || !$id_convidado) {
        echo "<p>Informe o nome do evento e selecione um convidado.</p>";
        return;
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE divulgacao SET nm_evento=?, nm_local=?, dt_evento=?, id_convidado=? WHERE id_divulgacao=?");
        $stmt->execute([$evento, $local, $data, $id_convidado, $id]);
        echo "<p>Divulgação atualizada!</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO divulgacao (nm_evento, nm_local, dt_evento, id_convidado) VALUES (?, ?, ?, ?)");
        $stmt->execute([$evento, $local, $data, $id_convidado]);
        echo "<p>Divulgação cadastrada!</p>";
    }

    echo "<button onclick=\"carregarPagina('divulgacao.php')\">Voltar</button>";
}

// ---------- DELETAR ----------
function deletarDivulgacao($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM divulgacao WHERE id_divulgacao = ?");
    $stmt->execute([$id]);
    echo "<p>Divulgação excluída!</p>";
    echo "<button onclick=\"carregarPagina('divulgacao.php')\">Voltar</button>";
}

// ---------- ROTEAMENTO ----------
if ($acao == 'listar') listarDivulgacoes($pdo);
elseif ($acao == 'form') formDivulgacao($pdo, $_GET['id'] ?? null);
elseif ($acao == 'salvar' && $_SERVER['REQUEST_METHOD'] == 'POST') salvarDivulgacao($pdo);
elseif ($acao == 'deletar' && isset($_GET['id'])) deletarDivulgacao($pdo, $_GET['id']);
else echo "<p>Ação inválida.</p>";
?>

