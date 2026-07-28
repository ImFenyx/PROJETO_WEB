<?php
require_once '../components/connect.php';
require_once '../components/head.php';
$id = $_GET['id'] ?? null;
$nome = $partido = $data_nascimento = $data_inicio = $data_fim = '';
if ($_SERVER['REQUEST_METHOD']==='POST'){
    $nome=$_POST['nome']??'';$partido=$_POST['partido_politico']??null;$data_nascimento=$_POST['data_nascimento']??null;$data_inicio=$_POST['data_inicio_mandato']??null;$data_fim=$_POST['data_fim_mandato']??null;
    if($id){ $s=mysqli_prepare($conexao,"UPDATE governantes SET nome=?, partido_politico=?, data_nascimento=?, data_inicio_mandato=?, data_fim_mandato=? WHERE id=?"); mysqli_stmt_bind_param($s,"sssssi",$nome,$partido,$data_nascimento,$data_inicio,$data_fim,$id); mysqli_stmt_execute($s); }else{ $s=mysqli_prepare($conexao,"INSERT INTO governantes (nome, partido_politico, data_nascimento, data_inicio_mandato, data_fim_mandato) VALUES (?,?,?,?,?)"); mysqli_stmt_bind_param($s,"sssss",$nome,$partido,$data_nascimento,$data_inicio,$data_fim); mysqli_stmt_execute($s); }
    header("Location: governantes.php"); exit;
}
if($id && empty($_POST)){ $row=mysqli_fetch_assoc(mysqli_query($conexao,"SELECT * FROM governantes WHERE id=$id")); $nome=$row['nome']; $partido=$row['partido_politico']; $data_nascimento=$row['data_nascimento']; $data_inicio=$row['data_inicio_mandato']; $data_fim=$row['data_fim_mandato']; }
if(isset($_GET['delete'])){ mysqli_query($conexao,"DELETE FROM governantes WHERE id=".intval($_GET['delete'])); header("Location: governantes.php"); exit; }
?>
<title>Governantes</title>
</head>
<body class="min-h-screen bg-linear-to-b from-mocha-base via-mocha-mantle to-mocha-crust font-sans text-mocha-text">
<div class="mx-auto max-w-5xl px-4 py-8 space-y-8">
<h1 class="text-3xl font-serif italic text-mocha-mauve">Governantes</h1>
<form method="POST" class="grid md:grid-cols-2 gap-4 bg-mocha-surface0 p-6 rounded-2xl border border-mocha-surface1" onsubmit="return confirm('Salvar governante?');">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div><label class="block text-sm mb-1">Nome</label><input name="nome" value="<?php echo htmlspecialchars($nome); ?>" required class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Partido</label><input name="partido_politico" value="<?php echo htmlspecialchars($partido); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Nascimento</label><input type="date" name="data_nascimento" value="<?php echo htmlspecialchars($data_nascimento); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Início Mandato</label><input type="date" name="data_inicio_mandato" value="<?php echo htmlspecialchars($data_inicio); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Fim Mandato</label><input type="date" name="data_fim_mandato" value="<?php echo htmlspecialchars($data_fim); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div class="md:col-span-2"><button type="submit" class="bg-mocha-mauve text-mocha-base font-semibold px-5 py-2.5 rounded-xl hover:brightness-110">Salvar</button></div>
</form>
<table class="w-full bg-mocha-surface0 rounded-2xl overflow-hidden border border-mocha-surface1"><thead class="bg-mocha-surface1 text-left"><tr><th class="px-4 py-3">Nome</th><th>Partido</th><th>Nasc.</th><th>Início</th><th>Fim</th><th>Ações</th></tr></thead><tbody>
<?php $res=mysqli_query($conexao,"SELECT * FROM governantes"); while($r=mysqli_fetch_assoc($res)){ echo '<tr class="border-t border-mocha-surface1"><td class="px-4 py-3">'.htmlspecialchars($r['nome']).'</td><td class="px-4 py-3">'.htmlspecialchars($r['partido_politico']).'</td><td class="px-4 py-3">'.($r['data_nascimento']??'').'</td><td class="px-4 py-3">'.($r['data_inicio_mandato']??'').'</td><td class="px-4 py-3">'.($r['data_fim_mandato']??'').'</td><td class="px-4 py-3 space-x-2"><a href="?id='.$r['id'].'" class="text-mocha-blue hover:underline">Editar</a> <a href="?delete='.$r['id'].'" onclick="return confirm(\'Confirmar exclusão?\');" class="text-mocha-red hover:underline">Excluir</a></td></tr>'; } ?></tbody></table>
</div>
</body>
</html>
