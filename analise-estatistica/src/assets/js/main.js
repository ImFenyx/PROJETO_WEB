let btnGerar = document.getElementById("btn-gerar");
let camposAlunos = document.getElementById("campos-alunos");
let btnEnviar = document.getElementById("btn-enviar");
let inputQtde = document.getElementById("qtde_alunos");
let inputTurma = document.getElementById("nome_turma");

if (btnGerar) {
  btnGerar.addEventListener("click", gerarCampos);
}

function gerarCampos() {
  let qtde = parseInt(inputQtde.value);
  let turma = inputTurma.value.trim();

  if (!turma) {
    inputTurma.focus();
    return;
  }

  if (!qtde || qtde < 1 || qtde > 50) {
    inputQtde.focus();
    return;
  }

  camposAlunos.innerHTML = "";

  for (let i = 0; i < qtde; i++) {
    let html = "";
    html +=
      "<div class='rounded-xl border border-mocha-surface1 bg-mocha-mantle p-4'>";
    html +=
      "  <p class='mb-3 text-xs font-bold uppercase tracking-wider text-mocha-overlay1'>Aluno " +
      (i + 1) +
      "</p>";
    html += "  <div class='grid grid-cols-1 gap-3 sm:grid-cols-4'>";

    html += "    <div>";
    html +=
      "      <label class='form-label' for='aluno_nome_" + i + "'>Nome</label>";
    html +=
      "      <input class='form-input' type='text' id='aluno_nome_" +
      i +
      "' name='aluno_nome_" +
      i +
      "' placeholder='Ex.: Ana Silva' required />";
    html += "    </div>";

    html += "    <div>";
    html +=
      "      <label class='form-label' for='aluno_nota1_" +
      i +
      "'>Prova 1</label>";
    html +=
      "      <input class='form-input' type='number' id='aluno_nota1_" +
      i +
      "' name='aluno_nota1_" +
      i +
      "' min='0' max='10' step='0.1' placeholder='0 a 10' required />";
    html += "    </div>";

    html += "    <div>";
    html +=
      "      <label class='form-label' for='aluno_nota2_" +
      i +
      "'>Prova 2</label>";
    html +=
      "      <input class='form-input' type='number' id='aluno_nota2_" +
      i +
      "' name='aluno_nota2_" +
      i +
      "' min='0' max='10' step='0.1' placeholder='0 a 10' required />";
    html += "    </div>";

    html += "    <div>";
    html +=
      "      <label class='form-label' for='aluno_trabalho_" +
      i +
      "'>Trabalho</label>";
    html +=
      "      <input class='form-input' type='number' id='aluno_trabalho_" +
      i +
      "' name='aluno_trabalho_" +
      i +
      "' min='0' max='10' step='0.1' placeholder='0 a 10' required />";
    html += "    </div>";

    html += "  </div>";
    html += "</div>";

    camposAlunos.innerHTML += html;
  }

  camposAlunos.classList.remove("hidden");
  btnEnviar.classList.remove("hidden");
}
