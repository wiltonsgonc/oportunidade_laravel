// assets/js/admin.js
document.addEventListener('DOMContentLoaded', function() {
    // =================================================================
    // 1. EXCLUSÃO DE USUÁRIOS
    // =================================================================
    
    document.querySelectorAll('.btn-excluir').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const tipo = this.dataset.tipo;
            const nome = this.dataset.nome;
            
            Swal.fire({
                title: 'Confirmar Exclusão',
                html: `Tem certeza que deseja excluir ${tipo}: "<strong>${nome}</strong>"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});

// =================================================================
// 2. FUNÇÕES DE AUDITORIA
// =================================================================

function visualizarDetalhes(id) {
    fetch(`/admin/auditoria/${id}/detalhes`)
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="mb-3">
                    <strong>Tipo de Ação:</strong> ${data.tipo}<br>
                    <strong>Usuário:</strong> ${data.usuario}<br>
                    <strong>Data:</strong> ${data.data}
                </div>
                <h6>Dados da Vaga:</h6>
                <table class="table table-bordered table-sm">
            `;
            
            for (const [key, value] of Object.entries(data.dados || {})) {
                html += `<tr><th>${key}</th><td>${value ?? 'N/A'}</td></tr>`;
            }
            
            html += '</table>';
            
            document.getElementById('detalhesConteudo').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalDetalhes')).show();
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar detalhes.');
        });
}

function confirmarRestauracao(id, nome) {
    Swal.fire({
        title: 'Restaurar Vaga',
        text: `Tem certeza que deseja restaurar a vaga "${nome}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, restaurar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#198754'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('formRestaurar');
            form.action = `/admin/auditoria/restaurar?id=${id}`;
            form.submit();
        }
    });
}

function confirmarExclusaoPermanente(id, nome) {
    Swal.fire({
        title: 'Excluir Permanentemente',
        text: `ATENÇÃO! Esta ação não pode ser desfeita. A vaga "${nome}" será excluída permanentemente.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545',
        dangerMode: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('formExcluirPermanente');
            form.action = `/admin/auditoria/excluir-permanente?id=${id}`;
            form.submit();
        }
    });
}

// Exportar funções para uso global
window.visualizarDetalhes = visualizarDetalhes;
window.confirmarRestauracao = confirmarRestauracao;
window.confirmarExclusaoPermanente = confirmarExclusaoPermanente;

console.log('admin.js carregado com sucesso');
