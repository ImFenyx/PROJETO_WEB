<?php
require_once '../components/connect.php';
require_once '../components/head.php';
$id=$_GET['id']??null;$nome=$_POST['nome']??'';$pais=$_POST['pais_id']??null;$populacao=$_POST['populacao']??null;$area=$_POST['area_km2']??null;$governante=$_POST['governante_id']??null;$clima=$_POST['clima']??null;$fundacao=$_POST['data_fundacao']??null;
if($_SERVER['REQUEST_METHOD']==='POST'){ if($id){ $s=mysqli_prepare($conexao,"UPDATE cidades SET nome=?, pais_id=?, populacao=?, area_km2=?, governante_id=?, clima=?, data_fundacao=? WHERE id=?"); mysqli_stmt_bind_param($s,"siidsissi",$nome,$pais,$populacao,$area,$governante,$clima,$fundacao,$id); }else{ $s=mysqli_prepare($conexao,"INSERT INTO cidades (nome, pais_id, populacao, area_km2, governante_id, clima, data_fundacao) VALUES (?,?,?,?,?,?,?)"); mysqli_stmt_bind_param($s,"siidsiss",$nome,$pais,$populacao,$area,$governante,$clima,$fundacao); } mysqli_stmt_execute($s); header("Location: cidades.php"); exit; }
if($id && empty($_POST)){ $r=mysqli_fetch_assoc(mysqli_query($conexao,"SELECT * FROM cidades WHERE id=$id")); $nome=$r['nome']; $pais=$r['pais_id']; $populacao=$r['populacao']; $area=$r['area_km2']; $governante=$r['governante_id']; $clima=$r['clima']; $fundacao=$r['data_fundacao']; }
if(isset($_GET['delete'])){ mysqli_query($conexao,"DELETE FROM cidades WHERE id=".intval($_GET['delete'])); header("Location: cidades.php"); exit; }
$paises = mysqli_query($conexao,"SELECT id, nome FROM paises");
$governantes = mysqli_query($conexao,"SELECT id, nome FROM governantes");
?>
<title>Cidades</title>
</head>
<body class="min-h-screen bg-linear-to-b from-mocha-base via-mocha-mantle to-mocha-crust font-sans text-mocha-text">
<div class="mx-auto max-w-6xl px-4 py-8 space-y-8">
<h1 class="text-3xl font-serif italic text-mocha-mauve">Cidades</h1>
<form method="POST" class="grid md:grid-cols-4 gap-4 bg-mocha-surface0 p-6 rounded-2xl border border-mocha-surface1" onsubmit="return confirm('Salvar cidade?');">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div><label class="block text-sm mb-1">Nome</label><input name="nome" value="<?php echo htmlspecialchars($nome); ?>" required class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">País</label><select name="pais_id" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"><?php while($p=mysqli_fetch_assoc($paises)){ echo '<option value="'.$p['id'].'" '.($pais==$p['id']?'selected':'').'>'.htmlspecialchars($p['nome']).'</option>'; } ?></select></div>
    <div><label class="block text-sm mb-1">População</label><input type="number" name="populacao" value="<?php echo htmlspecialchars($populacao); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Área (km²)</label><input type="number" step="0.01" name="area_km2" value="<?php echo htmlspecialchars($area); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Governante</label><select name="governante_id" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"><option value="">--</option><?php while($g=mysqli_fetch_assoc($governantes)){ echo '<option value="'.$g['id'].'" '.($governante==$g['id']?'selected':'').'>'.htmlspecialchars($g['nome']).'</option>'; } ?></select></div>
    <div><label class="block text-sm mb-1">Clima</label><input name="clima" value="<?php echo htmlspecialchars($clima); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Fundação</label><input type="date" name="data_fundacao" value="<?php echo htmlspecialchars($fundacao); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div class="md:col-span-4"><button type="submit" class="bg-mocha-mauve text-mocha-base font-semibold px-5 py-2.5 rounded-xl hover:brightness-110">Salvar Cidade</button></div>
</form>
<table class="w-full bg-mocha-surface0 rounded-2xl overflow-hidden border border-mocha-surface1"><thead class="bg-mocha-surface1 text-left"><tr><th class="px-4 py-3">Nome</th><th>País</th><th>Pop.</th><th>Área</th><th>Ações</th></tr></thead><tbody>
<?php $res=mysqli_query($conexao,"SELECT c.*, p.nome as pais_nome FROM cidades c LEFT JOIN paises p ON c.pais_id=p.id"); while($r=mysqli_fetch_assoc($res)){ echo '<tr class="border-t border-mocha-surface1"><td class="px-4 py-3">'.htmlspecialchars($r['nome']).'</td><td class="px-4 py-3">'.htmlspecialchars($r['pais_nome']??'').'</td><td class="px-4 py-3">'.($r['populacao']??'').'</td><td class="px-4 py-3">'.($r['area_km2']??'').'</td><td class="px-4 py-3 space-x-2"><a href="?id='.$r['id'].'" class="text-mocha-blue hover:underline">Editar</a> <a href="?delete='.$r['id'].'" onclick="return confirm(\'Confirmar exclusão?\');" class="text-mocha-red hover:underline">Excluir</a></td></tr>'; } ?></tbody></table>
</div>
</body>
</html>
