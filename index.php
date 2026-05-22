<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Vá com Deus Passagens Rodoviárias</title>
    <style>
        :root {
            --copel-orange: #F5821E;
            --copel-orange-dark: #E07010;
            --copel-gray: #ef8206;
            --copel-gray-light: #9fa1a4;
            --bg-page: #f8f9fa;
            --bg-card: #ffffff;
            --border-light: #e9ecef;
            --error-bg: #fff3f3;
            --error-border: #dc3545;
            --error-text: #721c24;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-page);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: var(--copel-gray);
        }

        .login-card {
            background: var(--bg-card);
            width: 100%;
            max-width: 420px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(15, 6, 0, 0.1);
            overflow: hidden;
            border-top: 4px solid var(--copel-orange);
        }

        .card-header {
            background: var(--copel-orange);
            color: #fff;
            padding: 1.5rem;
            text-align: center;
        }

        .card-header h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .card-header p {
            font-size: 0.85rem;
            opacity: 0.95;
            margin-top: 0.3rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* ===== ALERTA DE ERRO ===== */
        .alert-error {
            background: var(--error-bg);
            border-left: 5px solid var(--error-border);
            color: var(--error-text);
            padding: 1rem 1.2rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            animation: slideIn 0.3s ease;
        }

        .alert-error::before {
            content: "⚠️";
            font-size: 1.1rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 1.3rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--copel-gray);
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid var(--border-light);
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--copel-orange);
        }

        .btn-entrar,
        .btn-inserir,
        .btn-editar,
        .btn-excluir,
        .btn-relatorio {
            width: 100%;
            padding: 0.9rem;
            background-color: var(--copel-orange);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-entrar:hover,
        .btn-inserir:hover,
        .btn-editar:hover,
        .btn-excluir:hover,
        .btn-relatorio:hover {
            background-color: var(--copel-orange-dark);
        }

        .card-footer {
            text-align: center;
            padding: 1rem;
            font-size: 0.75rem;
            color: var(--copel-gray-light);
            border-top: 1px solid var(--border-light);
            background: #fafbfc;
        }

        @media (max-width: 480px) {
            .card-body {
                padding: 1.5rem;
            }

            .card-header {
                padding: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="card-header">
            <h2>Agência Vá com Deus de Transporte Rodoviário</h2>
            <p>Acesso ao sistema</p>
        </div>
        <div class="card-body">
            <!-- Exibe erro da sessão, se existir -->
            <?php if (isset($_SESSION['erro']) && !empty($_SESSION['erro'])): ?>
                <div class="alert-error">
                    <?php echo htmlspecialchars($_SESSION['erro']); ?>
                </div>
                <?php unset($_SESSION['erro']); // Limpa após exibir ?>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="login">Usuário</label>
                    <input type="text" id="login" name="login" placeholder="Gerente / Consultor de Vendas" autofocus>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="••••••••">
                </div>
                <button type="submit" name="btn-entrar" class="btn-entrar">Entrar</button>
            </form>
        </div>
        <div class="card-footer">
            &copy; <?php echo date('Y'); ?> Vá com Deus Transporte Rodoviário - Todos os direitos reservados.
        </div>
    </div>
</body>

</html>