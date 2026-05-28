<?php

function calcularMedia($n1, $n2, $trabalho)
{
    $soma = $n1 + $n2 + $trabalho;
    $media = $soma / 3;
    return $media;
}

function calcularRaizDaSoma($n1, $n2, $trabalho)
{
    $soma = $n1 + $n2 + $trabalho;
    $raiz = sqrt($soma);
    return $raiz;
}

function calcularDiferencaAbsoluta($n1, $n2, $trabalho)
{
    $maior = max($n1, $n2, $trabalho);
    $menor = min($n1, $n2, $trabalho);
    $diferenca = abs($maior - $menor);
    return $diferenca;
}

function definirSituacao($media)
{
    if ($media >= 7.0) {
        return "Aprovado";
    } elseif ($media >= 5.0) {
        return "Recuperação";
    } else {
        return "Reprovado";
    }
}

function definirClasseBadge($situacao)
{
    if ($situacao === "Aprovado") {
        return "badge-aprovado";
    } elseif ($situacao === "Recuperação") {
        return "badge-recuperacao";
    } else {
        return "badge-reprovado";
    }
}

function fmt($n)
{
    return number_format($n, 2, ",", ".");
}


$formularioEnviado = false;
$nomeTurma = "";
$qtdeAlunos = 0;
$alunos = [];

$mediaGeral = 0;
$maiorMedia = 0;
$menorMedia = 0;
$aprovados = 0;
$recuperacoes = 0;
$reprovados = 0;
$somaTotalNotas = 0;
$percentualAprovacao = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $formularioEnviado = true;

    $nomeTurma = trim($_POST["nome_turma"]);
    $qtdeAlunos = (int) $_POST["qtde_alunos"];

    for ($i = 0; $i < $qtdeAlunos; $i++) {
        $nome = trim($_POST["aluno_nome_$i"]);
        $nota1 = (float) $_POST["aluno_nota1_$i"];
        $nota2 = (float) $_POST["aluno_nota2_$i"];
        $trabalho = (float) $_POST["aluno_trabalho_$i"];

        $media = calcularMedia($nota1, $nota2, $trabalho);
        $raiz = calcularRaizDaSoma($nota1, $nota2, $trabalho);
        $diffAbs = calcularDiferencaAbsoluta($nota1, $nota2, $trabalho);
        $situacao = definirSituacao($media);

        $alunos[] = [
            "nome" => $nome,
            "nota1" => $nota1,
            "nota2" => $nota2,
            "trabalho" => $trabalho,
            "media" => $media,
            "raiz" => $raiz,
            "diffAbs" => $diffAbs,
            "situacao" => $situacao,
        ];
    }

    $somaMedias = 0;
    $maiorMedia = $alunos[0]["media"];
    $menorMedia = $alunos[0]["media"];

    foreach ($alunos as $aluno) {
        $somaMedias += $aluno["media"];
        $somaTotalNotas += $aluno["nota1"] + $aluno["nota2"] + $aluno["trabalho"];

        if ($aluno["media"] > $maiorMedia) {
            $maiorMedia = $aluno["media"];
        }

        if ($aluno["media"] < $menorMedia) {
            $menorMedia = $aluno["media"];
        }

        if ($aluno["situacao"] === "Aprovado") {
            $aprovados++;
        } elseif ($aluno["situacao"] === "Recuperação") {
            $recuperacoes++;
        } else {
            $reprovados++;
        }
    }

    $mediaGeral = $somaMedias / $qtdeAlunos;
    $percentualAprovacao = ($aprovados / $qtdeAlunos) * 100;
}
?>
<!doctype html>
<html lang="pt-BR" class="bg-mocha-crust">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Análise Estatística de Turma</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@8..144,100..1000&family=IBM+Plex+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./output.css" />
</head>

<body class="min-h-screen bg-linear-to-b from-mocha-base via-mocha-mantle to-mocha-crust font-sans text-mocha-text">

    <?php if ($formularioEnviado): ?>
        <header class="mx-auto w-full max-w-6xl px-4 pt-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif italic text-mocha-mauve sm:text-4xl">
                Relatório — <?php echo htmlspecialchars($nomeTurma) ?>
            </h1>
            <p class="mt-2 text-sm text-mocha-subtext1 sm:text-base">
                Análise estatística completa da turma com <?php echo $qtdeAlunos ?> aluno(s).
            </p>
        </header>

        <main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">

            <section class="panel">
                <div class="panel-header">
                    <h2 id="stats-heading" class="panel-title">Estatísticas da Turma</h2>
                    <p class="panel-description">Resumo geral do desempenho.</p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="stat-card">
                        <span class="stat-value"><?php echo fmt($mediaGeral) ?></span>
                        <span class="stat-label">Média Geral</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value text-mocha-green"><?php echo fmt($maiorMedia) ?></span>
                        <span class="stat-label">Maior Média</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value text-mocha-red"><?php echo fmt($menorMedia) ?></span>
                        <span class="stat-label">Menor Média</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value"><?php echo fmt($somaTotalNotas) ?></span>
                        <span class="stat-label">Soma Total</span>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="stat-card border-mocha-green/30">
                        <span class="stat-value text-mocha-green"><?php echo $aprovados ?></span>
                        <span class="stat-label">Aprovados</span>
                    </div>
                    <div class="stat-card border-mocha-yellow/30">
                        <span class="stat-value text-mocha-yellow"><?php echo $recuperacoes ?></span>
                        <span class="stat-label">Recuperação</span>
                    </div>
                    <div class="stat-card border-mocha-red/30">
                        <span class="stat-value text-mocha-red"><?php echo $reprovados ?></span>
                        <span class="stat-label">Reprovados</span>
                    </div>
                    <div class="stat-card border-mocha-mauve/30">
                        <span class="stat-value text-mocha-mauve"><?php echo fmt($percentualAprovacao) ?>%</span>
                        <span class="stat-label">% Aprovação</span>
                    </div>
                </div>
            </section>

            <section>
                <?php if ($percentualAprovacao >= 70): ?>
                    <div class="alert-success">
                        &#x2714; Excelente! A turma apresenta um índice de aprovação de
                        <?php echo fmt($percentualAprovacao) ?>%.
                        O desempenho geral é satisfatório.
                    </div>
                <?php elseif ($percentualAprovacao >= 50): ?>
                    <div class="alert-warning">
                        &#x26A0; Atenção! O índice de aprovação é de <?php echo fmt($percentualAprovacao) ?>%.
                        A turma precisa de acompanhamento pedagógico.
                    </div>
                <?php else: ?>
                    <div class="alert-danger">
                        &#x2718; Crítico! Apenas <?php echo fmt($percentualAprovacao) ?>% de aprovação.
                        Intervenção urgente é necessária.
                    </div>
                <?php endif; ?>
            </section>

            <section class="panel">
                <div class="panel-header">
                    <h2 id="tabela-heading" class="panel-title">Dados dos Alunos</h2>
                    <p class="panel-description">Resultado individual de cada aluno.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Prova 1</th>
                                <th>Prova 2</th>
                                <th>Trabalho</th>
                                <th>Média</th>
                                <th>√Soma</th>
                                <th>|Maior−Menor|</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos as $idx => $aluno): ?>
                                <tr>
                                    <td class="text-mocha-overlay1"><?php echo $idx + 1 ?></td>
                                    <td class="font-semibold"><?php echo htmlspecialchars($aluno["nome"]) ?></td>
                                    <td><?php echo fmt($aluno["nota1"]) ?></td>
                                    <td><?php echo fmt($aluno["nota2"]) ?></td>
                                    <td><?php echo fmt($aluno["trabalho"]) ?></td>
                                    <td class="font-bold"><?php echo fmt($aluno["media"]) ?></td>
                                    <td><?php echo fmt($aluno["raiz"]) ?></td>
                                    <td><?php echo fmt($aluno["diffAbs"]) ?></td>
                                    <td>
                                        <span class="badge <?php echo definirClasseBadge($aluno["situacao"]) ?>">
                                            <?php echo $aluno["situacao"] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Voltar -->
            <div class="flex justify-center">
                <a href="index.php" class="btn-secondary">Nova Análise</a>
            </div>

        </main>

    <?php else: ?>
        <header class="mx-auto w-full max-w-6xl px-4 pt-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif italic text-mocha-mauve sm:text-4xl">
                Análise Estatística de Turma
            </h1>
            <p class="mt-2 text-sm text-mocha-subtext1 sm:text-base">
                Cadastre os alunos e suas notas para gerar o relatório estatístico completo.
            </p>
        </header>

        <main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

            <section class="panel">
                <div class="panel-header">
                    <h2 id="turma-heading" class="panel-title">Dados da Turma</h2>
                    <p class="panel-description">Informe o nome da turma e a quantidade de alunos.</p>
                </div>

                <form id="form-turma" method="post" action="index.php">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="nome_turma" class="form-label">Nome da Turma</label>
                            <input id="nome_turma" name="nome_turma" type="text" required placeholder="Ex.: 3º DS Mtec-PI"
                                class="form-input" />
                        </div>
                        <div>
                            <label for="qtde_alunos" class="form-label">Quantidade de Alunos</label>
                            <input id="qtde_alunos" name="qtde_alunos" type="number" min="1" max="67" required
                                placeholder="Ex.: 67" class="form-input" />
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" id="btn-gerar" class="btn-primary">
                            Gerar Campos
                        </button>
                    </div>

                    <div id="campos-alunos" class="mt-6 space-y-4 hidden"></div>

                    <div id="btn-enviar" class="mt-6 hidden">
                        <button type="submit" class="btn-primary w-full sm:w-auto">
                            Processar e Gerar Relatório
                        </button>
                    </div>
                </form>
            </section>

        </main>

        <script src="./src/assets/js/main.js"></script>
    <?php endif; ?>

</body>

</html>