const passwordInput = document.getElementById('passwordIn');
const togglePassword = document.getElementById('togglePassword');
const eyeIcon = document.getElementById('eyeIcon');

togglePassword.addEventListener('click', function () {
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput.setAttribute('type', type);

  // Göz ikonunu değiştir
  eyeIcon.classList.toggle('bi-eye');
  eyeIcon.classList.toggle('bi-eye-slash');
});


//Screen Size
  const screenSize = document.querySelector("#screenSize");

  sizes = window.screen.width + 'x' + window.screen.height;
  screenSize.value = sizes;