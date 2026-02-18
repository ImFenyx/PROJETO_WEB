const track = document.querySelector(".carousel-track");
const cards = Array.from(track.children);
cards.forEach((card) => {
  const clone = card.cloneNode(true);
  console.log(clone);
  track.appendChild(clone);
});

console.log(track);
console.log(cards);
