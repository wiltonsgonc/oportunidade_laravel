// assets/js/auditoria.js

/**
 * Função para visualizar detalhes da vaga no modal
 */
function visualizarDetalhes(dadosVaga) {
    const modalContent = document.getElementById('detalhesConteudo');
    
    let html = `
        <div class="row">
            <div class="col-md-6">
                <strong>Edital:</strong> ${dadosVaga.edital || 'N/A'}<br>
                <strong>Setor:</strong> ${dadosVaga.setor || 'N/A'}<br>
                <strong>Local:</strong> ${dadosVaga.tipo || 'N/A'}<br>
                <strong>Programa/Curso/Área:</strong> ${dadosVaga.programa_curso_area || 'N/A'}<br>
            </div>
            <div class="col-md-6">
                <strong>Data Limite:</strong> ${dadosVaga.data_limite ? new Date(dadosVaga.data_limite).toLocaleDateString('pt-BR') : 'N/A'}<br>
                <strong>Vagas:</strong> ${dadosVaga.numero_de_vagas || 'N/A'}<br>
                <strong>Taxa de Inscrição:</strong> ${dadosVaga.taxa_inscricao || 'Gratuito'}<br>
                <strong>Mensalidade/Bolsa:</strong> ${dadosVaga.mensalidade_bolsa || 'N/A'}<br>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <strong>Descrição:</strong><br>
                <div class="border p-2 bg-light">${dadosVaga.descricao || 'N/A'}</div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <strong>Email do Responsável:</strong> ${dadosVaga.email_responsavel || 'N/A'}<br>
                <strong>Link de Inscrição:</strong> <a href="${dadosVaga.link_inscricao}" target="_blank">${dadosVaga.link_inscricao || 'N/A'}</a><br>
                <strong>Arquivo do Edital:</strong> ${dadosVaga.arquivo_edital || 'N/A'}<br>
            </div>
        </div>
    `;
    
    modalContent.innerHTML = html;
    
    // Mostrar o modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
    modal.show();
}

/**
 * Função para confirmar restauração de vaga arquivada
 */
function confirmarRestauracao(id, nome) {
    Swal.fire({
        title: 'Confirmar Restauração',
        html: `Tem certeza que deseja restaurar a vaga <strong>"${nome}"</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, restaurar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário dinâmico para envio POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'auditoria.php';
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'restaurar_id';
            idInput.value = id;
            
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

/**
 * Função para confirmar exclusão permanente de vaga arquivada
 */
function confirmarExclusaoPermanente(id, nome) {
    Swal.fire({
        title: 'Excluir Permanentemente',
        html: `Tem certeza que deseja excluir PERMANENTEMENTE a vaga <strong>"${nome}"</strong>?<br><br>
               <span class="text-danger"><strong>ATENÇÃO:</strong> Esta ação é irreversível e não pode ser desfeita!</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir permanentemente!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // CORREÇÃO: Usar o arquivo correto
            window.location.href = `excluir_vaga_permanente.php?id=${id}`;
        }
    });
}

/**
 * Função para inicializar a página de auditoria
 */
function inicializarAuditoria() {
    console.log('Sistema de auditoria inicializado');
    
    // Ativar aba baseada na URL
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab) {
        const tabElement = document.getElementById(`${tab}-tab`);
        if (tabElement) {
            new bootstrap.Tab(tabElement).show();
        }
    }
    
    // Verificar parâmetros de status na URL
    const status = urlParams.get('status');
    
    if (status === 'deleted') {
        Swal.fire({
            title: 'Sucesso!',
            text: 'Vaga excluída permanentemente do sistema.',
            icon: 'success',
            confirmButtonColor: '#198754'
        }).then(() => {
            // Limpar parâmetros da URL
            const novaUrl = window.location.pathname + '?tab=arquivadas';
            window.history.replaceState({}, document.title, novaUrl);
        });
    }
    
    if (status === 'error') {
        Swal.fire({
            title: 'Erro!',
            text: 'Ocorreu um erro ao processar a solicitação.',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        }).then(() => {
            // Limpar parâmetros da URL
            const novaUrl = window.location.pathname + '?tab=arquivadas';
            window.history.replaceState({}, document.title, novaUrl);
        });
    }
    
    if (status === 'restored') {
        Swal.fire({
            title: 'Sucesso!',
            text: 'Vaga restaurada com sucesso.',
            icon: 'success',
            confirmButtonColor: '#198754'
        }).then(() => {
            // Limpar parâmetros da URL
            const novaUrl = window.location.pathname + '?tab=arquivadas';
            window.history.replaceState({}, document.title, novaUrl);
        });
    }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    inicializarAuditoria();
});

// Torna as funções disponíveis globalmente
window.visualizarDetalhes = visualizarDetalhes;
window.confirmarRestauracao = confirmarRestauracao;
window.confirmarExclusaoPermanente = confirmarExclusaoPermanente;
window.inicializarAuditoria = inicializarAuditoria;