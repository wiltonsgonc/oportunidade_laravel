// assets/js/upload.js
document.addEventListener('DOMContentLoaded', function() {
    // =================================================================
    // 1. UPLOAD DE ANEXOS COM PROGRESSO
    // =================================================================
    
    const formUploadAnexo = document.getElementById('form-upload-anexo');
    if (formUploadAnexo) {
        formUploadAnexo.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const vagaId = formUploadAnexo.dataset.vagaId;
            
            let progressHtml = `
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mb-2">Enviando arquivo...</p>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                    <p class="progress-text mt-2 text-muted">0%</p>
                </div>
            `;
            
            Swal.fire({
                title: 'Enviando arquivo...',
                html: progressHtml,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    const progressBar = document.querySelector('.progress-bar');
                    const progressText = document.querySelector('.progress-text');
                    if (progressBar) {
                        progressBar.style.width = percentComplete + '%';
                    }
                    if (progressText) {
                        progressText.textContent = percentComplete + '%';
                    }
                }
            });

            xhr.addEventListener('load', function() {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (xhr.status === 200 && data.success) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Anexo adicionado com sucesso!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Erro!',
                            text: data.error || 'Erro ao fazer upload do anexo.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Erro ao processar resposta do servidor.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });

            xhr.addEventListener('error', function() {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao fazer upload do anexo.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });

            xhr.open('POST', `/vagas/${vagaId}/anexo`);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.send(formData);
        });
    }

    // =================================================================
    // 2. UPLOAD DE RETIFICAÇÕES COM PROGRESSO
    // =================================================================
    
    const formUploadRetificacao = document.getElementById('form-upload-retificacao');
    if (formUploadRetificacao) {
        formUploadRetificacao.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const vagaId = formUploadRetificacao.dataset.vagaId;
            
            let progressHtml = `
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mb-2">Enviando arquivo...</p>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                    <p class="progress-text mt-2 text-muted">0%</p>
                </div>
            `;
            
            Swal.fire({
                title: 'Enviando arquivo...',
                html: progressHtml,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    const progressBar = document.querySelector('.progress-bar');
                    const progressText = document.querySelector('.progress-text');
                    if (progressBar) {
                        progressBar.style.width = percentComplete + '%';
                    }
                    if (progressText) {
                        progressText.textContent = percentComplete + '%';
                    }
                }
            });

            xhr.addEventListener('load', function() {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (xhr.status === 200 && data.success) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Retificação adicionada com sucesso!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Erro!',
                            text: data.error || 'Erro ao fazer upload da retificação.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Erro ao processar resposta do servidor.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });

            xhr.addEventListener('error', function() {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao fazer upload da retificação.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });

            xhr.open('POST', `/vagas/${vagaId}/retificacao`);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.send(formData);
        });
    }
});

// =================================================================
// 3. FUNÇÕES GLOBAIS DE EXCLUSÃO
// =================================================================

function excluirAnexo(anexoId) {
    const vagaId = document.getElementById('form-upload-anexo')?.dataset.vagaId;
    if (!vagaId) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    Swal.fire({
        title: 'Confirmar Exclusão',
        text: 'Tem certeza que deseja excluir este anexo? Esta ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/vagas/${vagaId}/anexo/${anexoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: data.message || 'Anexo excluído com sucesso!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Erro!',
                        text: data.error || 'Erro ao excluir anexo.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao excluir anexo.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function excluirRetificacao(retificacaoId) {
    const vagaId = document.getElementById('form-upload-retificacao')?.dataset.vagaId;
    if (!vagaId) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    Swal.fire({
        title: 'Confirmar Exclusão',
        text: 'Tem certeza que deseja excluir esta retificação? Esta ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/vagas/${vagaId}/retificacao/${retificacaoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: data.message || 'Retificação excluída com sucesso!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Erro!',
                        text: data.error || 'Erro ao excluir retificação.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao excluir retificação.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

// Exportar funções para uso global
window.excluirAnexo = excluirAnexo;
window.excluirRetificacao = excluirRetificacao;

console.log('upload.js carregado com sucesso');
