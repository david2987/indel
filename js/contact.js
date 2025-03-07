$(document).ready(function () {
    
    $(".botonEnviar").click(function (e) { 
        $("#formContact").trigger("submit");    
        e.preventDefault();
        
    });

    $("#formContact").submit(function (e) { 
        var host = window.location; 

        if(!$("#email").val())
        {
          $('.textoEnvioMail').empty().append("* Debe ingresar un Correo electrónico válido");
          return false;
        }

          
          if(!document.getElementById('email').value.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/))
          {
            $('.textoEnvioMail').empty().append("* Debe ingresar un Correo electrónico válido ");
            return false;
          }

      
          if(!$("#nombre").val()){
            $('.textoEnvioMail').empty().append("* Debe ingresar un Nombre ");
            return false;
          }

          if(!$("#apellido").val()){
            $('.textoEnvioMail').empty().append("* Debe ingresar un Apellido ");
            return false;
          }

          
          if(!$("#mensaje").val()){
            $('.textoEnvioMail').empty().append("* Debe ingresar un Mensaje ");
            return false;
          }

          if(!$("#telefono").val()){
            $('.textoEnvioMail').empty().append("* Debe ingresar un Teléfono ");
            return false;
          }

        var formData = {
            nombre: $("#nombre").val(),
            apellido: $("#apellido").val(),
            telefono: $("#telefono").val(),
            ciudad: $("#ciudad").val(),
            mensaje: $("#mensaje").val(),
            email: $("#email").val(),
            empresa: $("#empresa").val(),              
          };
        
          $.ajax({
            type: 'POST',
            url:  'https://gyssoluciones.com.ar/mail/sendMail.php',
            data: formData,            
          }).success(function(data) { 
            console.log(data);
            $('.textoEnvioMail').append(data);
            $("#formContact").attr('id','');
          }).fail(function(errmsg) { 
            console.log("error: ", errmsg); 
            $('.textoEnvioMail').append(data);
          });      
          return false;
        });

        
});