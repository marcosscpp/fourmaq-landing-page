import triggerEvent from "./modules/maquinas-agricolas/trigger-event";
import SmoothScroll from "./modules/smooth-scroll";

import Form from "./modules/maquinas-agricolas/form";
import Modal from "./modules/modal";
import initHotjar from "./init-hotjar";

new SmoothScroll("a[href^='#']").init();

triggerEvent("PageView");

initHotjar();

const form = new Form(".contact__form");

const modal = new Modal(
  "[data-modal='open']",
  "[data-modal='close']",
  "[data-modal='container']"
).init();

const formInternalLinks = document.querySelectorAll("[href='#contato']");
formInternalLinks.forEach((link) => {
  link.addEventListener("click", (e) => {
    triggerEvent("Clicou no link do formulário");
  });
});
