<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="css/cadastro.css">
</head>
<body>
    <section class="container_cadastro">
        <div class="caixa1">
            <h1>Crie sua conta</h1>
            <p>Insira seus dados para se cadastrar no sistema</p>
        </div>

        <div class="caixa2">
            <form action="processa_cadastro.php" method="POST">
                <label>Nome</label>
                <input type="text" name="nome" placeholder="Digite seu nome" required><br>
                
                <label>E-mail</label>
                <input type="email" name="email" placeholder="Digite seu e-mail" required><br>
                
                <label>Senha</label>
                <input type="password" name="senha" placeholder="Digite sua senha" required><br>
                
                <button class="botao_cadastro" type="submit">Cadastrar</button>
            </form>

            <p class="voltar_login">
                Já tem uma conta? <a href="login.php">Faça login</a>
            </p>
        </div>
    </section>
</body>
</html>
