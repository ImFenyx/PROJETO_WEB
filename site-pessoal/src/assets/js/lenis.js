import Lenis from "https://cdn.jsdelivr.net/npm/lenis/dist/lenis.mjs";

const lenis = new Lenis({
  autoRaf: true,
  duration: 1.2,
  touchMultiplier: 2,
  infinite: false,
});

document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", (e) => {
    lenis.scrollTo(anchor.getAttribute("href"));
  });
});

lenis.on("scroll", () => {
  document.querySelectorAll(".section").forEach((section) => {
    const rect = section.getBoundingClientRect();
    const isVisible = rect.top < window.innerHeight * 0.8 && rect.bottom > 0;

    if (isVisible) {
      section.classList.add("visible");
    } else {
      section.classList.remove("visible");
    }
  });
});
