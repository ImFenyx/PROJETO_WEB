const track = document.querySelector(".carousel-track");

if (track) {
  const cards = Array.from(track.children);
  cards.forEach((card) => {
    const clone = card.cloneNode(true);
    track.appendChild(clone);
  });
}
