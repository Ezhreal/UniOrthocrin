<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Aprovado</title>
    <style>
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 32px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        .content {
            padding: 32px;
            color: #374151;
            line-height: 1.6;
        }
        .content p {
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 16px;
        }
        .button-wrapper {
            margin: 32px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            padding: 14px 28px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2), 0 2px 4px -1px rgba(37, 99, 235, 0.1);
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #1d4ed8;
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UniOrthocrin</h1>
        </div>
        <div class="content">
            <p>Olá, <strong>{{ $user->name }}</strong>,</p>
            <p>Temos o prazer de informar que o seu cadastro na plataforma <strong>UniOrthocrin</strong> foi aprovado com sucesso pelo administrador!</p>
            <p>Agora você já pode acessar a plataforma utilizando o seu e-mail (<strong>{{ $user->email }}</strong>) e a senha cadastrada.</p>
            <div class="button-wrapper">
                <a href="{{ route('login') }}" class="btn">Acessar a Plataforma</a>
            </div>
            <p>Se você tiver qualquer dúvida, entre em contato com o nosso suporte.</p>
            <p>Atenciosamente,<br>Equipe UniOrthocrin</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} UniOrthocrin. Todos os direitos reservados.
        </div>
    </div>
</body>
</html>
