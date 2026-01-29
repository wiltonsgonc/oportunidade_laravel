/**
 * Função para exibir um diálogo de confirmação SweetAlert2
 * antes de executar uma ação de exclusão.
 * @param {string} urlDestino - A URL para a qual redirecionar (a ação de exclusão no PHP).
 * @param {string} tipoItem - O tipo de item sendo excluído (ex: 'a vaga', 'o usuário').
 * @param {string} nomeItem - O nome específico do item (ex: 'Bolsa de Pós-Graduação', 'admin').
 */
function confirmarExclusao(urlDestino, tipoItem, nomeItem) {
    // Verificar se é admin principal (baseado no email da sessão)
    const isAdminPrincipal = document.body.getAttribute('data-admin-principal') === 'true';
    
    // Lógica diferente para vagas vs usuários
    if (tipoItem.includes('vaga')) {
        // Para vagas: admin principal exclui permanentemente, outros arquivam
        const titulo = isAdminPrincipal ? 'Excluir Permanentemente' : 'Arquivar Vaga';
        const texto = isAdminPrincipal 
            ? `Você realmente deseja excluir PERMANENTEMENTE ${tipoItem}: "${nomeItem}"? Esta ação é irreversível e não pode ser desfeita!`
            : `Você realmente deseja arquivar ${tipoItem}: "${nomeItem}"? A vaga será removida do sistema mas mantida em backup para auditoria.`;
        
        const confirmButtonText = isAdminPrincipal ? 'Sim, Excluir Permanentemente!' : 'Sim, Arquivar!';

        Swal.fire({
            title: titulo,
            text: texto,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', 
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmButtonText, 
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            // Se o usuário clicar em "Sim, Excluir!" ou "Sim, Arquivar!"
            if (result.isConfirmed) {
                // Redireciona para o link de exclusão
                window.location.href = urlDestino;
            }
        });
    } else {
        // Para usuários (e outros tipos): sempre excluir permanentemente
        Swal.fire({
            title: 'Excluir Usuário',
            text: `Você realmente deseja excluir PERMANENTEMENTE ${tipoItem}: "${nomeItem}"? Esta ação é irreversível e não pode ser desfeita!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', 
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, Excluir Permanentemente!', 
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            // Se o usuário clicar em "Sim, Excluir!"
            if (result.isConfirmed) {
                // Redireciona para o link de exclusão
                window.location.href = urlDestino;
            }
        });
    }
}

/**
 * Função de exclusão com CSRF para o dashboard (USANDO FORMULÁRIO DINÂMICO)
 * @param {string} url - URL do endpoint de exclusão
 * @param {string} id - ID do item a ser excluído
 * @param {string} tipo - Tipo do item (ex: 'a vaga')
 * @param {string} nome - Nome do item para exibição
 */
function confirmarExclusaoComCSRF(url, id, tipo, nome) {
    const csrfToken = document.getElementById("csrf_token")
      ? document.getElementById("csrf_token").value
      : "";

    Swal.fire({
        title: "Confirmar Exclusão",
        html: `Tem certeza que deseja excluir ${tipo} <strong>"${nome}"</strong>?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário dinâmico para envio POST com CSRF
            const form = document.createElement("form");
            form.method = "POST";
            form.action = url;

            const idInput = document.createElement("input");
            idInput.type = "hidden";
            idInput.name = "id";
            idInput.value = id;

            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "csrf_token";
            csrfInput.value = csrfToken;

            form.appendChild(idInput);
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

/**
 * Função de exclusão legada (para compatibilidade com outros sistemas)
 * @param {string} url - URL completa com ID
 * @param {string} tipo - Tipo do item
 * @param {string} nome - Nome do item
 */
function confirmarExclusaoLegada(url, tipo, nome) {
    if (typeof Swal === "undefined") {
        if (confirm(`Tem certeza que deseja excluir ${tipo} "${nome}"?`)) {
            window.location.href = url;
        }
        return;
    }

    Swal.fire({
        title: "Confirmar Exclusão",
        html: `Tem certeza que deseja excluir ${tipo} <strong>"${nome}"</strong>?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

/**
 * Função para exibir um diálogo de confirmação SweetAlert2
 * antes de executar uma ação de exclusão no dashboard.
 * @param {string} urlDestino - A URL para a qual redirecionar (a ação de exclusão no PHP).
 * @param {string} tipoItem - O tipo de item sendo excluído (ex: 'a vaga', 'o usuário').
 * @param {string} nomeItem - O nome específico do item (ex: 'Bolsa de Pós-Graduação', 'admin').
 */
function confirmarExclusaoDashboard(urlDestino, tipoItem, nomeItem) {
    // Verificar se é admin principal (baseado no email da sessão)
    const isAdminPrincipal = document.body.getAttribute("data-admin-principal") === "true";

    const titulo = isAdminPrincipal
        ? "Excluir Permanentemente"
        : "Arquivar Vaga";
    const texto = isAdminPrincipal
        ? `Você realmente deseja excluir PERMANENTEMENTE ${tipoItem}: "${nomeItem}"? Esta ação é irreversível e não pode ser desfeita!`
        : `Você realmente deseja arquivar ${tipoItem}: "${nomeItem}"? A vaga será removida do sistema mas mantida em backup para auditoria.`;

    const confirmButtonText = isAdminPrincipal
        ? "Sim, Excluir Permanentemente!"
        : "Sim, Arquivar!";

    if (typeof Swal === "undefined") {
        if (confirm(texto)) {
            window.location.href = urlDestino;
        }
        return;
    }

    Swal.fire({
        title: titulo,
        text: texto,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: confirmButtonText,
        cancelButtonText: "Cancelar",
    }).then((result) => {
        // Se o usuário clicar em "Sim, Excluir!" ou "Sim, Arquivar!"
        if (result.isConfirmed) {
            // Redireciona para o link de exclusão
            window.location.href = urlDestino;
        }
    });
}

/**
 * Cria e submete formulário dinâmico para exclusão com CSRF
 * @param {string} url - URL do endpoint
 * @param {string} id - ID do item
 * @param {string} csrfToken - Token CSRF
 */
function criarFormularioExclusao(url, id, csrfToken) {
    const form = document.createElement("form");
    form.method = "POST";
    form.action = url;
    form.style.display = "none";

    const idInput = document.createElement("input");
    idInput.type = "hidden";
    idInput.name = "id";
    idInput.value = id;

    const csrfInput = document.createElement("input");
    csrfInput.type = "hidden";
    csrfInput.name = "csrf_token";
    csrfInput.value = csrfToken;

    form.appendChild(idInput);
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    form.submit();
}

/**
 * Função específica para exclusão de anexos
 * @param {string} url - URL para exclusão
 * @param {string} nomeArquivo - Nome do arquivo a ser excluído
 */
function confirmarExclusaoAnexo(url, nomeArquivo) {
    Swal.fire({
        title: 'Excluir Anexo',
        html: `Tem certeza que deseja excluir o anexo <strong>"${nomeArquivo}"</strong>?<br>Esta ação não pode ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

/**
 * Função específica para exclusão de retificações
 * @param {string} url - URL para exclusão
 * @param {string} nomeArquivo - Nome do arquivo a ser excluído
 */
function confirmarExclusaoRetificacao(url, nomeArquivo) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: 'Tem certeza que deseja excluir esta retificação? Esta ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

/**
 * Inicializa os event listeners para botões de exclusão de anexos
 * COM VERIFICAÇÃO DE SEGURANÇA - não causa erro se elementos não existirem
 */
function inicializarExclusaoAnexos() {
    const deleteButtons = document.querySelectorAll('.btn-excluir-anexo');
    
    // Verifica se existem botões de exclusão antes de tentar adicionar event listeners
    if (deleteButtons.length === 0) {
        console.log('Nenhum botão de exclusão de anexos encontrado.');
        return;
    }
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            
            // Encontra o nome do arquivo de forma segura
            const row = this.closest('tr');
            if (!row) {
                console.error('Não foi possível encontrar a linha do anexo');
                return;
            }
            
            const firstCell = row.querySelector('td:first-child');
            const nomeArquivo = firstCell ? firstCell.textContent.trim() : 'arquivo';
            
            confirmarExclusaoAnexo(url, nomeArquivo);
        });
    });
}

/**
 * Inicializa os event listeners para botões de exclusão de retificações
 * COM VERIFICAÇÃO DE SEGURANÇA - não causa erro se elementos não existirem
 */
function inicializarExclusaoRetificacoes() {
    const deleteButtons = document.querySelectorAll('.btn-excluir-retificacao');
    
    // Verifica se existem botões de exclusão antes de tentar adicionar event listeners
    if (deleteButtons.length === 0) {
        console.log('Nenhum botão de exclusão de retificações encontrado.');
        return;
    }
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            
            // Encontra o nome do arquivo de forma segura
            const row = this.closest('tr');
            if (!row) {
                console.error('Não foi possível encontrar a linha da retificação');
                return;
            }
            
            const firstCell = row.querySelector('td:first-child');
            const nomeArquivo = firstCell ? firstCell.textContent.trim() : 'retificação';
            
            confirmarExclusaoRetificacao(url, nomeArquivo);
        });
    });
}

/**
 * Função segura para inicializar exclusão de anexos que não causa erros
 * se os elementos não existirem (para usuários padrão)
 */
function inicializarExclusaoAnexosSegura() {
    try {
        inicializarExclusaoAnexos();
    } catch (error) {
        console.warn('Erro ao inicializar exclusão de anexos:', error);
        // Não propaga o erro - função segura para usuários padrão
    }
}

/**
 * Função segura para inicializar exclusão de retificações que não causa erros
 * se os elementos não existirem (para usuários padrão)
 */
function inicializarExclusaoRetificacoesSegura() {
    try {
        inicializarExclusaoRetificacoes();
    } catch (error) {
        console.warn('Erro ao inicializar exclusão de retificações:', error);
        // Não propaga o erro - função segura para usuários padrão
    }
}

/**
 * Inicializa os botões de remover edital e resultados
 * COM VERIFICAÇÃO DE SEGURANÇA - não causa erro se elementos não existirem
 */
function inicializarRemoverAnexos() {
    const deleteButtons = document.querySelectorAll('.btn-excluir-anexo');
    
    // Verifica se existem botões de exclusão antes de tentar adicionar event listeners
    if (deleteButtons.length === 0) {
        console.log('Nenhum botão de exclusão de anexos encontrado.');
        return;
    }
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            const nomeArquivo = this.getAttribute('data-nome') || 'arquivo';
            
            confirmarExclusaoAnexo(url, nomeArquivo);
        });
    });
}

/**
 * Função segura para inicializar remoção de anexos que não causa erros
 * se os elementos não existirem
 */
function inicializarRemoverAnexosSegura() {
    try {
        inicializarRemoverAnexos();
    } catch (error) {
        console.warn('Erro ao inicializar remoção de anexos:', error);
        // Não propaga o erro - função segura
    }
}

/**
 * Inicialização completa do sistema de alertas
 * - Tooltips do Bootstrap
 * - Remoção de anexos
 * - Remoção de retificações
 * - Verificação de dependências
 */
function inicializarSistemaAlertas() {
    // Inicializa tooltips do Bootstrap se disponível
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Inicializa botões de remover anexos
    inicializarRemoverAnexosSegura();
    
    // Inicializa botões de remover retificações
    inicializarExclusaoRetificacoesSegura();
    
    // Verificação de dependências
    if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 não está carregado. Funções de confirmação não estarão disponíveis.');
    }
}

// Torna as funções disponíveis globalmente
window.confirmarExclusaoComCSRF = confirmarExclusaoComCSRF;
window.confirmarExclusao = confirmarExclusao;
window.confirmarExclusaoLegada = confirmarExclusaoLegada;
window.confirmarExclusaoDashboard = confirmarExclusaoDashboard;
window.criarFormularioExclusao = criarFormularioExclusao;
window.confirmarExclusaoAnexo = confirmarExclusaoAnexo;
window.confirmarExclusaoRetificacao = confirmarExclusaoRetificacao;
window.inicializarExclusaoAnexos = inicializarExclusaoAnexos;
window.inicializarExclusaoAnexosSegura = inicializarExclusaoAnexosSegura;
window.inicializarExclusaoRetificacoes = inicializarExclusaoRetificacoes;
window.inicializarExclusaoRetificacoesSegura = inicializarExclusaoRetificacoesSegura;
window.inicializarRemoverAnexos = inicializarRemoverAnexos;
window.inicializarRemoverAnexosSegura = inicializarRemoverAnexosSegura;
window.inicializarSistemaAlertas = inicializarSistemaAlertas;

/**
 * Inicialização segura quando o DOM estiver carregado
 * - Não causa erros se elementos não existirem
 * - Compatível com usuários padrão e administradores
 */
document.addEventListener('DOMContentLoaded', function() {
    // Inicialização segura que não causa erros
    if (typeof inicializarExclusaoAnexosSegura === 'function') {
        inicializarExclusaoAnexosSegura();
    }
    
    // Inicialização de retificações
    if (typeof inicializarExclusaoRetificacoesSegura === 'function') {
        inicializarExclusaoRetificacoesSegura();
    }
    
    // Inicialização do sistema de alertas
    if (typeof inicializarSistemaAlertas === 'function') {
        inicializarSistemaAlertas();
    }
    
    // Verificação adicional para garantir que SweetAlert2 está carregado
    if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 não está carregado. Funções de confirmação não estarão disponíveis.');
    }
});