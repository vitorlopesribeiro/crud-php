<?php
require_once 'conexao.php'; // cria $pdo (PDO)

// ação: listar | form | salvar | deletar
$acao = $_GET['acao'] ?? 'listar';

// ---------- LISTAR ----------
function listarPublicacoes($pdo) {
    $sql = "SELECT p.id_publicacao, p.nm_titulo, p.dt_publicacao, p.nm_palavra_chave,
                   a.nm_autor, d.nm_evento AS nm_divulgacao, t.ds_tipo_publicacao
            FROM publicacao p
            JOIN autor a ON p.id_autor = a.id_autor
            JOIN divulgacao d ON p.id_divulgacao = d.id_divulgacao
            JOIN tipo_publicacao t ON p.id_tipo_publicacao = t.id_tipo_publicacao
            ORDER BY p.id_publicacao DESC";
    $publicacoes = $pdo->query($sql)->fetchAll();
    ?>
    <h2>Lista de Publicações</h2>
    <button onclick="carregarPagina('publicacao.php?acao=form')" 
            style="padding:8px 12px; background:#28a745; color:white; border:none; border-radius:6px;">
        + Nova Publicação
    </button>
    <br><br>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr style="background:#f0f0f0;">
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Divulgação</th>
            <th>Tipo</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($publicacoes as $p): ?>
            <tr>
                <td><?= $p['id_publicacao'] ?></td>
                <td><?= htmlspecialchars($p['nm_titulo']) ?></td>
                <td><?= htmlspecialchars($p['nm_autor']) ?></td>
                <td><?= htmlspecialchars($p['nm_divulgacao']) ?></td>
                <td><?= htmlspecialchars($p['ds_tipo_publicacao']) ?></td>
                <td><?= htmlspecialchars($p['dt_publicacao']) ?></td>
                <td>
                    <a href="#" style="color:orange;" onclick="carregarPagina('publicacao.php?acao=form&id=<?= $p['id_publicacao'] ?>')">Editar</a> |
                    <a href="#" style="color:red;"
                       onclick="if(confirm('Deseja excluir esta publicação?')) carregarPagina('publicacao.php?acao=deletar&id=<?= $p['id_publicacao'] ?>'); return false;">
                       Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

// ---------- FORMULÁRIO ----------
function formPublicacao($pdo, $id = null) {
    $pub = [
        'id_publicacao' => '',
        'nm_titulo' => '',
        'ds_resumo' => '',
        'dt_publicacao' => '',
        'nm_palavra_chave' => '',
        'id_divulgacao' => '',
        'id_autor' => '',
        'id_tipo_publicacao' => ''
    ];

    $autores = $pdo->query("SELECT id_autor, nm_autor FROM autor ORDER BY nm_autor")->fetchAll();
    $divs = $pdo->query("SELECT id_divulgacao, nm_evento FROM divulgacao ORDER BY nm_evento")->fetchAll();
    $tipos = $pdo->query("SELECT id_tipo_publicacao, ds_tipo_publicacao FROM tipo_publicacao ORDER BY ds_tipo_publicacao")->fetchAll();

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM publicacao WHERE id_publicacao = ?");
        $stmt->execute([$id]);
        $f = $stmt->fetch();
        if ($f) {
            $pub = array_merge($pub, $f);
            echo "<h2>Editar Publicação</h2>";
        } else {
            echo "<p>Publicação não encontrada.</p>";
            return;
        }
    } else {
        echo "<h2>Nova Publicação</h2>";
    }
    ?>
    <form method="POST" action="publicacao.php?acao=salvar" onsubmit="enviarFormulario(event, this)">
        <?php if ($id): ?>
            <input type="hidden" name="id_publicacao" value="<?= htmlspecialchars($pub['id_publicacao']) ?>">
        <?php endif; ?>

        <label>Título:</label><br>
        <input type="text" name="nm_titulo" required value="<?= htmlspecialchars($pub['nm_titulo']) ?>"><br><br>

        <label>Resumo:</label><br>
        <textarea name="ds_resumo" rows="5" cols="60" required><?= htmlspecialchars($pub['ds_resumo']) ?></textarea><br><br>

        <label>Data da Publicação:</label><br>
        <input type="date" name="dt_publicacao" required value="<?= htmlspecialchars($pub['dt_publicacao']) ?>"><br><br>

        <label>Palavra-chave:</label><br>
        <input type="text" name="nm_palavra_chave" value="<?= htmlspecialchars($pub['nm_palavra_chave']) ?>"><br><br>

        <label>Autor:</label><br>
        <select name="id_autor" required>
            <option value="">Selecione...</option>
            <?php foreach ($autores as $a): ?>
                <option value="<?= $a['id_autor'] ?>" <?= $a['id_autor'] == $pub['id_autor'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nm_autor']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Divulgação:</label><br>
        <select name="id_divulgacao" required>
            <option value="">Selecione...</option>
            <?php foreach ($divs as $d): ?>
                <option value="<?= $d['id_divulgacao'] ?>" <?= $d['id_divulgacao'] == $pub['id_divulgacao'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nm_evento']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Tipo de Publicação:</label><br>
        <select name="id_tipo_publicacao" required>
            <option value="">Selecione...</option>
            <?php foreach ($tipos as $t): ?>
                <option value="<?= $t['id_tipo_publicacao'] ?>" <?= $t['id_tipo_publicacao'] == $pub['id_tipo_publicacao'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['ds_tipo_publicacao']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit" style="padding:8px 12px;">Salvar</button>
        <button type="button" style="margin-left:10px;" onclick="carregarPagina('publicacao.php')">Cancelar</button>
    </form>
    <?php
}

// ---------- SALVAR ----------
function salvarPublicacao($pdo) {
    $titulo = trim($_POST['nm_titulo'] ?? '');
    $resumo = trim($_POST['ds_resumo'] ?? '');
    $data = $_POST['dt_publicacao'] ?? '';
    $palavra = trim($_POST['nm_palavra_chave'] ?? '');
    $id_div = intval($_POST['id_divulgacao'] ?? 0);
    $id_autor = intval($_POST['id_autor'] ?? 0);
    $id_tipo = intval($_POST['id_tipo_publicacao'] ?? 0);

    if (!$titulo || !$resumo || !$data || !$id_div || !$id_autor || !$id_tipo) {
        echo "<p>Preencha todos os campos obrigatórios.</p>";
        return;
    }

    $id = $_POST['id_publicacao'] ?? null;
    if ($id) {
        $sql = "UPDATE publicacao SET nm_titulo=:titulo, ds_resumo=:resumo, dt_publicacao=:data,
                nm_palavra_chave=:palavra, id_divulgacao=:div, id_autor=:autor, id_tipo_publicacao=:tipo
                WHERE id_publicacao=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo, ':resumo' => $resumo, ':data' => $data, ':palavra' => $palavra,
            ':div' => $id_div, ':autor' => $id_autor, ':tipo' => $id_tipo, ':id' => $id
        ]);
        echo "<p>Publicação atualizada com sucesso!</p>";
    } else {
        $sql = "INSERT INTO publicacao (nm_titulo, ds_resumo, dt_publicacao, nm_palavra_chave, id_divulgacao, id_autor, id_tipo_publicacao)
                VALUES (:titulo, :resumo, :data, :palavra, :div, :autor, :tipo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo, ':resumo' => $resumo, ':data' => $data, ':palavra' => $palavra,
            ':div' => $id_div, ':autor' => $id_autor, ':tipo' => $id_tipo
        ]);
        echo "<p>Publicação cadastrada com sucesso!</p>";
    }

    echo "<button onclick=\"carregarPagina('publicacao.php')\">Voltar à lista</button>";
}

// ---------- DELETAR ----------
function deletarPublicacao($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM publicacao WHERE id_publicacao = ?");
    $stmt->execute([$id]);
    echo "<p>Publicação excluída com sucesso!</p>";
    echo "<button onclick=\"carregarPagina('publicacao.php')\">Voltar à lista</button>";
}

// ---------- ROTEAMENTO ----------
if ($acao == 'listar') {
    listarPublicacoes($pdo);
} elseif ($acao == 'form') {
    formPublicacao($pdo, $_GET['id'] ?? null);
} elseif ($acao == 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    salvarPublicacao($pdo);
} elseif ($acao == 'deletar' && isset($_GET['id'])) {
    deletarPublicacao($pdo, $_GET['id']);
} else {
    echo "<p>Ação inválida.</p>";
}
?>
