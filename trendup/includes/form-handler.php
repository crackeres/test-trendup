<?php
error_reporting(E_ERROR);

$site_root = $_SERVER['DOCUMENT_ROOT'];
require_once($site_root . '/wp-load.php');

$error_message = '';
$success_message = '';

if (isset($_POST['upload_file']) && $_FILES['uploaded_file']['error'] == 0) {
    $uploaded_file = $_FILES['uploaded_file'];

    $file_path = $uploaded_file['tmp_name'];

    if (is_readable($file_path)) {
        $file_data = file_get_contents($file_path);

        $lines = explode("----------------------", $file_data);

        global $wpdb;
        $table_name = $wpdb->prefix . 'transactions';

        foreach ($lines as $line) {
            $line = trim($line);

            $parts = explode(" | ", $line);

            if (count($parts) === 3) {
                $date = trim($parts[0]);
                $url = trim($parts[2]);

                $url_parts = parse_url($url);
                parse_str($url_parts['query'], $query_params);

                $invId = isset($query_params['InvId']) ? $query_params['InvId'] : null;
                $outSum = isset($query_params['OutSum']) ? $query_params['OutSum'] : null;

                // Используем регулярное выражение для извлечения signatureValue
                $signatureValue = null;
                if (preg_match('/"SignatureValue"\s*:\s*"?([A-Z0-9]+)"?/i', $line, $matches)) {
                    $signatureValue = $matches[1];
                } elseif (preg_match('/"SignatureValue=([A-Z0-9]+)"/i', $line, $matches)) {
                    $signatureValue = $matches[1];
                }

                $linkField = null;

                // Поиск и вставка "Транзакция #XXXXX"
                if (preg_match('/Транзакция #(\d+)/', $line, $transaction_matches)) {
                    $transactionField = 'Транзакция #' . $transaction_matches[1];
                }

                if (strpos($line, '') !== false) {
                    $linkField = $url;
                }

                if ($signatureValue !== null) {
                    $data_to_insert = array(
                        'date' => $date,
                        'invId' => $invId,
                        'outSum' => $outSum,
                        'signatureValue' => $signatureValue,
                    );

                    if ($transactionField !== null) {
                        $data_to_insert['transactionField'] = $transactionField;
                    }

                    if ($linkField !== null) {
                        $data_to_insert['linkField'] = $linkField;
                    }

                    $wpdb->insert($table_name, $data_to_insert);
                    $success_message = "Данные успешно отправлены.";
                }
            }
        }
    } else {
        $error_message = "Файл не может быть прочитан.";
    }
}

if (!empty($error_message)) {
    echo '<div class="error-message">' . $error_message . '</div>';
}

if (!empty($success_message)) {
    echo '<div class="success-message">' . $success_message . '</div>';
}
