// assets/js/form_logic.js
document.addEventListener("DOMContentLoaded", function () {
  // =================================================================
  // 1. LÓGICA DO FORMULÁRIO SETOR/LOCAL
  // =================================================================
  const setorSelect = document.getElementById("setor");
  const tipoSelect = document.getElementById("tipo");
  const form = document.querySelector("form");

  // Mapeamento da dependência: Setor -> Opções de Local
  const localOptions = {
    "PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA": ["STRICTO SENSU", "LATO SENSU"],
    "PRO-REITORIA DE GRADUAÇÃO": ["GRADUAÇÃO"],
    "ÁREA TECNOLÓGICA SENAI CIMATEC": ["PDI"],
  };

  /**
   * Atualiza as opções do campo 'Local' (tipoSelect) com base no Setor selecionado.
   * @param {string} initialTipoValue - Valor pré-selecionado (útil para edição ou erro de POST).
   */
  function updateTipoOptions(initialTipoValue = "") {
    const selectedSetor = setorSelect.value;
    const options = localOptions[selectedSetor] || [];

    // 1. Limpa as opções atuais
    tipoSelect.innerHTML = "";

    // 2. Define o valor a ser selecionado (o valor inicial ou vazio)
    let valueToSelect = initialTipoValue;

    // 3. Se o Setor não tiver opções, desabilita o campo e define um valor vazio
    if (options.length === 0 || !selectedSetor) {
      tipoSelect.disabled = true;
      // Para 'PRO-REITORIA DE GRADUAÇÃO', o campo deve ser visualmente desativado
      tipoSelect.innerHTML = '<option value="">Não se aplica</option>';
      tipoSelect.value = "";
      tipoSelect.setCustomValidity(""); // Remove validação
      return;
    }

    // Se há opções, habilita o campo
    tipoSelect.disabled = false;

    // Adiciona a opção padrão
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Selecione o Local";
    defaultOption.disabled = true;
    tipoSelect.appendChild(defaultOption);

    // Adiciona as opções válidas
    options.forEach((option) => {
      const newOption = document.createElement("option");
      newOption.value = option;
      newOption.textContent = option;
      if (option === valueToSelect) {
        newOption.selected = true;
      }
      tipoSelect.appendChild(newOption);
    });

    // Se o valor inicial não for uma das opções válidas, força a seleção para o placeholder
    if (valueToSelect && !options.includes(valueToSelect)) {
      tipoSelect.value = "";
    } else if (valueToSelect) {
      tipoSelect.value = valueToSelect;
    } else {
      tipoSelect.value = ""; // Garante que o placeholder está selecionado
    }
  }

  // --- Event Listeners e Inicialização do Formulário ---

  // 1. Ouve a mudança no Setor para atualizar o Local
  if (setorSelect) {
    setorSelect.addEventListener("change", function () {
      // Quando o Setor muda, limpamos o valor do Local para forçar nova seleção
      updateTipoOptions("");
      if (tipoSelect) tipoSelect.focus();
    });
  }

  // 2. Validação customizada no envio do formulário (apenas se for obrigatório)
  if (form) {
    form.addEventListener("submit", function (e) {
      // O campo 'Local' é obrigatório, exceto se o Setor for "PRO-REITORIA DE GRADUAÇÃO"
      const isTipoRequired =
        setorSelect && setorSelect.value !== "PRO-REITORIA DE GRADUAÇÃO";

      if (isTipoRequired && tipoSelect && tipoSelect.value === "") {
        e.preventDefault();
        e.stopPropagation();
        tipoSelect.setCustomValidity("Por favor, selecione o Local.");
        tipoSelect.reportValidity();
      } else if (tipoSelect) {
        tipoSelect.setCustomValidity(""); // Validação OK
      }
    });
  }

  // 3. Remove a mensagem de erro customizada ao selecionar uma opção
  if (tipoSelect) {
    tipoSelect.addEventListener("change", function () {
      tipoSelect.setCustomValidity("");
    });
  }

  // =================================================================
  // 2. INICIALIZAÇÃO DOS TOOLTIPS DO BOOTSTRAP
  // =================================================================

  // Verifica se a variável global 'bootstrap' está disponível (do bootstrap.bundle.min.js)
  if (typeof bootstrap !== "undefined") {
    // Seleciona todos os elementos que têm o atributo data-bs-toggle="tooltip"
    var tooltipTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );

    // Mapeia a lista e inicializa um novo objeto Tooltip do Bootstrap para cada um
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  }

  // =================================================================
  // 3. VALIDAÇÃO DE FORMULÁRIOS ADICIONAIS
  // =================================================================

  /**
   * Validação de campos monetários
   */
  function inicializarValidacaoMonetaria() {
    const camposMonetarios = document.querySelectorAll(
      'input[type="text"][name*="taxa"], input[type="text"][name*="mensalidade"]'
    );

    camposMonetarios.forEach((campo) => {
      campo.addEventListener("blur", function () {
        const valor = this.value.trim();

        if (
          valor === "" ||
          valor.toLowerCase() === "não se aplica" ||
          valor.toLowerCase() === "nao se aplica"
        ) {
          this.value = "";
          return;
        }

        // Valida formato monetário básico
        const formatoValido = /^(\d{1,3}(\.\d{3})*|\d+)(,\d{1,2})?$/.test(
          valor
        );
        if (!formatoValido && valor !== "") {
          this.setCustomValidity(
            "Por favor, insira um valor válido (ex: 1500,00 ou 1.500,00)"
          );
          this.reportValidity();
        } else {
          this.setCustomValidity("");
        }
      });

      campo.addEventListener("input", function () {
        this.setCustomValidity("");
      });
    });
  }

  // =================================================================
  // 4. SISTEMA DE CAIXA ALTA AUTOMÁTICA
  // =================================================================

  /**
   * Converte campos de texto para CAIXA ALTA automaticamente
   * Exceto campos específicos como descrição, email, etc.
   */
  function inicializarCaixaAlta() {
    const camposParaCaixaAlta = [
      'edital', 'local', 'taxa_inscricao', 'taxa_mensal'
    ];
    
    const camposParaPreservar = [
      'descricao', 'email_responsavel', 'link_inscricao'
    ];
    
    camposParaCaixaAlta.forEach(campoName => {
      const elemento = document.querySelector(`[name="${campoName}"]`);
      if (elemento) {
        // Evento input - converte em tempo real
        elemento.addEventListener('input', function(e) {
          // Preserva a posição do cursor
          const inicio = this.selectionStart;
          const fim = this.selectionEnd;
          
          this.value = this.value.toUpperCase();
          
          // Restaura a posição do cursor
          this.setSelectionRange(inicio, fim);
        });
        
        // Evento blur - converte quando perde o foco (backup)
        elemento.addEventListener('blur', function() {
          this.value = this.value.toUpperCase();
        });
      }
    });
    
    // Para campos que NÃO devem ser convertidos
    camposParaPreservar.forEach(campoName => {
      const elemento = document.querySelector(`[name="${campoName}"]`);
      if (elemento) {
        // Garante que não seja convertido para maiúsculo
        elemento.addEventListener('input', function(e) {
          // Remove qualquer conversão automática do navegador
          if (this.value !== this.value.toLowerCase()) {
            // Preserva o caso original
            const inicio = this.selectionStart;
            const fim = this.selectionEnd;
            this.value = this.value; // Força o valor atual
            this.setSelectionRange(inicio, fim);
          }
        });
      }
    });
    
    console.log('Sistema de caixa alta inicializado');
  }

  // =================================================================
  // 5. INICIALIZAÇÃO COMPLETA DO FORMULÁRIO
  // =================================================================

  /**
   * Inicialização completa do formulário
   */
  function inicializarFormulario() {
    // 1. Lógica de Setor/Local
    if (tipoSelect) {
      const initialTipoValue = tipoSelect.getAttribute("data-initial-tipo") || "";
      updateTipoOptions(initialTipoValue);
    }
    
    // 2. Tooltips Bootstrap
    if (typeof bootstrap !== "undefined") {
      var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
      );
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
    
    // 3. Validação monetária
    inicializarValidacaoMonetaria();
    
    // 4. Sistema de caixa alta
    inicializarCaixaAlta();
    
    console.log("Formulário inicializado com sucesso - Todas as funcionalidades ativas");
  }

  // =================================================================
  // 6. INICIALIZAÇÃO E EXPORTAÇÃO DE FUNÇÕES
  // =================================================================

  // Inicializar o formulário
  inicializarFormulario();

  // Exportar funções para uso global
  window.updateTipoOptions = updateTipoOptions;
  window.inicializarValidacaoMonetaria = inicializarValidacaoMonetaria;
  window.inicializarCaixaAlta = inicializarCaixaAlta;
  window.inicializarFormulario = inicializarFormulario;

  // Log de inicialização para debugging
  console.log("form_logic.js carregado com sucesso - Funcionalidades:");
  console.log("- Lógica de formulário Setor/Local");
  console.log("- Tooltips Bootstrap");
  console.log("- Validação de campos monetários");
  console.log("- Sistema de caixa alta automática");
});