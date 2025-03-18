document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const nombreInput = form.querySelector('[name="nombre"]');
        const apellidoInput = form.querySelector('[name="apellido"]');
        
        [nombreInput, apellidoInput].forEach(input => {
            if (input) {
                input.addEventListener('input', function() {
                    const valor = this.value.trim();
                    const esValido = /^[a-zA-Z0-9\s]{1,30}$/.test(valor);
                    
                    this.classList.toggle('is-invalid', !esValido);
                    this.classList.toggle('is-valid', esValido);
                });
            }
        });
        
        const documentoInput = form.querySelector('[name="documento_identidad"]');
        const telefonoInput = form.querySelector('[name="telefono"]');
        
        [documentoInput, telefonoInput].forEach(input => {
            if (input) {
                input.addEventListener('input', function() {
                    const valor = this.value.trim();
                    const esValido = /^[0-9]{1,12}$/.test(valor);
                    
                    this.classList.toggle('is-invalid', !esValido);
                    this.classList.toggle('is-valid', esValido);
                });
            }
        });
        
        const correoInput = form.querySelector('[name="correo"]');
        if (correoInput) {
            correoInput.addEventListener('input', function() {
                const valor = this.value.trim();
                const esValido = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(valor) && valor.length <= 100;
                
                this.classList.toggle('is-invalid', !esValido);
                this.classList.toggle('is-valid', esValido);
            });
        }
        
        const passwordInput = form.querySelector('[name="password"]');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const valor = this.value;
                const tieneMayuscula = /[A-Z]/.test(valor);
                const tieneMinuscula = /[a-z]/.test(valor);
                const tieneNumero = /[0-9]/.test(valor);
                const tieneEspecial = /[!@#$%^&*()\-_=+{};:,<.>]/.test(valor);
                const longitudValida = valor.length >= 8 && valor.length <= 16;
                
                const esValido = tieneMayuscula && tieneMinuscula && tieneNumero && tieneEspecial && longitudValida;
                
                this.classList.toggle('is-invalid', !esValido);
                this.classList.toggle('is-valid', esValido);
                
                const feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    let mensaje = 'La contraseña debe tener:';
                    if (!longitudValida) mensaje += '<br>- Entre 8 y 16 caracteres';
                    if (!tieneMayuscula) mensaje += '<br>- Al menos una mayúscula';
                    if (!tieneMinuscula) mensaje += '<br>- Al menos una minúscula';
                    if (!tieneNumero) mensaje += '<br>- Al menos un número';
                    if (!tieneEspecial) mensaje += '<br>- Al menos un carácter especial';
                    
                    feedback.innerHTML = mensaje;
                }
            });
        }
        
        const direccionInput = form.querySelector('[name="direccion"]');
        if (direccionInput) {
            direccionInput.addEventListener('input', function() {
                const valor = this.value.trim();
                const esValido = valor === '' || /^[a-zA-Z0-9\s]{1,50}$/.test(valor);
                
                this.classList.toggle('is-invalid', !esValido);
                this.classList.toggle('is-valid', esValido && valor !== '');
            });
        }
        
        const documentoCedulaInput = form.querySelector('[name="documento_cedula"]');
        if (documentoCedulaInput) {
            documentoCedulaInput.addEventListener('change', function() {
                const archivo = this.files[0];
                const tiposPermitidos = ['application/pdf', 'image/jpeg', 'image/png'];
                const esValido = archivo && tiposPermitidos.includes(archivo.type);
                
                this.classList.toggle('is-invalid', !esValido);
                this.classList.toggle('is-valid', esValido);
            });
        }
    });
}); 