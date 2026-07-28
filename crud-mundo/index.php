<?php
require_once 'src/components/card.php';
require_once 'src/components/head.php';
?>
<title>CRUD Mundo</title>
</head>

<body class="min-h-screen bg-linear-to-b from-mocha-base via-mocha-mantle to-mocha-crust font-sans text-mocha-text">
    <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">
        <h1 class="text-3xl font-serif italic text-mocha-mauve sm:text-4xl">CRUD Mundo</h1>
        <hr>
        <div class="grid grid-cols-2 gap-4 mt-4 p-4 group">
            <?php renderCard('🏳️', 'Países', 'Visualizar, cadastrar, editar ou remover os países do banco de dados.', 'paises.php'); ?>
            <?php renderCard('🏙️', 'Cidades', 'Visualizar, cadastrar, editar ou remover as cidades do banco de dados.', 'cidades.php'); ?>
            <?php renderCard('🌍', 'Continentes', 'Visualizar, cadastrar, editar ou remover os continentes do banco de dados.', 'continentes.php'); ?>
            <?php renderCard('👥', 'Governantes', 'Visualizar, cadastrar, editar ou remover os governantes do banco de dados.', 'governantes.php'); ?>
        </div>
    </div>
    </div>
</body>

</html>