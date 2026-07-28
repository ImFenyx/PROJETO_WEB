<?php
require_once '../components/head.php';
require_once '../components/connect.php';

$editMode = false;
$editId = null;
$editNome = '';
$editPaisId = '';
$editPopulacao = '';
$editArea = '';
$editGovernanteId = '';
$editClima = '';
$editFundacao = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? '';

    if ($action === 'create') {
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
        $pais_id = $_POST['pais_id'] ? (int) $_POST['pais_id'] : 'NULL';
        $populacao = $_POST['populacao'] ? (int) $_POST['populacao'] : 'NULL';
        $area = $_POST['area_km2'] ? (float) $_POST['area_km2'] : 'NULL';
        $governante_id = $_POST['governante_id'] ? (int) $_POST['governante_id'] : 'NULL';
        $clima = $_POST['clima'] ? "'" . mysqli_real_escape_string($conexao, $_POST['clima']) . "'" : 'NULL';
        $fundacao = $_POST['data_fundacao'] ? "'" . $_POST['data_fundacao'] . "'" : 'NULL';

        $sql = "INSERT INTO cidades (nome, pais_id, populacao, area_km2, governante_id, clima, data_fundacao)
                VALUES ('$nome', $pais_id, $populacao, $area, $governante_id, $clima, $fundacao)";
        mysqli_query($conexao, $sql);
    } elseif ($action === 'update') {
        $id = (int) $_POST['id'];
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
        $pais_id = $_POST['pais_id'] ? (int) $_POST['pais_id'] : 'NULL';
        $populacao = $_POST['populacao'] ? (int) $_POST['populacao'] : 'NULL';
        $area = $_POST['area_km2'] ? (float) $_POST['area_km2'] : 'NULL';
        $governante_id = $_POST['governante_id'] ? (int) $_POST['governante_id'] : 'NULL';
        $clima = $_POST['clima'] ? "'" . mysqli_real_escape_string($conexao, $_POST['clima']) . "'" : 'NULL';
        $fundacao = $_POST['data_fundacao'] ? "'" . $_POST['data_fundacao'] . "'" : 'NULL';

        $sql = "UPDATE cidades SET
                nome = '$nome',
                pais_id = $pais_id,
                populacao = $populacao,
                area_km2 = $area,
                governante_id = $governante_id,
                clima = $clima,
                data_fundacao = $fundacao
                WHERE id = $id";
        mysqli_query($conexao, $sql);
    } elseif ($action === 'delete') {
        $id = (int) $_POST['id'];
        mysqli_query($conexao, "DELETE FROM cidades WHERE id = $id");
    }
    header('Location: cidades.php');
    exit;
}

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $result = mysqli_query($conexao, "SELECT * FROM cidades WHERE id = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        $editMode = true;
        $editId = $row['id'];
        $editNome = $row['nome'];
        $editPaisId = $row['pais_id'];
        $editPopulacao = $row['populacao'];
        $editArea = $row['area_km2'];
        $editGovernanteId = $row['governante_id'];
        $editClima = $row['clima'];
        $editFundacao = $row['data_fundacao'];
    }
}
?>
<title>Cidades</title>
</head>

<body class="min-h-screen bg-linear-to-b from-mocha-base via-mocha-mantle to-mocha-crust font-sans text-mocha-text">
    <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-serif italic text-mocha-mauve sm:text-4xl">Cidades</h1>
            <a href="../../index.php"
                class="text-mocha-subtext0 hover:text-mocha-mauve transition-colors text-sm">&larr; Voltar</a>
        </div>
        <hr class="border-mocha-surface1">

        <form method="POST"
            class="rounded-2xl border border-mocha-surface1 bg-mocha-surface0/70 backdrop-blur-sm">
            <div class="mx-10 my-10 space-y-6">
            <h2 class="text-xl font-serif italic text-mocha-mauve"><?= $editMode ? 'Editar' : 'Nova' ?> Cidade</h2>
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
                    <label class="block text-sm text-mocha-subtext0 mb-1">País</label>
                    <select name="pais_id"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text focus:outline-none focus:border-mocha-mauve transition-colors">
                        <option value="">Selecione...</option>
                        <?php
                        $paises = mysqli_query($conexao, "SELECT id, nome FROM paises ORDER BY nome");
                        while ($p = mysqli_fetch_assoc($paises)):
                            $sel = $editPaisId == $p['id'] ? 'selected' : '';
                            echo "<option value=\"{$p['id']}\" $sel>" . htmlspecialchars($p['nome']) . "</option>";
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
                    <label class="block text-sm text-mocha-subtext0 mb-1">Clima</label>
                    <input type="text" name="clima" value="<?= htmlspecialchars($editClima) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Data de Fundação</label>
                    <input type="date" name="data_fundacao" value="<?= htmlspecialchars($editFundacao) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-mocha-mauve text-mocha-base font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all">
                    <?= $editMode ? 'Atualizar' : 'Cadastrar' ?>
                </button>
                <?php if ($editMode): ?>
                    <a href="cidades.php"
                        class="bg-mocha-surface1 text-mocha-text font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all text-center">
                        Cancelar
                    </a>
                <?php endif; ?>
            </div>
            </div>
        </form>

        <div class="rounded-2xl border border-mocha-surface1 bg-mocha-surface0/70 p-8 backdrop-blur-sm overflow-x-auto">
            <h2 class="text-xl font-serif italic text-mocha-mauve mb-4">Lista de Cidades</h2>
            <input type="text" id="searchInput" placeholder="Buscar cidade..."
                class="w-full mb-4 rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-mocha-subtext0 border-b border-mocha-surface1">
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">Nome</th>
                        <th class="py-3 px-4">País</th>
                        <th class="py-3 px-4">Governante</th>
                        <th class="py-3 px-4">População</th>
                        <th class="py-3 px-4">Área (km²)</th>
                        <th class="py-3 px-4">Clima</th>
                        <th class="py-3 px-4">Fundação</th>
                        <th class="py-3 px-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conexao, "
                        SELECT c.*, p.nome AS pais_nome, g.nome AS governante_nome
                        FROM cidades c
                        LEFT JOIN paises p ON c.pais_id = p.id
                        LEFT JOIN governantes g ON c.governante_id = g.id
                        ORDER BY c.nome
                    ");
                    while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <tr class="border-b border-mocha-surface1 hover:bg-mocha-surface0/50 transition-colors">
                            <td class="py-3 px-4"><?= $row['id'] ?></td>
                            <td class="py-3 px-4 font-medium"><?= htmlspecialchars($row['nome']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['pais_nome'] ?? '-') ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['governante_nome'] ?? '-') ?></td>
                            <td class="py-3 px-4"><?= $row['populacao'] ? number_format($row['populacao'], 0, ',', '.') : '-' ?></td>
                            <td class="py-3 px-4"><?= $row['area_km2'] ? number_format($row['area_km2'], 2, ',', '.') : '-' ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['clima'] ?? '-') ?></td>
                            <td class="py-3 px-4"><?= $row['data_fundacao'] ? date('d/m/Y', strtotime($row['data_fundacao'])) : '-' ?></td>
                            <td class="py-3 px-4 flex gap-3">
                                <a href="?edit=<?= $row['id'] ?>"
                                    class="text-mocha-blue hover:text-mocha-sapphire transition-colors text-sm">Editar</a>
                                <form method="POST" class="inline" onsubmit="return confirm('Excluir cidade?')">
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
