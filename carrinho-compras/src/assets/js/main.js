const productFilter = document.querySelector("#product-filter");
const productsList = document.querySelector("#products-list");
const cartList = document.querySelector("#cart-list");
const cartEmptyMessage = document.querySelector("#cart-empty-message");
const cartTotalValue = document.querySelector("#cart-total-value");

const products = [
  { nome: "Mouse USB", preco: 25.9 },
  { nome: "Teclado Mecânico", preco: 189.9 },
  { nome: "Headset Gamer", preco: 149.9 },
  { nome: "Mousepad Grande", preco: 39.9 },
  { nome: "Webcam HD", preco: 79.9 },
  { nome: "Cabo HDMI", preco: 29.9 },
  { nome: "Suporte Notebook", preco: 59.9 },
  { nome: "Pen Drive 64GB", preco: 49.9 },
];

let cart = [];

function formatarPreco(preco) {
  return preco.toLocaleString("pt-BR", {
    style: "currency",
    currency: "BRL",
  });
}

function salvarCarrinho() {
  localStorage.setItem("cart", JSON.stringify(cart));
}

function carregarCarrinho() {
  const cartData = localStorage.getItem("cart");
  if (cartData) {
    cart = JSON.parse(cartData);
    renderizarCarrinho();
  }
}

function filtrarProdutos(filtro) {
  switch (filtro) {
    case "ate50":
      return products.filter((p) => p.preco <= 50);
    case "acima50":
      return products.filter((p) => p.preco > 50);
    default:
      return products;
  }
}

function listarProdutos(lista) {
  productsList.innerHTML = "";

  lista.forEach((produto) => {
    const card = document.createElement("section");
    card.className = "product-card";

    const info = document.createElement("div");
    const nome = document.createElement("p");
    nome.className = "product-name";
    nome.textContent = produto.nome;

    const preco = document.createElement("p");
    preco.className = "product-price";
    preco.textContent = formatarPreco(produto.preco);

    info.appendChild(nome);
    info.appendChild(preco);

    const botaoAdicionar = document.createElement("button");
    botaoAdicionar.type = "button";
    botaoAdicionar.className = "btn-action";
    botaoAdicionar.textContent = "Adicionar ao Carrinho";
    botaoAdicionar.addEventListener("click", () => {
      adicionarAoCarrinho(produto.nome);
    });
    card.appendChild(info);
    card.appendChild(botaoAdicionar);
    productsList.appendChild(card);
  });
}

function atualizarTotal() {
  const total = cart.reduce(
    (acc, item) => acc + item.preco * item.quantidade,
    0,
  );
  cartTotalValue.textContent = formatarPreco(total);
}

function atualizarMensagemVazia() {
  cartEmptyMessage.style.display = cart.length === 0 ? "block" : "none";
}

function renderizarCarrinho() {
  cartList.innerHTML = "";

  cart.forEach((item) => {
    const li = document.createElement("li");
    li.className = "cart-item";

    const linhaPrincipal = document.createElement("div");
    linhaPrincipal.className = "cart-item-row";

    const nome = document.createElement("p");
    nome.className = "cart-item-name";
    nome.textContent = item.nome;

    const botaoRemover = document.createElement("button");
    botaoRemover.type = "button";
    botaoRemover.className = "btn-remove";
    botaoRemover.textContent = "Remover";
    botaoRemover.addEventListener("click", () => {
      removerDoCarrinho(item.nome);
    });

    linhaPrincipal.appendChild(nome);
    linhaPrincipal.appendChild(botaoRemover);

    const quantidade = document.createElement("p");
    quantidade.className = "cart-item-meta";
    quantidade.textContent = `Quantidade: ${item.quantidade}`;

    const subtotal = document.createElement("p");
    subtotal.className = "item-subtotal";
    subtotal.textContent = `Subtotal: ${formatarPreco(item.preco * item.quantidade)}`;

    li.appendChild(linhaPrincipal);
    li.appendChild(quantidade);
    li.appendChild(subtotal);
    cartList.appendChild(li);
  });

  atualizarTotal();
  atualizarMensagemVazia();
}

function adicionarAoCarrinho(nomeProduto) {
  const produtoSelecionado = products.find((p) => p.nome === nomeProduto);
  if (!produtoSelecionado) return;

  const itemExistente = cart.find((item) => item.nome === nomeProduto);
  if (itemExistente) {
    itemExistente.quantidade += 1;
  } else {
    cart.push({ ...produtoSelecionado, quantidade: 1 });
  }
  salvarCarrinho();
  renderizarCarrinho();
}

function removerDoCarrinho(nomeProduto) {
  const indiceItem = cart.findIndex((item) => item.nome === nomeProduto);

  if (indiceItem === -1) {
    return;
  }

  if (cart[indiceItem].quantidade > 1) {
    cart[indiceItem].quantidade -= 1;
  } else {
    cart.splice(indiceItem, 1);
  }

  salvarCarrinho();
  renderizarCarrinho();
}

document.addEventListener("DOMContentLoaded", () => {
  carregarCarrinho();
  renderizarCarrinho();

  const filtroAtual = productFilter.value;
  listarProdutos(filtrarProdutos(filtroAtual));

  productFilter.addEventListener("change", (evento) => {
    const produtosFiltrados = filtrarProdutos(evento.target.value);
    listarProdutos(produtosFiltrados);
  });
});
