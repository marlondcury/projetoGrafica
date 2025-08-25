<?php require_once '../includes/cabecalho.php'; ?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h1 class="display-4">Fale Conosco</h1>
        <p class="lead">Estamos aqui para ajudar. Envie sua dúvida, crítica ou sugestão.</p>
    </div>

    <div class="row">
        <div class="col-md-5">
            <h4>Informações de Contato</h4>
            <hr>
            <p>
                <strong><i class="fas fa-map-marker-alt"></i> Endereço:</strong><br>
                Av. Fernando Ferrari, 514<br>
                Goiabeiras, Vitória - ES, 29075-910
            </p>
            <p>
                <strong><i class="fas fa-phone"></i> Telefone:</strong><br>
                (27) 3344-5566
            </p>
            <p>
                <strong><i class="fas fa-envelope"></i> E-mail:</strong><br>
                contato@graficarapida.com.br
            </p>
            <p>
                <strong><i class="fas fa-clock"></i> Horário de Funcionamento:</strong><br>
                Segunda a Sexta, das 08:00 às 18:00.
            </p>
        </div>

        <div class="col-md-7">
            <h4>Envie uma Mensagem</h4>
            <hr>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Seu Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Seu E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="assunto" class="form-label">Assunto</label>
                    <input type="text" class="form-control" id="assunto" name="assunto" required>
                </div>
                <div class="mb-3">
                    <label for="mensagem" class="form-label">Mensagem</label>
                    <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required></textarea>
                </div>
                <button class="btn">Enviar Mensagem!</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>