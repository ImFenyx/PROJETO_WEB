<?php
require_once '../components/connect.php';
require_once '../components/head.php';
$id=$_GET['id']??null;$nome=$_POST['nome']??'';$continente=$_POST['continente_id']??null;$populacao=$_POST['populacao']??null;$area=$_POST['area_km2']??null;$idioma=$_POST['idioma']??null;$governante=$_POST['governante_id']??null;$clima=$_POST['clima']??null;$regime=$_POST['regime_politico']??null;$moeda=$_POST['moeda']??null;
if($_SERVER['REQUEST_METHOD']==='POST'){ if($id){ $s=mysqli_prepare($conexao,"UPDATE paises SET nome=?, continente_id=?, populacao=?, area_km2=?, idioma=?, governante_id=?, clima=?, regime_politico=?, moeda=? WHERE id=?"); mysqli_stmt_bind_param($s,"siidsisssi",$nome,$continente,$populacao,$area,$idioma,$governante,$clima,$regime,$moeda,$id); }else{ $s=mysqli_prepare($conexao,"INSERT INTO paises (nome, continente_id, populacao, area_km2, idioma, governante_id, clima, regime_politico, moeda) VALUES (?,?,?,?,?,?,?,?,?)"); mysqli_stmt_bind_param($s,"siidsisss",$nome,$continente,$populacao,$area,$idioma,$governante,$clima,$regime,$moeda); } mysqli_stmt_execute($s); header("Location: paises.php"); exit; }
if($id && empty($_POST)){ $r=mysqli_fetch_assoc(mysqli_query($conexao,"SELECT * FROM paises WHERE id=$id")); $nome=$r['nome']; $continente=$r['continente_id']; $populacao=$r['populacao']; $area=$r['area_km2']; $idioma=$r['idioma']; $governante=$r['governante_id']; $clima=$r['clima']; $regime=$r['regime_politico']; $moeda=$r['moeda']; }
if(isset($_GET['delete'])){ mysqli_query($conexao,"DELETE FROM paises WHERE id=".intval($_GET['delete'])); header("Location: paises.php"); exit; }
$continentes = mysqli_query($conexao,"SELECT id, nome FROM continentes");
$governantes = mysqli_query($conexao,"SELECT id, nome FROM governantes");
?>
<title>Países</title>
</head>
<body class="min-h-screen bg-linear-to-b from-mocha-base via-mocha-mantle to-mocha-crust font-sans text-mocha-text">
<div class="mx-auto max-w-6xl px-4 py-8 space-y-8">
<h1 class="text-3xl font-serif italic text-mocha-mauve">Países</h1>
<form method="POST" class="grid md:grid-cols-4 gap-4 bg-mocha-surface0 p-6 rounded-2xl border border-mocha-surface1" onsubmit="return confirm('Salvar país?');">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div><label class="block text-sm mb-1">Nome</label><input name="nome" value="<?php echo htmlspecialchars($nome); ?>" required class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Continente</label><select name="continente_id" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"><?php while($c=mysqli_fetch_assoc($continentes)){ echo '<option value="'.$c['id'].'" '.($continente==$c['id']?'selected':'').'>'.htmlspecialchars($c['nome']).'</option>'; } ?></select></div>
    <div><label class="block text-sm mb-1">População</label><input type="number" name="populacao" value="<?php echo htmlspecialchars($populacao); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Área (km²)</label><input type="number" step="0.01" name="area_km2" value="<?php echo htmlspecialchars($area); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Idioma</label><input name="idioma" value="<?php echo htmlspecialchars($idioma); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Governante</label><select name="governante_id" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"><option value="">--</option><?php while($g=mysqli_fetch_assoc($governantes)){ echo '<option value="'.$g['id'].'" '.($governante==$g['id']?'selected':'').'>'.htmlspecialchars($g['nome']).'</option>'; } ?></select></div>
    <div><label class="block text-sm mb-1">Clima</label><input name="clima" value="<?php echo htmlspecialchars($clima); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Regime Político</label><input name="regime_politico" value="<?php echo htmlspecialchars($regime); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm mb-1">Moeda</label><input name="moeda" value="<?php echo htmlspecialchars($moeda); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2"></div>
    <div class="md:col-span-4"><button type="submit" class="bg-mocha-mauve text-mocha-base font-semibold px-5 py-2.5 rounded-xl hover:brightness-110">Salvar País</button></div>
</form>
<table class="w-full bg-mocha-surface0 rounded-2xl overflow-hidden border border-mocha-surface1"><thead class="bg-mocha-surface1 text-left"><tr><th class="px-4 py-3">Nome</th><th>Continente</th><th>Pop.</th><th>Área</th><th>Ações</th></tr></thead><tbody>
<?php $res=mysqli_query($conexao,"SELECT p.*, c.nome as cont_nome FROM paises p LEFT JOIN continentes c ON p.continente_id=c.id"); while($r=mysqli_fetch_assoc($res)){ echo '<tr class="border-t border-mocha-surface1"><td class="px-4 py-3">'.htmlspecialchars($r['nome']).'</td><td class="px-4 py-3">'.htmlspecialchars($r['cont_nome']??'').'</td><td class="px-4 py-3">'.($r['populacao']??'').'</td><td class="px-4 py-3">'.($r['area_km2']??'').'</td><td class="px-4 py-3 space-x-2"><a href="?id='.$r['id'].'" class="text-mocha-blue hover:underline">Editar</a> <a href="?delete='.$r['id'].'" onclick="return confirm(\'Confirmar exclusão?\');" class="text-mocha-red hover:underline">Excluir</a></td></tr>'; } ?></tbody></table>
</div>
</body>
</html>
