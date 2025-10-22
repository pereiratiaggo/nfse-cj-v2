<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['zipFile'])) {
    $uploadedFile = $_FILES['zipFile'];
    $uploadDir = 'uploads/';
    $nfseWithConsulta = [];
    $nfseWithoutConsulta = [];

    if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($uploadedFile['name']);
        $uploadFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($uploadedFile['tmp_name'], $uploadFilePath)) {
            $zip = new ZipArchive;
            if ($zip->open($uploadFilePath) === TRUE) {
                $tempDir = $uploadDir . 'temp_extr_' . uniqid();
                mkdir($tempDir);
                $zip->extractTo($tempDir);
                $zip->close();

                $files = scandir($tempDir);
                foreach ($files as $file) {
                    if (is_file($tempDir . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) == 'xml') {
                        $xml = simplexml_load_file($tempDir . '/' . $file);
                        if ($xml) {
                            $xml->registerXPathNamespace('n', 'http://www.sped.fazenda.gov.br/nfse');
                            $infNFSe = $xml->xpath('//n:infNFSe');

                            if ($infNFSe) {
                                $numero = (string) $infNFSe[0]->nNFSe;
                                $dataEmissao = (string) $infNFSe[0]->dhProc;
                                $descricao = (string) $infNFSe[0]->DPS->infDPS->serv->cServ->xDescServ;
                                $valor = (string) $infNFSe[0]->valores->vLiq;

                                $nfseData = [
                                    'numero' => $numero,
                                    'dataEmissao' => $dataEmissao,
                                    'descricao' => $descricao,
                                    'valor' => $valor,
                                ];

                                if (stripos($descricao, 'consulta') !== false) {
                                    $nfseWithConsulta[] = $nfseData;
                                } else {
                                    $nfseWithoutConsulta[] = $nfseData;
                                }
                            }
                        }
                    }
                }

                // Clean up temporary files and directory
                $files = glob($tempDir . '/*'); 
                foreach($files as $file){ 
                  if(is_file($file)) {
                    unlink($file); 
                  }
                }
                rmdir($tempDir);
                unlink($uploadFilePath);
            }
        }
    }

    $_SESSION['nfseWithConsulta'] = $nfseWithConsulta;
    $_SESSION['nfseWithoutConsulta'] = $nfseWithoutConsulta;

    header('Location: report.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}
