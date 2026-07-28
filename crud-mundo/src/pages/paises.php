<?php
require_once '../components/head.php';
require_once '../components/connect.php';

$editMode = false;
$editId = null;
$editNome = '';
$editContinenteId = '';
$editPopulacao = '';
$editArea = '';
$editIdioma = '';
$editGovernanteId = '';
$editClima = '';
$editRegime = '';
$editMoeda = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? '';

    if ($action === 'create') {
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
        $continente_id = $_POST['continente_id'] ? (int) $_POST['continente_id'] : 'NULL';
        $populacao = $_POST['populacao'] ? (int) $_POST['populacao'] : 'NULL';
        $area = $_POST['area_km2'] ? (float) $_POST['area_km2'] : 'NULL';
        $idioma = $_POST['idioma'] ? "'" . mysqli_real_escape_string($conexao, $_POST['idioma']) . "'" : 'NULL';
        $governante_id = $_POST['governante_id'] ? (int) $_POST['governante_id'] : 'NULL';
        $clima = $_POST['clima'] ? "'" . mysqli_real_escape_string($conexao, $_POST['clima']) . "'" : 'NULL';
        $regime = $_POST['regime_politico'] ? "'" . mysqli_real_escape_string($conexao, $_POST['regime_politico']) . "'" : 'NULL';
        $moeda = $_POST['moeda'] ? "'" . mysqli_real_escape_string($conexao, $_POST['moeda']) . "'" : 'NULL';

        $sql = "INSERT INTO paises (nome, continente_id, populacao, area_km2, idioma, governante_id, clima, regime_politico, moeda)
                VALUES ('$nome', $continente_id, $populacao, $area, $idioma, $governante_id, $clima, $regime, $moeda)";
        mysqli_query($conexao, $sql);
    } elseif ($action === 'update') {
        $id = (int) $_POST['id'];
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
        $continente_id = $_POST['continente_id'] ? (int) $_POST['continente_id'] : 'NULL';
        $populacao = $_POST['populacao'] ? (int) $_POST['populacao'] : 'NULL';
        $area = $_POST['area_km2'] ? (float) $_POST['area_km2'] : 'NULL';
        $idioma = $_POST['idioma'] ? "'" . mysqli_real_escape_string($conexao, $_POST['idioma']) . "'" : 'NULL';
        $governante_id = $_POST['governante_id'] ? (int) $_POST['governante_id'] : 'NULL';
        $clima = $_POST['clima'] ? "'" . mysqli_real_escape_string($conexao, $_POST['clima']) . "'" : 'NULL';
        $regime = $_POST['regime_politico'] ? "'" . mysqli_real_escape_string($conexao, $_POST['regime_politico']) . "'" : 'NULL';
        $moeda = $_POST['moeda'] ? "'" . mysqli_real_escape_string($conexao, $_POST['moeda']) . "'" : 'NULL';

        $sql = "UPDATE paises SET
                nome = '$nome',
                continente_id = $continente_id,
                populacao = $populacao,
                area_km2 = $area,
                idioma = $idioma,
                governante_id = $governante_id,
                clima = $clima,
                regime_politico = $regime,
                moeda = $moeda
                WHERE id = $id";
        mysqli_query($conexao, $sql);
    } elseif ($action === 'delete') {
        $id = (int) $_POST['id'];
        $check = mysqli_query($conexao, "SELECT id FROM cidades WHERE pais_id = $id");
        if (mysqli_num_rows($check) === 0) {
            mysqli_query($conexao, "DELETE FROM paises WHERE id = $id");
        } else {
            echo "<script>alert('Não é possível excluir: país possui cidades vinculadas.');</script>";
        }
    }
    header('Location: paises.php');
    exit;
}

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $result = mysqli_query($conexao, "SELECT * FROM paises WHERE id = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        $editMode = true;
        $editId = $row['id'];
        $editNome = $row['nome'];
        $editContinenteId = $row['continente_id'];
        $editPopulacao = $row['populacao'];
        $editArea = $row['area_km2'];
        $editIdioma = $row['idioma'];
        $editGovernanteId = $row['governante_id'];
        $editClima = $row['clima'];
        $editRegime = $row['regime_politico'];
        $editMoeda = $row['moeda'];
    }
}
?>
<title>Países</title>
</head>

<body class="min-h-screen bg-linear-to-b from-mocha-base via-mocha-mantle to-mocha-crust font-sans text-mocha-text">
    <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-serif italic text-mocha-mauve sm:text-4xl">Países</h1>
            <a href="../../index.php"
                class="text-mocha-subtext0 hover:text-mocha-mauve transition-colors text-sm">&larr; Voltar</a>
        </div>
        <hr class="border-mocha-surface1">

        <form method="POST"
            class="rounded-2xl border border-mocha-surface1 bg-mocha-surface0/70 backdrop-blur-sm">
            <div class="mx-10 my-10 space-y-6">
            <h2 class="text-xl font-serif italic text-mocha-mauve"><?= $editMode ? 'Editar' : 'Novo' ?> País</h2>
            <?php if ($editMode): ?>
                <input type="hidden" name="_action" value="update">
                <input type="hidden" name="id" value="<?= $editId ?>">
            <?php else: ?>
                <input type="hidden" name="_action" value="create">
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Nome <span class="text-mocha-red">*</span></label>
                    <input type="text" name="nome" required value="<?= htmlspecialchars($editNome) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Continente</label>
                    <select name="continente_id"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text focus:outline-none focus:border-mocha-mauve transition-colors">
                        <option value="">Selecione...</option>
                        <?php
                        $continentes = mysqli_query($conexao, "SELECT id, nome FROM continentes ORDER BY nome");
                        while ($c = mysqli_fetch_assoc($continentes)):
                            $sel = $editContinenteId == $c['id'] ? 'selected' : '';
                            echo "<option value=\"{$c['id']}\" $sel>" . htmlspecialchars($c['nome']) . "</option>";
                        endwhile;
                        ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Governante</label>
                    <select name="governante_id"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text focus:outline-none focus:border-mocha-mauve transition-colors">
                        <option value="">Selecione...</option>
                        <?php
                        $governantes = mysqli_query($conexao, "SELECT id, nome FROM governantes ORDER BY nome");
                        while ($g = mysqli_fetch_assoc($governantes)):
                            $sel = $editGovernanteId == $g['id'] ? 'selected' : '';
                            echo "<option value=\"{$g['id']}\" $sel>" . htmlspecialchars($g['nome']) . "</option>";
                        endwhile;
                        ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">População</label>
                    <input type="number" name="populacao" min="0" value="<?= htmlspecialchars($editPopulacao) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Área (km²)</label>
                    <input type="number" name="area_km2" min="0" step="0.01" value="<?= htmlspecialchars($editArea) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Idioma</label>
                    <input type="text" name="idioma" value="<?= htmlspecialchars($editIdioma) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Clima</label>
                    <input type="text" name="clima" value="<?= htmlspecialchars($editClima) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Regime Político</label>
                    <input type="text" name="regime_politico" value="<?= htmlspecialchars($editRegime) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Moeda</label>
                    <input type="text" name="moeda" value="<?= htmlspecialchars($editMoeda) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-mocha-mauve text-mocha-base font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all">
                    <?= $editMode ? 'Atualizar' : 'Cadastrar' ?>
                </button>
                <?php if ($editMode): ?>
                    <a href="paises.php"
                        class="bg-mocha-surface1 text-mocha-text font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all text-center">
                        Cancelar
                    </a>
                <?php endif; ?>
            </div>
            </div>
        </form>

        <div class="rounded-2xl border border-mocha-surface1 bg-mocha-surface0/70 p-8 backdrop-blur-sm overflow-x-auto">
            <h2 class="text-xl font-serif italic text-mocha-mauve mb-4">Lista de Países</h2>
            <input type="text" id="searchInput" placeholder="Buscar país..."
                class="w-full mb-4 rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-mocha-subtext0 border-b border-mocha-surface1">
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">Nome</th>
                        <th class="py-3 px-4">Continente</th>
                        <th class="py-3 px-4">Governante</th>
                        <th class="py-3 px-4">População</th>
                        <th class="py-3 px-4">Área (km²)</th>
                        <th class="py-3 px-4">Idioma</th>
                        <th class="py-3 px-4">Clima</th>
                        <th class="py-3 px-4">Regime</th>
                        <th class="py-3 px-4">Moeda</th>
                        <th class="py-3 px-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conexao, "
                        SELECT p.*, c.nome AS continente_nome, g.nome AS governante_nome
                        FROM paises p
                        LEFT JOIN continentes c ON p.continente_id = c.id
                        LEFT JOIN governantes g ON p.governante_id = g.id
                        ORDER BY p.nome
                    ");
                    while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <tr class="border-b border-mocha-surface1 hover:bg-mocha-surface0/50 transition-colors">
                            <td class="py-3 px-4"><?= $row['id'] ?></td>
                            <td class="py-3 px-4 font-medium"><?= htmlspecialchars($row['nome']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['continente_nome'] ?? '-') ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['governante_nome'] ?? '-') ?></td>
                            <td class="py-3 px-4"><?= $row['populacao'] ? number_format($row['populacao'], 0, ',', '.') : '-' ?></td>
                            <td class="py-3 px-4"><?= $row['area_km2'] ? number_format($row['area_km2'], 2, ',', '.') : '-' ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['idioma'] ?? '-') ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['clima'] ?? '-') ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['regime_politico'] ?? '-') ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['moeda'] ?? '-') ?></td>
                            <td class="py-3 px-4 flex gap-3">
                                <a href="?edit=<?= $row['id'] ?>"
                                    class="text-mocha-blue hover:text-mocha-sapphire transition-colors text-sm">Editar</a>
                                <form method="POST" class="inline" onsubmit="return confirm('Excluir país? Todas as cidades vinculadas também serão excluídas.')">
                                    <input type="hidden" name="_action" value="delete">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="text-mocha-red hover:text-mocha-maroon transition-colors text-sm">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
