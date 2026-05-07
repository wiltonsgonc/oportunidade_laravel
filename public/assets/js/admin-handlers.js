// Handlers para páginas administrativas

// Visualizar detalhes de auditoria via AJAX
visualizarDetalhes = function(id) {
    fetch(`/admin/auditoria/${id}/detalhes`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        let html = '<div style="text-align: left;">';
        html += `<p><strong>Usuário:</strong> ${data.usuario_nome || 'N/A'}</p>`;
        html += `<p><strong>E-mail:</strong> ${data.usuario_email || 'N/A'}</p>`;
        html += `<p><strong>Ação:</strong> ${data.acao}</p>`;
        html += `<p><strong>Data:</strong> ${data.created_at}</p>`;
        if (data.dados_originais) {
            html += `<p><strong>Dados_originais:</strong><pre>${JSON.stringify(data.dados_originais, null, 2)}</pre></p>`;
        }
        if (data.dados_novos) {
            html += `<p><strong>Dados_novos:</strong><pre>${JSON.stringify(data.dados_novos, null, 2)}</pre></p>`;
        }
        html += '</div>';
        
        Swal.fire({
            title: 'Detalhes da Auditoria',
            html: html,
            icon: 'info',
            width: '600px'
        });
    })
    .catch(() => {
        Swal.fire('Erro!', 'Não foi possível carregar os detalhes.', 'error');
    });
};

// Confirmar restauração de vaga
confirmarRestauracao = function(id, nome) {
    Swal.fire({
        title: 'Restaurar vaga',
        html: `Tem certeza que deseja restaurar a vaga <strong>"${nome}"</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, restaurar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/vagas/${id}/restaurar`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
};

// Confirmar exclusão permanente
confirmarExclusaoPermanente = function(id, nome) {
    Swal.fire({
        title: 'Excluir permanentemente',
        html: `Tem certeza que deseja excluir permanentemente a vaga <strong>"${nome}"</strong>?<br><br><strong style="color: red;">Esta ação não pode ser desfeita!</strong>`,
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
            form.action = `/admin/vagas/${id}/force-delete`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
};

// Inicializar handlers quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Vincular eventos aos botões de visualizar detalhes
    document.querySelectorAll('.btn-visualizar-detalhes').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (id) visualizarDetalhes(id);
        });
    });
    
    // Vincular eventos aos botões de restaurar
    document.querySelectorAll('.btn-restaurar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nome = this.dataset.nome;
            if (id && nome) confirmarRestauracao(id, nome);
        });
    });
    
    // Vincular eventos aos botões de excluir permanentemente
    document.querySelectorAll('.btn-excluir-permanente').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nome = this.dataset.nome;
            if (id && nome) confirmarExclusaoPermanente(id, nome);
        });
    });
});