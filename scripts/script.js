import triggerEvent from "./modules/trigger-event";
import enableOptions from "./modules/fetch-select-options";
import initSwiper from "./modules/init-swiper";
import Form from "./modules/form";
import Modal from "./modules/modal";

triggerEvent("PageView");

enableOptions();
initSwiper();

const form = new Form(".contact__form");
const modal = new Modal(
  "[data-modal='open']",
  "[data-modal='close']",
  "[data-modal='container']"
);
