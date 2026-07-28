<?php
require_once '../components/connect.php';
require_once '../components/head.php';

$id = $_GET['id'] ?? null;
$nome = $populacao = $area_km2 = $total_paises = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $populacao = $_POST['populacao'] ?? null;
    $area_km2 = $_POST['area_km2'] ?? null;
    $total_paises = $_POST['total_paises'] ?? null;

    if ($id) {
        $stmt = mysqli_prepare($conexao, "UPDATE continentes SET nome=?, populacao=?, area_km2=?, total_paises=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sddii", $nome, $populacao, $area_km2, $total_paises, $id);
        mysqli_stmt_execute($stmt);
    } else {
        $stmt = mysqli_prepare($conexao, "INSERT INTO continentes (nome, populacao, area_km2, total_paises) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sddi", $nome, $populacao, $area_km2, $total_paises);
        mysqli_stmt_execute($stmt);
    }
    header("Location: continentes.php");
    exit;
}

if ($id && empty($_POST)) {
    $res = mysqli_query($conexao, "SELECT * FROM continentes WHERE id=$id");
    $row = mysqli_fetch_assoc($res);
    $nome = $row['nome'];
    $populacao = $row['populacao'];
    $area_km2 = $row['area_km2'];
    $total_paises = $row['total_paises'];
}

if (isset($_GET['delete'])) {
    mysqli_query($conexao, "DELETE FROM continentes WHERE id=" . intval($_GET['delete']));
    header("Location: continentes.php");
    exit;
}
?>
<title>Continentes</title>
</head>
<body class="min-h-screen bg-linear-to-b from-mocha-base via-mocha-mantle to-mocha-crust font-sans text-mocha-text">
<div class="mx-auto w-full max-w-5xl px-4 py-8 space-y-8">
<h1 class="text-3xl font-serif italic text-mocha-mauve">Continentes</h1>

<form method="POST" class="grid md:grid-cols-2 gap-4 bg-mocha-surface0 p-6 rounded-2xl border border-mocha-surface1" onsubmit="return confirm('Salvar este continente?');">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div>
        <label class="block text-sm mb-1">Nome</label>
        <input name="nome" value="<?php echo htmlspecialchars($nome); ?>" required class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mocha-mauve">
    </div>
    <div>
        <label class="block text-sm mb-1">População</label>
        <input type="number" name="populacao" value="<?php echo htmlspecialchars($populacao); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mocha-mauve">
    </div>
    <div>
        <label class="block text-sm mb-1">Área (km²)</label>
        <input type="number" step="0.01" name="area_km2" value="<?php echo htmlspecialchars($area_km2); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mocha-mauve">
    </div>
    <div>
        <label class="block text-sm mb-1">Total de Países</label>
        <input type="number" name="total_paises" value="<?php echo htmlspecialchars($total_paises); ?>" class="w-full bg-mocha-base border border-mocha-surface1 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mocha-mauve">
    </div>
    <div class="md:col-span-2">
        <button type="submit" class="bg-mocha-mauve text-mocha-base font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 transition-all">Salvar Continente</button>
    </div>
</form>

<table class="w-full bg-mocha-surface0 rounded-2xl overflow-hidden border border-mocha-surface1">
<thead class="bg-mocha-surface1 text-left">
<tr><th class="px-4 py-3">Nome</th><th class="px-4 py-3">População</th><th class="px-4 py-3">Área</th><th class="px-4 py-3">Países</th><th class="px-4 py-3">Ações</th></tr>
</thead>
<tbody>
<?php
$res = mysqli_query($conexao, "SELECT * FROM continentes");
while ($r = mysqli_fetch_assoc($res)) {
    echo '<tr class="border-t border-mocha-surface1"><td class="px-4 py-3">'.htmlspecialchars($r['nome']).'</td><td class="px-4 py-3">'.($r['populacao'] ?? '').'</td><td class="px-4 py-3">'.($r['area_km2'] ?? '').'</td><td class="px-4 py-3">'.($r['total_paises'] ?? '').'</td><td class="px-4 py-3 space-x-2"><a href="?id='.$r['id'].'" class="text-mocha-blue hover:underline">Editar</a> <a href="?delete='.$r['id'].'" onclick="return confirm(\'Confirmar exclusão?\');" class="text-mocha-red hover:underline">Excluir</a></td></tr>';
}
?>
</tbody>
</table>
</div>
</body>
</html>
