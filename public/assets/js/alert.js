function confirmarExclusao(urlDestino, tipoItem, nomeItem) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        html: `Tem certeza que deseja excluir <strong>${tipoItem}</strong>: "${nomeItem}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = urlDestino;
        }
    });
}

function confirmarExclusaoArquivo(url, tipoArquivo) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || "";

    Swal.fire({
        title: 'Confirmar Exclusão',
        html: `Tem certeza que deseja excluir o arquivo do <strong>${tipoArquivo}</strong>?<br>Esta ação não pode ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = url;

            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;

            const methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "DELETE";

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function confirmarExclusaoComCSRF(url, id, tipo, nome) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || "";

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
            const form = document.createElement("form");
            form.method = "POST";
            form.action = url;

            const idInput = document.createElement("input");
            idInput.type = "hidden";
            idInput.name = "id";
            idInput.value = id;

            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;

            form.appendChild(idInput);
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

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

function testarSweetAlert() {
    Swal.fire({
        title: 'SweetAlert2 Funcionando!',
        text: 'O SweetAlert2 está corretamente carregado.',
        icon: 'success'
    });
}

window.confirmarExclusao = confirmarExclusao;
window.confirmarExclusaoArquivo = confirmarExclusaoArquivo;
window.confirmarExclusaoComCSRF = confirmarExclusaoComCSRF;
window.confirmarExclusaoAnexo = confirmarExclusaoAnexo;
window.testarSweetAlert = testarSweetAlert;

console.log('Alert.js carregado com sucesso');
