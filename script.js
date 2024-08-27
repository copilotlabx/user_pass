document.getElementById('registrationForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;

    if (password.length < 6) {
        event.preventDefault();
        alert('La contraseña debe tener al menos 6 caracteres.');
    }
    if (id.length == 8) {
        event.preventDefault();
        alert('el número de DNI se compone de 8 dígitos.');
    }

});
