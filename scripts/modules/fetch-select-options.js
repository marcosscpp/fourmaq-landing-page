function enableOptions() {
  fetchStates();
  const stateSelect = document.querySelector("[data-select='state']");
  stateSelect.addEventListener("change", (e) => {
    const stateUF = e.target.options[e.target.selectedIndex].getAttribute("data-uf");
    console.log(stateUF);
    fetchCities(stateUF);
  });
}

function fetchCities(stateUF) {
  const brazilCitiesApiUrl = `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${stateUF}/municipios`;
  const citySelect = document.querySelector("[data-select='city']");

  fetch(brazilCitiesApiUrl)
    .then((response) => response.json())
    .then((data) => {
      const defaultOption =
        '<option value="" disabled selected>Cidade</option>';
      citySelect.innerHTML =
        defaultOption +
        data
          .sort((a, b) => a.nome.localeCompare(b.nome))
          .map((city) => `<option value="${city.nome}">${city.nome}</option>`)
          .join("");
    })
    .catch((error) => {
      console.error("Erro ao buscar cidades:", error);
    });
}

function fetchStates() {
  const brazilStatesApiUrl =
    "https://servicodados.ibge.gov.br/api/v1/localidades/estados";
  const stateSelect = document.querySelector("[data-select='state']");

  fetch(brazilStatesApiUrl)
    .then((response) => response.json())
    .then((data) => {
      const defaultOption =
        '<option value="" disabled selected>Estado</option>';
      stateSelect.innerHTML =
        defaultOption +
        data
          .sort((a, b) => a.nome.localeCompare(b.nome))
          .map(
            (state) =>
              `<option value="${state.nome}" data-uf="${state.sigla}">${state.nome}</option>`
          )
          .join("");
    })
    .catch((error) => {
      console.error("Erro ao buscar estados:", error);
    });
}

export default enableOptions;
