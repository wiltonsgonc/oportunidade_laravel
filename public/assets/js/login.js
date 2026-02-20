// Prevenir múltiplos envios do formulário
document.getElementById("loginForm").addEventListener("submit", function (e) {
  const btn = this.querySelector('button[type="submit"]');
  btn.disabled = true;
  btn.innerHTML = "Entrando...";
});

// Foco no campo de email ao carregar
document.addEventListener('DOMContentLoaded', function() {
  const emailInput = document.getElementById('email');
  if (emailInput) {
    emailInput.focus();
  }
});
