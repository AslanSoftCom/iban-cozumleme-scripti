<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 500</title>
    <style>
        html,
        body {
            background: #f8f9fa;
            width: 100%;
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            margin: 0px;
            padding: 0px;
            color: #212529;
        }
        .page-row {
            display: flex;
            flex-wrap: wrap;
            height: 100%;
            width: 100%;
            align-items: center;
            text-align: center;
            justify-content: center;
            padding: 20px 0;
        }
        .page-content {
            width: 100%;
            max-width: 800px;
            text-align: center;
            margin-top: auto;
            margin-bottom: auto;
        }
        .page-title {
            font-size: 150px;
            margin-top: -100px;
        }
        .page-description {
            color: #868e96;
            font-size: 16px;
        }
        .page-description p {
            padding: 0px;
            margin: 0px;
        }
        .page-link {
            margin-top: 10px;
        }
        .error-message, .error-details, .error-trace {
            text-align: left;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .error-message {
            color: #dc3545;
            font-size: 18px;
            font-weight: bold;
        }
        .error-details {
            font-size: 16px;
            color: #343a40;
        }
        .error-trace {
            font-size: 14px;
            color: #495057;
            overflow: auto;
        }
        @media (max-width: 768px) {
            .page-title {
                font-size: 100px;
                margin-top: -70px;
            }
        }
    </style>
</head>
<body>
    <div class="page-row">
        <div class="page-content">
            <div class="page-title">
                500
            </div>
            <div class="page-description">
                <p>An internal server error occurred</p>
            </div>
            <div class="error-message">
                <strong>Hata Mesajı:</strong> <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="error-details">
                <strong>Hata Yolu:</strong> <?php echo htmlspecialchars($errorFile, ENT_QUOTES, 'UTF-8'); ?>:<?php echo htmlspecialchars($errorLine, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="error-trace">
                <strong>Hata İzleme:</strong><br>
                <?php foreach ($errorTrace as $trace): ?>
                    <?php if (isset($trace['file'])): ?>
                        <div>
                            Dosya: <?php echo htmlspecialchars($trace['file'], ENT_QUOTES, 'UTF-8'); ?><br>
                            Fonksiyon: <?php echo htmlspecialchars($trace['function'], ENT_QUOTES, 'UTF-8'); ?><br>
                            Satır: <?php echo htmlspecialchars($trace['line'], ENT_QUOTES, 'UTF-8'); ?><br>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
