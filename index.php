<?php

require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Session.php';
require_once 'core/Database.php';

Session::start();

function handleException($exception) {
    http_response_code(500);
    $errorMessage = $exception->getMessage();
    $errorFile = $exception->getFile();
    $errorLine = $exception->getLine();
    $errorTrace = $exception->getTrace();
    require 'app/views/errors/500.php';
    exit();
}

function handleError($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    $errorMessage = $errstr;
    $errorFile = $errfile;
    $errorLine = $errline;
    $errorTrace = debug_backtrace();
    require 'app/views/errors/500.php';
    exit();
}

set_exception_handler('handleException');
set_error_handler('handleError');

function parseIBAN($iban) {

    $iban = strtoupper(str_replace(' ', '', $iban));
    if (strlen($iban) != 26) {
        return ['error' => 'Geçersiz İBAN uzunluğu. Türkiye için İBAN 26 haneli olmalıdır.'];
    }
    
    $countryCode = substr($iban, 0, 2);
    $checkDigits = substr($iban, 2, 2);
    
    if ($countryCode !== 'TR') {
        return ['error' => 'Desteklenmeyen Ülke Kodu!'];
    }
    
    if (!is_numeric($checkDigits)) {
        return ['error' => 'Geçersiz kontrol rakamları!'];
    }

    $countryCode = substr($iban, 0, 2);
    $checkDigits = substr($iban, 2, 2);
    
    if ($countryCode === 'TR') {
        $bankCode = substr($iban, 6, 3);
        $accountNumber = substr($iban, 18);
        $bankName = getBankName($bankCode);
        
        return [
            'bank_name' => $bankName ?? 'Bilinmiyor',
            'bank_code' => "0" . $bankCode,
            'account_number' => ltrim($accountNumber, '0'),
            'iban' => trim(chunk_split($iban, 4, ' ')),
        ];
    } else {
        return ['error' => 'Desteklenmeyen Ülke Kodu!'];
    }
}

function getBankName($bankCode) {
    $banks = [
        '001' => 'Türkiye Cumhuriyet Merkez Bankası A.Ş.',
        '004' => 'İller Bankası',
        '010' => 'Türkiye Cumhuriyeti Ziraat Bankası A.Ş.',
        '012' => 'Türkiye Halk Bankası A.Ş.',
        '013' => 'Denizbank',
        '014' => 'Türkiye Sınai Kalkınma Bankası A.Ş.',
        '015' => 'Türkiye Vakıflar Bankası T.A.O.',
        '016' => 'Türkiye İhracat Kredi Bankası A.Ş. (Eximbank)',
        '017' => 'Türkiye Kalkınma Bankası A.Ş.',
        '029' => 'Birleşik Fon Bankası A.Ş. (Bayındırbank A.Ş.)',
        '032' => 'Türk Ekonomi Bankası A.Ş.',
        '034' => 'Aktif Yatırım Bankası A.Ş.',
        '046' => 'Akbank T.A.Ş.',
        '048' => 'HSBC Bank A.Ş.',
        '058' => 'Sınai Yatırım Bankası A.Ş.',
        '059' => 'Şekerbank T.A.Ş.',
        '062' => 'Türkiye Garanti Bankası A.Ş.',
        '064' => 'Türkiye İş Bankası A.Ş.',
        '067' => 'Yapı ve Kredi Bankası A.Ş.',
        '071' => 'Fortis Bank (TEB)',
        '087' => 'Banca di Roma',
        '088' => 'The Royal Bank of Scotland PLC Merkezi Amsterdam İstanbul Merkez Şubesi',
        '091' => 'Arap Türk Bankası A.Ş.',
        '092' => 'Citibank N.A.',
        '094' => 'Bank Mellat',
        '095' => 'BCCI',
        '096' => 'Turkish Bank A.Ş.',
        '097' => 'Habib Bank Limited',
        '098' => 'JP Morgan Chase Bank İstanbul Türkiye Şubesi',
        '099' => 'Oyak Bank A.Ş. - ING BANK',
        '100' => 'Adabank A.Ş.',
        '101' => 'Türk Sakura Bank A.Ş.',
        '103' => 'Fiba Bank A.Ş.',
        '104' => 'IMPEXBANK',
        '106' => 'PORTIGON A.G.',
        '107' => 'BNP-Ak-Dresdner Bank A.Ş.',
        '108' => 'Turkland Bank A.Ş.',
        '109' => 'Tekstil Bankası A.Ş.',
        '110' => 'Credit Lyonnais',
        '111' => 'Finansbank A.Ş.',
        '113' => 'Marbank',
        '115' => 'Deutsche Bank A.Ş.',
        '116' => 'TAİB Yatırım Bank A.Ş.',
        '117' => 'Turizm Yatırım ve Ticaret Bank A.Ş.',
        '118' => 'Kıbrıs Kredi Bankası',
        '119' => 'Birleşik Yatırım',
        '121' => 'Standard Chartered Yatırım Bankası Türk A.Ş.',
        '122' => 'Societe Generale',
        '123' => 'HSBC Bank A.Ş.',
        '124' => 'Alternatifbank A.Ş.',
        '125' => 'Burganbank A.Ş.',
        '127' => 'KentBank',
        '128' => 'Park Yatırım Bankası',
        '129' => 'Tat Yatırım Bankası A.Ş.',
        '132' => 'IMKB Takas ve Saklama Bankası A.Ş.',
        '133' => 'ING BANK',
        '134' => 'Denizbank A.Ş.',
        '135' => 'Anadolubank A.Ş.',
        '136' => 'Okan Yatırım Bankası A Ş',
        '137' => 'Rabobank Nederland İstanbul Merkez Şubesi',
        '138' => 'Diler Yatırım Bankası A.Ş.',
        '139' => 'GSD Yatırım Bankası A.Ş.',
        '140' => 'Credit Suisse First Boston İstanbul Şubesi',
        '141' => 'Nurol Yatırım Bankası A.Ş.',
        '142' => 'Bank Pozitif Kredi ve Kalkınma Bankası A.Ş.',
        '144' => 'Atlas Yatırım Bankası A.Ş.',
        '145' => 'Morgan Guarenty Trust Company',
        '146' => 'OdeaBank A.Ş.',
        '147' => 'Bank of Tokyo -Mitsubishi UFJ Turkey A.Ş.',
        '148' => 'Intesa SanPaolo SPA İtalya-İstanbul Merkez Şubesi',
        '203' => 'Al Baraka Türk Katılım Bankası A.Ş.',
        '204' => 'Family Finans Kurumu',
        '205' => 'Kuveyt Türk Katılım Bankası A.Ş.',
        '206' => 'Türkiye Finans Katılım Bankası A.Ş.',
        '208' => 'Asya Katılım Bankası A.Ş.',
        '210' => 'Vakıf Katılım Bankası A.Ş.',
        '223' => 'Al Baraka Türk Katılım Bankası A.Ş.'
    ];

    return $banks[$bankCode] ?? null;
}

$app = new App();
