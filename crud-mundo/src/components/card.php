<?php
function renderCard($icon, $title, $description, $link = '#')
{
    ?>
    <section
        class="flex flex-col rounded-2xl border border-mocha-surface1 bg-mocha-surface0/70 p-5 backdrop-blur-sm sm:p-6 transition-all duration-200 ease-in-out group-hover:scale-[0.95] group-hover:opacity-80 hover:scale-[1.05] hover:opacity-100 hover:shadow-xl">

        <div class="mb-2 flex gap-2 items-center">
            <span class="text-2xl"><?php echo htmlspecialchars($icon); ?></span>
            <h2 class="text-2xl font-serif italic text-mocha-mauve"><?php echo htmlspecialchars($title); ?></h2>
        </div>

        <p class="text-mocha-subtext0 mb-6"><?php echo htmlspecialchars($description); ?></p>

        <a href="src/pages/<?php echo htmlspecialchars($link); ?>"
            class="mt-auto w-full md:w-fit text-center bg-mocha-mauve text-mocha-base font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all">
            Gerenciar <?php echo htmlspecialchars($title); ?>
        </a>
    </section>
    <?php
}
?>