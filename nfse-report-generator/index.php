<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Relatórios NFSe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Gerador de Relatórios NFSe</h1>
        <form action="upload.php" method="post" enctype="multipart/form-data" class="mt-4">
            <div class="form-group">
                <label for="zipFile">Selecione um arquivo ZIP para enviar:</label>
                <input type="file" name="zipFile" id="zipFile" class="form-control-file" accept=".zip" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar e Gerar Relatório</button>
        </form>
    </div>
</body>
</html>
