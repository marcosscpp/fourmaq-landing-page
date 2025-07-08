import { validateCompleteName, validatePhone } from "./validate.js";
import debounce from "./debounce.js";

export default class BasicForm {
  constructor(formSelector) {
    this.errorTypes = [
      "valueMissing",
      "typeMismatch",
      "customError",
      "patternMismatch",
      "tooShort",
    ];

    this.errorMessages = {
      nome: {
        valueMissing: "O campo de nome não pode estar vazio.",
        patternMismatch: "Por favor, preencha um nome válido.",
        tooShort: "Por favor, preencha um nome válido.",
      },
      email: {
        valueMissing: "O campo de e-mail não pode estar vazio.",
        typeMismatch: "Por favor, preencha um email válido.",
        tooShort: "Por favor, preencha um email válido.",
      },
      telefone: {
        valueMissing: "O campo de telefone não pode estar vazio.",
        patternMismatch: "Digite um número de telefone válido.",
        tooShort: "O campo de celular não tem dígitos suficientes.",
      },
      estado: {
        valueMissing: "Selecione um estado.",
      },
      cidade: {
        valueMissing: "Selecione uma cidade.",
      },
      preferencia: {
        valueMissing: "O campo de preferência não pode estar vazio.",
      },
      urgencia: {
        valueMissing: "O campo de urgência não pode estar vazio.",
      },
      termos: {
        valueMissing: "Você deve aceitar os termos e condições.",
      },
    };

    this.formElement = document.querySelector(formSelector);
    this.fields = this.formElement.querySelectorAll("input, select");
    this.disableDefaultError();
    this.enabledPhoneMask();
    this.initFields();
    this.initSubmit();
  }

  initSubmit() {
    const form = this.formElement;

    form.querySelector("[type='submit']").addEventListener("click", () => {
      this.fields.forEach((field) => {
        if (field.hasAttribute("required") || field.value.trim().length > 0) {
          this.checkField(field);
        }
      });
    });

    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      const loader = document.querySelector("[data-loader]");
      const errorBox = document.querySelector("[data-error-box]");
      const formData = new FormData(form);
      const submitButton = form.querySelector("[type='submit']");

      // Google UTM Params
      const param = new URLSearchParams(window.location.search);
      param.forEach((value, key) => {
        formData.set(key, value);
      });

      try {
        if (errorBox.classList.contains("active")) {
          errorBox.classList.remove("active");
        }

        submitButton.disabled = true;
        loader.classList.add("active");
        const metaApiResponse = await fetch("../php/pixel-submit.php", {
          method: "POST",
          body: formData,
        });

        const crmResponse = await fetch("../php/submit.php", {
          method: "POST",
          body: formData,
        });

        const result = await crmResponse.json();

        if (result.erro) {
          errorBox.innerText = result.erro;
          errorBox.classList.add("active");
        } else {
          window.open("./obrigado", "_blank");
        }
      } catch (error) {
        errorBox.innerText =
          "Ocorreu um erro ao enviar o formulário. Por favor, tente novamente.";
        errorBox.classList.add("active");
      } finally {
        submitButton.disabled = false;
        loader.classList.remove("active");
      }
    });
  }

  disableDefaultError() {
    this.fields.forEach((field) =>
      field.addEventListener("invalid", (e) => e.preventDefault())
    );
  }

  initFields() {
    this.fields.forEach((field) => {
      const checkFunc = debounce(() => {
        this.checkField(field);
      }, 200);

      if (field.tagName === "INPUT") {
        if (field.type === "checkbox") {
          field.addEventListener("change", (e) => {
            e.preventDefault();
            checkFunc();
          });
        } else {
          field.addEventListener("keyup", (e) => {
            e.preventDefault();
            checkFunc();
          });
        }
      } else if (field.tagName === "SELECT") {
        field.addEventListener("change", (e) => {
          e.preventDefault();
          checkFunc();
        });
      }
    });
  }

  enabledPhoneMask() {
    this.formElement.querySelectorAll("[type='tel']").forEach((tel) => {
      tel.addEventListener("input", (e) => {
        let formattedNumber = e.target.value.replace(/\D/g, "");

        if (formattedNumber.length > 11) {
          formattedNumber = formattedNumber.slice(0, 11);
        }

        if (formattedNumber.length > 2) {
          let formatted = formattedNumber.replace(/^(\d{2})(\d)/g, "($1) $2");
          formatted = formatted.replace(/(\d)(\d{4})$/, "$1-$2");

          formattedNumber = formatted;
        }

        e.target.value = formattedNumber;
      });
    });
  }

  checkField(field) {
    let msg;
    field.setCustomValidity("");
    if (field.name == "nome") {
      validateCompleteName(field, 2, 2);
    } else if (field.name == "telefone") {
      validatePhone(field);
    }
    this.errorTypes.forEach((erro) => {
      if (field.validity[erro]) {
        msg = this.errorMessages[field.name][erro];
        if (erro === "customError") msg = field.validationMessage;
      }
    });

    const inputValidator = field.checkValidity();
    const messageBox = field.parentElement.querySelector("[data-error]");

    if (!inputValidator && !field.disabled) {
      messageBox.innerText = msg;
      messageBox.classList.add("active");
      field.classList.add("error");
    } else {
      messageBox.innerText = "";
      messageBox.classList.remove("active");
      field.classList.remove("error");
    }
  }
}
