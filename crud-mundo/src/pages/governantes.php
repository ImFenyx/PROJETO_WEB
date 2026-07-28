<?php
require_once '../components/head.php';
require_once '../components/connect.php';

$editMode = false;
$editId = null;
$editNome = '';
$editPartido = '';
$editNascimento = '';
$editInicio = '';
$editFim = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? '';

    if ($action === 'create') {
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
        $partido = mysqli_real_escape_string($conexao, $_POST['partido_politico']);
        $nascimento = $_POST['data_nascimento'] ?: null;
        $inicio = $_POST['data_inicio_mandato'] ?: null;
        $fim = $_POST['data_fim_mandato'] ?: null;

        $sql = "INSERT INTO governantes (nome, partido_politico, data_nascimento, data_inicio_mandato, data_fim_mandato)
                VALUES ('$nome', '$partido', " . ($nascimento ? "'$nascimento'" : "NULL") . ", " . ($inicio ? "'$inicio'" : "NULL") . ", " . ($fim ? "'$fim'" : "NULL") . ")";
        mysqli_query($conexao, $sql);
    } elseif ($action === 'update') {
        $id = (int) $_POST['id'];
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
        $partido = mysqli_real_escape_string($conexao, $_POST['partido_politico']);
        $nascimento = $_POST['data_nascimento'] ?: null;
        $inicio = $_POST['data_inicio_mandato'] ?: null;
        $fim = $_POST['data_fim_mandato'] ?: null;

        $sql = "UPDATE governantes SET
                nome = '$nome',
                partido_politico = '$partido',
                data_nascimento = " . ($nascimento ? "'$nascimento'" : "NULL") . ",
                data_inicio_mandato = " . ($inicio ? "'$inicio'" : "NULL") . ",
                data_fim_mandato = " . ($fim ? "'$fim'" : "NULL") . "
                WHERE id = $id";
        mysqli_query($conexao, $sql);
    } elseif ($action === 'delete') {
        $id = (int) $_POST['id'];
        $check = mysqli_query($conexao, "SELECT id FROM paises WHERE governante_id = $id UNION SELECT id FROM cidades WHERE governante_id = $id");
        if (mysqli_num_rows($check) === 0) {
            mysqli_query($conexao, "DELETE FROM governantes WHERE id = $id");
        } else {
            echo "<script>alert('Não é possível excluir: governante vinculado a um país ou cidade.');</script>";
        }
    }
    header('Location: governantes.php');
    exit;
}

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $result = mysqli_query($conexao, "SELECT * FROM governantes WHERE id = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        $editMode = true;
        $editId = $row['id'];
        $editNome = $row['nome'];
        $editPartido = $row['partido_politico'];
        $editNascimento = $row['data_nascimento'];
        $editInicio = $row['data_inicio_mandato'];
        $editFim = $row['data_fim_mandato'];
    }
}
?>
<title>Governantes</title>
</head>

<body class="min-h-screen bg-linear-to-b from-mocha-base via-mocha-mantle to-mocha-crust font-sans text-mocha-text">
    <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-serif italic text-mocha-mauve sm:text-4xl">Governantes</h1>
            <a href="../../index.php"
                class="text-mocha-subtext0 hover:text-mocha-mauve transition-colors text-sm">&larr; Voltar</a>
        </div>
        <hr class="border-mocha-surface1">

        <form method="POST"
            class="rounded-2xl border border-mocha-surface1 bg-mocha-surface0/70 backdrop-blur-sm">
            <div class="mx-10 my-10 space-y-6">
            <h2 class="text-xl font-serif italic text-mocha-mauve"><?= $editMode ? 'Editar' : 'Novo' ?> Governante</h2>
            <?php if ($editMode): ?>
                <input type="hidden" name="_action" value="update">
                <input type="hidden" name="id" value="<?= $editId ?>">
            <?php else: ?>
                <input type="hidden" name="_action" value="create">
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Nome <span class="text-mocha-red">*</span></label>
                    <input type="text" name="nome" required value="<?= htmlspecialchars($editNome) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Partido Político</label>
                    <input type="text" name="partido_politico" value="<?= htmlspecialchars($editPartido) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" value="<?= htmlspecialchars($editNascimento) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Início do Mandato</label>
                    <input type="date" name="data_inicio_mandato" value="<?= htmlspecialchars($editInicio) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
                <div>
                    <label class="block text-sm text-mocha-subtext0 mb-1">Fim do Mandato</label>
                    <input type="date" name="data_fim_mandato" value="<?= htmlspecialchars($editFim) ?>"
                        class="w-full rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text focus:outline-none focus:border-mocha-mauve transition-colors">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-mocha-mauve text-mocha-base font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all">
                    <?= $editMode ? 'Atualizar' : 'Cadastrar' ?>
                </button>
                <?php if ($editMode): ?>
                    <a href="governantes.php"
                        class="bg-mocha-surface1 text-mocha-text font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all text-center">
                        Cancelar
                    </a>
                <?php endif; ?>
            </div>
            </div>
        </form>

        <div class="rounded-2xl border border-mocha-surface1 bg-mocha-surface0/70 p-8 backdrop-blur-sm overflow-x-auto">
            <h2 class="text-xl font-serif italic text-mocha-mauve mb-4">Lista de Governantes</h2>
            <input type="text" id="searchInput" placeholder="Buscar governante..."
                class="w-full mb-4 rounded-xl border border-mocha-surface1 bg-mocha-base px-4 py-2.5 text-mocha-text placeholder-mocha-overlay0 focus:outline-none focus:border-mocha-mauve transition-colors">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-mocha-subtext0 border-b border-mocha-surface1">
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">Nome</th>
                        <th class="py-3 px-4">Partido</th>
                        <th class="py-3 px-4">Nascimento</th>
                        <th class="py-3 px-4">Idade</th>
                        <th class="py-3 px-4">Início Mandato</th>
                        <th class="py-3 px-4">Fim Mandato</th>
                        <th class="py-3 px-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conexao, "SELECT * FROM governantes ORDER BY nome");
                    while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <tr class="border-b border-mocha-surface1 hover:bg-mocha-surface0/50 transition-colors">
                            <td class="py-3 px-4"><?= $row['id'] ?></td>
                            <td class="py-3 px-4 font-medium"><?= htmlspecialchars($row['nome']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($row['partido_politico'] ?? '-') ?></td>
                            <td class="py-3 px-4"><?= $row['data_nascimento'] ? date('d/m/Y', strtotime($row['data_nascimento'])) : '-' ?></td>
                            <td class="py-3 px-4"><?= $row['idade'] ?? '-' ?></td>
                            <td class="py-3 px-4"><?= $row['data_inicio_mandato'] ? date('d/m/Y', strtotime($row['data_inicio_mandato'])) : '-' ?></td>
                            <td class="py-3 px-4"><?= $row['data_fim_mandato'] ? date('d/m/Y', strtotime($row['data_fim_mandato'])) : '-' ?></td>
                            <td class="py-3 px-4 flex gap-3">
                                <a href="?edit=<?= $row['id'] ?>"
                                    class="text-mocha-blue hover:text-mocha-sapphire transition-colors text-sm">Editar</a>
                                <form method="POST" class="inline" onsubmit="return confirm('Excluir governante?')">
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
