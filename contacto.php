<?php include("template/cabecera_publica.php"); ?>

<div class="header-section text-center mt-4 mb-4">
    <h1 class="display-4" style="color: #800000;">Contacto</h1>
</div>

<div class="container content-section">
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-lg bg-light h-100">
                <div class="card-body">
                    <h2 class="text-center" style="color: #800000;">Contáctanos</h2>
                    <p class="text-justify" style="color: #333;">Si tienes alguna pregunta o comentario, no dudes en ponerte en contacto con nosotros. Puedes utilizar el formulario a continuación o enviarnos un correo electrónico.</p>
                    <form>
                        <div class="form-group">
                            <label for="nombre" style="color: #333;">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Tu nombre">
                        </div>
                        <div class="form-group">
                            <label for="email" style="color: #333;">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" placeholder="Tu correo electrónico">
                        </div>
                        <div class="form-group">
                            <label for="mensaje" style="color: #333;">Mensaje</label>
                            <textarea class="form-control" id="mensaje" rows="5" placeholder="Tu mensaje"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="background-color: #800000; border-color: #800000;">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-lg bg-light h-100">
                <div class="card-body">
                    <h2 class="text-center" style="color: #800000;">Información de Contacto</h2>
                    <p class="text-justify" style="color: #333;"><strong>Dirección:</strong> Calle Falsa 123, Ciudad, País</p>
                    <p class="text-justify" style="color: #333;"><strong>Teléfono:</strong> +123 456 7890</p>
                    <p class="text-justify" style="color: #333;"><strong>Email:</strong> contacto@biblioteca.com</p>
                    <p class="text-justify" style="color: #333;">También puedes encontrarnos en nuestras redes sociales:</p>
                    <div class="text-center">
                        <!-- Añadir URLs de redes sociales dentro de href -->
                        <a href="#" class="btn btn-social-icon btn-facebook" style="background-color: #3b5998; color: white; margin: 5px;" aria-hidden="true"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-social-icon btn-twitter" style="background-color: #1da1f2; color: white; margin: 5px;" aria-hidden="true"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-social-icon btn-instagram" style="background-color: #e4405f; color: white; margin: 5px;" aria-hidden="true"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-social-icon btn-linkedin" style="background-color: #0077b5; color: white; margin: 5px;" aria-hidden="true"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaS3ukQmTktG8f5DpiUibVx3" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIyFEYeDjAxZw8++PpRtW0uChFfYCAaMSFZcUOLO" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<style>
    .header-section {
        background-color: #f8f9fa;
        padding: 40px 0;
    }
    .card {
        border-radius: 8px;
    }
    .card-body {
        padding: 20px;
    }
    h1, h2 {
        font-family: 'Arial', sans-serif;
    }
    h1 {
        font-size: 2.5rem;
        color: #800000;
    }
    h2 {
        font-size: 1.75rem;
        color: #800000;
    }
    p, label {
        font-size: 1rem;
        color: #333;
        text-align: justify;
    }
    .card.bg-light {
        background-color: #f8f9fa !important;
    }
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }
    .form-control {
        border-radius: 4px;
    }
    .btn {
        transition: background-color 0.3s, border-color 0.3s;
    }
    .btn:hover {
        background-color: #660000;
        border-color: #660000;
    }
    .btn-social-icon {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-size: 1.2rem;
    }
    .btn-facebook:hover {
        background-color: #2d4373;
    }
    .btn-twitter:hover {
        background-color: #0c85d0;
    }
    .btn-instagram:hover {
        background-color: #c13584;
    }
    .btn-linkedin:hover {
        background-color: #005582;
    }
</style>
