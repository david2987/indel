<div class="container" style="margin-top: 46px;margin-bottom:126px">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <h2 class="tituloContacto mb-4">Formulario de Consulta</h2>
            <p class="textoContacto mb-4">Complete el siguiente formulario y nos pondremos en contacto con usted a la brevedad.</p>
            
            <form id="formContacto" method="POST" action="enviar_contacto.php" class="formularioContacto">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombreCliente" class="labelFormulario">Nombre Cliente <span class="text-danger">*</span></label>
                        <input type="text" class="form-control inputFormulario" id="nombreCliente" name="nombreCliente" required>
                        <div class="error-message" id="errorNombreCliente"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nombreEmpresa" class="labelFormulario">Nombre de la Empresa <span class="text-danger"></span></label>
                        <input type="text" class="form-control inputFormulario" id="nombreEmpresa" name="nombreEmpresa" >
                        <div class="error-message" id="errorNombreEmpresa"></div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="celular" class="labelFormulario">Tel. Movil <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control inputFormulario" id="celular" name="celular" required>
                        <div class="error-message" id="errorCelular"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="labelFormulario">Email <span class="text-danger"></span></label>
                        <input type="email" class="form-control inputFormulario" id="email" name="email" >
                        <div class="error-message" id="errorEmail"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="mensaje" class="labelFormulario">Mensaje <span class="text-danger">*</span></label>
                    <textarea class="form-control inputFormulario" id="mensaje" name="mensaje" rows="5" required><?= 
                    isset($_GET['mensaje']) ? "Estoy interesado en ".htmlspecialchars($_GET['mensaje']) : '';?></textarea>
                    <div class="error-message" id="errorMensaje"></div>
                </div>
                
               
                <div class="mb-3">
                    <div class="textoEnvioMail" id="mensajeRespuesta"></div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btnEnviarFormulario botonEnviarContacto">
                        <span id="textoBoton">Enviar Consulta</span>
                        <span id="spinnerBoton" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?= URL_HOST ?>js/contacto.js"></script>

