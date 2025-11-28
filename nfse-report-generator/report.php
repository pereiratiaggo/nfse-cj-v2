<?php
session_start();

$nfseWithConsulta = isset($_SESSION['nfseWithConsulta']) ? $_SESSION['nfseWithConsulta'] : [];
$nfseWithoutConsulta = isset($_SESSION['nfseWithoutConsulta']) ? $_SESSION['nfseWithoutConsulta'] : [];

unset($_SESSION['nfseWithConsulta']);
unset($_SESSION['nfseWithoutConsulta']);

function formatarData($data) {
    $timestamp = strtotime($data);
    return date('d/m/Y', $timestamp);
}

function formatarValor($valor) {
    return number_format($valor, 2);
}

$totalComConsulta = 0;
$totalSemConsulta = 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório NFSe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Relatório NFSe</h1>

        <h2 class="mt-4">NFSe com "Consulta"</h2>
        <table id="nfseComConsulta" class="table table-bordered">
            <thead>
                <tr>
                    <th>Número NFSe</th>
                    <th>Data de Emissão</th>
                    <th>Descrição do Serviço</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($nfseWithConsulta)): ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma NFSe encontrada com "Consulta".</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($nfseWithConsulta as $nfse): ?>
                        <tr>
                            <td><?= htmlspecialchars($nfse['numero']) ?></td>
                            <td><?= formatarData($nfse['dataEmissao']) ?></td>
                            <td><?= htmlspecialchars($nfse['descricao']) ?></td>
                            <td><?= formatarValor($nfse['valor']) ?></td>
                        </tr>
                        <?php $totalComConsulta += $nfse['valor']; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td><strong><?= formatarValor($totalComConsulta) ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <h2 class="mt-4">NFSe sem "Consulta"</h2>
        <table id="nfseSemConsulta" class="table table-bordered">
            <thead>
                <tr>
                    <th>Número NFSe</th>
                    <th>Data de Emissão</th>
                    <th>Descrição do Serviço</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($nfseWithoutConsulta)): ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma NFSe encontrada sem "Consulta".</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($nfseWithoutConsulta as $nfse): ?>
                        <tr>
                            <td><?= htmlspecialchars($nfse['numero']) ?></td>
                            <td><?= formatarData($nfse['dataEmissao']) ?></td>
                            <td><?= htmlspecialchars($nfse['descricao']) ?></td>
                            <td><?= formatarValor($nfse['valor']) ?></td>
                        </tr>
                        <?php $totalSemConsulta += $nfse['valor']; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td><strong><?= formatarValor($totalSemConsulta) ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <hr />
        Total Geral: <strong><?= formatarValor($totalSemConsulta + $totalComConsulta) ?></strong>
        <hr />
        
        <a href="index.php" class="btn btn-primary mt-4">Voltar para o Envio</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>

    <script>
    $(document).ready(function() {
        var tableConfig = {
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        };

        $('#nfseComConsulta').DataTable(tableConfig);
        $('#nfseSemConsulta').DataTable(tableConfig);
    } );
    </script>
</body>
</html>
