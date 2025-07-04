import { Swiper } from "swiper";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
import "swiper/css";

function initSwiper() {
  const categoryTitles = {
    embreagens: "Embreagens",
    filtros: "Filtros",
    freios: "Freios",
    diversas: "Peças Diversas",
    colheitadeira: "Peças de Colheitadeira",
  };

  function updateTitle(swiper) {
    const titleElement = document.querySelector("[data-swiper-title]");

    if (!titleElement || !swiper?.slides?.length) return;

    const activeSlide = swiper.slides[swiper.activeIndex];
    if (!activeSlide) return;

    const category =
      activeSlide.querySelector("[data-category]")?.dataset.category ||
      activeSlide.dataset.category;

    const title = categoryTitles?.[category];
    if (title) {
      titleElement.textContent = title;
    }
  }

  const swiper = new Swiper(".products__swiper-container", {
    modules: [Navigation, Pagination, Autoplay],
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: true,
    },

    navigation: {
      nextEl: ".products__swiper-button-next",
      prevEl: ".products__swiper-button-prev",
    },

    breakpoints: {
      768: {
        slidesPerView: 2,
        slidesPerGroup: 2,
        spaceBetween: 30,
      },
    },
    grabCursor: true,

    on: {
      init: function () {
        updateTitle(this);
      },
      slideChange: function () {
        updateTitle(this);
      },
    },
  });
}

export default initSwiper;
