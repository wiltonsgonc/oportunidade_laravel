document.addEventListener('DOMContentLoaded', function() {
    const statusButtons = document.querySelectorAll('.btn-group a');
    statusButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('lista-vagas').innerHTML = data;
                });
        });
    });
});
