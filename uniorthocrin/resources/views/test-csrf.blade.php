<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste CSRF</title>
</head>
<body>
    <h1>Teste CSRF</h1>
    
    <form action="/test-csrf-post" method="POST">
        @csrf
        <button type="submit">Testar POST com CSRF</button>
    </form>
    
    <div id="result"></div>
    
    <script>
        document.querySelector('form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('/test-csrf-post', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(result, null, 2) + '</pre>';
            } catch (error) {
                document.getElementById('result').innerHTML = '<p style="color: red;">Erro: ' + error.message + '</p>';
            }
        });
    </script>
</body>
</html>
