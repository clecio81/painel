<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup e Restauração</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            border: 1px solid #ddd;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #007bff;
        }
        form {
            margin-bottom: 20px;
        }
        button {
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 10px;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
            transform: scale(1.03);
        }
        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            width: 100%;
            box-sizing: border-box;
        }
        .alert {
            padding: 12px;
            margin-top: 15px;
            border-radius: 6px;
            color: #fff;
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
            opacity: 0.9;
        }
        .success {
            background-color: #28a745;
        }
        .error {
            background-color: #dc3545;
        }
    </style>
    <script>
        function showAlert(message, type) {
            const alertBox = document.createElement('div');
            alertBox.className = 'alert ' + type;
            alertBox.textContent = message;
            document.querySelector('.container').appendChild(alertBox);
            setTimeout(() => alertBox.remove(), 5000);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Backup e Restauração</h1>
        
        <!-- Formulário para criar backup -->
        <form method="post" action="">
            <input type="hidden" name="action" value="backup">
            <button type="submit">Criar Backup</button>
        </form>

        <hr>

        <!-- Formulário para restaurar backup -->
        <form method="post" action="">
            <input type="hidden" name="action" value="restore">
            <label for="backup_file">Escolha o arquivo de backup:</label>
            <select name="backup_file" id="backup_file">
                <option value="" disabled selected>Escolha um arquivo para a restauração</option>
           </select>
            <button type="submit">Restaurar</button>
        </form>

        <!-- Mensagem de sucesso ou erro -->
            </div>
</body>
</html>