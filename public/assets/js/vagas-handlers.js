// Handlers para páginas de vagas

// Excluir anexo via AJAX
excluirAnexo = function(anexoId) {
    const vagaId = document.getElementById('form-upload-anexo')?.dataset.vagaId;
    if (!vagaId || !confirm('Tem certeza que deseja excluir este anexo?')) return;

    fetch(`/vagas/${vagaId}/anexo/${anexoId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`anexo-${anexoId}`)?.remove();
            Swal.fire('Sucesso!', 'Anexo excluído com sucesso.', 'success');
        } else {
            Swal.fire('Erro!', data.error || 'Erro ao excluir anexo.', 'error');
        }
    })
    .catch(() => {
        Swal.fire('Erro!', 'Erro ao excluir anexo.', 'error');
    });
};

// Excluir retificação via AJAX
excluirRetificacao = function(retificacaoId) {
    const vagaId = document.getElementById('form-upload-retificacao')?.dataset.vagaId;
    if (!vagaId || !confirm('Tem certeza que deseja excluir esta retificação?')) return;

    fetch(`/vagas/${vagaId}/retificacao/${retificacaoId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`retificacao-${retificacaoId}`)?.remove();
            Swal.fire('Sucesso!', 'Retificação excluída com sucesso.', 'success');
        } else {
            Swal.fire('Erro!', data.error || 'Erro ao excluir retificação.', 'error');
        }
    })
    .catch(() => {
        Swal.fire('Erro!', 'Erro ao excluir retificação.', 'error');
    });
};

// Confirmar exclusão de arquivo (edital/resultados)
confirmarExclusaoArquivo = function(url, tipo) {
    Swal.fire({
        title: 'Excluir arquivo?',
        text: `Tem certeza que deseja remover o arquivo ${tipo}? Esta ação não pode ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'DELETE';
            form.action = url;
            
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

// Confirmar exclusão de vaga
confirmarExclusao = function(url, tipo, nome) {
    Swal.fire({
        title: 'Confirmar exclusão',
        html: `Tem certeza que deseja excluir ${tipo} <strong>"${nome}"</strong>?<br>Esta ação não pode ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
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

// Inicializar handlers cuando DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Vincular eventos aos botões de excluir anexo
    document.querySelectorAll('.btn-excluir-anexo').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.anexoId;
            if (id) excluirAnexo(id);
        });
    });
    
    // Vincular eventos aos botões de excluir retificação
    document.querySelectorAll('.btn-excluir-retificacao').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.retificacaoId;
            if (id) excluirRetificacao(id);
        });
    });
    
    // Vincular eventos aos botões de confirmar exclusão de arquivo
    document.querySelectorAll('.btn-confirmar-exclusao-arquivo').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const tipo = this.dataset.tipo;
            if (url && tipo) confirmarExclusaoArquivo(url, tipo);
        });
    });
    
    // Vincular eventos aos botões de exclusão no dashboard
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.dataset.url;
            const nome = this.dataset.edital;
            if (url && nome) confirmarExclusao(url, 'a vaga', nome);
        });
    });
});