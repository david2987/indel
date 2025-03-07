<div    style="height: 676px;background-color:#D9D9D9">
    <div class="d-flex container">
        <div class="col-md-6">
            <div class="mt-5">
                <img src="<?= URL_HOST ?>/Recursos/imagenes/Logo-footer.png" style='height: 97px;'>
            </div>          
            <div class="seccionShowRoom mt-4" >
                Show Room,<br>
                Administración<br>
                y Ventas:<br>
            </div>
            <div class="seccionFooter mt-4" >
                Constitución N° 850 – Rosario –<br> Santa Fe .  - Rep. Argentina
            </div>
            <div style="border: 1px solid #FE1E1E ;width:70%" class="mt-2"></div>
            <div class="seccionFooter mt-4">
            (0341) 4371007<br>
            (0341) 156913541
            </div>
            <div style="border: 1px solid #FE1E1E ;width:70%" class="mt-2"></div>
            <div class="seccionFooter mt-2">
            info@indelsrl.com.ar  
            </div>
        </div>
        <div class="col-md-6  mt-5">
             <div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3348.3943427162308!2d-60.67399659999999!3d-32.9405966!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95b7ab57c26a4225%3A0xdc92ba7bbc64fd6e!2sIndel%20SRL!5e0!3m2!1ses!2sar!4v1741100349238!5m2!1ses!2sar" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="<?= URL_HOST ?>js/script.js" ></script>
<script>
    $(document).ready(function () {
        $("#empresa").on("click",function(){
            window.location.href = '<?= URL_HOST ?>'
        });

        $("#productos").on("click",function(){
            window.location.href = 'productos.php'
        });

        $("#clientes").on("click",function(){
            window.location.href = 'clientes.php'
        });

        $("#contacto").on("click",function(){
            window.location.href = 'contacto.php'
        });

        $("#noticias").on("click",function(){
            window.location.href = 'noticias.php'
        });
    });
</script>