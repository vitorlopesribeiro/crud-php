<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Sistema de Publicações</title>
<style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #cad7ebff; }
    header { background: #516070ff; color: white; padding: 15px; text-align: center; }
    nav button { margin: 5px; padding: 10px 20px; border: none; background: #516070ff; color: white; border-radius: 5px; cursor: pointer; }
    nav button:hover { background: #067702ff; }
    #conteudo { padding: 20px; }
</style>
</head>
<body>

<header><h1>Gerenciador de Publicações</h1></header>

<nav style="text-align:center;">
    <button onclick="carregarPagina('autor.php')">Autores</button>
    <button onclick="carregarPagina('convidado.php')">Convidados</button>
    <button onclick="carregarPagina('divulgacao.php')">Divulgações</button>
    <button onclick="carregarPagina('publicacao.php')">Publicações</button>
</nav>

<div id="conteudo">
    <p>Selecione uma das opções acima para começar.</p>
</div>

<script>
async function carregarPagina(url) {
    const conteudo = document.getElementById('conteudo');
    conteudo.innerHTML = "<p>Carregando...</p>";
    try {
        const resp = await fetch(url);
        const html = await resp.text();
        conteudo.innerHTML = html;
    } catch (e) {
        conteudo.innerHTML = "<p>Erro ao carregar conteúdo.</p>";
    }
}

async function enviarFormulario(event, form) {
    event.preventDefault();
    const dados = new FormData(form);
    const resp = await fetch(form.action, { method: 'POST', body: dados });
    const html = await resp.text();
    document.getElementById('conteudo').innerHTML = html;
}
</script>

</body>
</html>
