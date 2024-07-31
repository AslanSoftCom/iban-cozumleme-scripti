<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İBAN Çözümleme</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="iban-container">
            <div class="iban-info">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>İBAN Çözümleme Nedir?</h2>
                    </div>
                    <div class="card-body">
                        <p class="text-center">Bu sayfada yer alan İBAN çözümleme aracı, kullanıcıların İBAN numarasından hesap numarasını, şube kodunu ve bankanın adını kolayca bulmalarını sağlayan pratik bir uygulamadır. Bu işlemler, bankaların resmi web sitelerinde yayınlanan güvenilir bilgiler ve formüller kullanılarak gerçekleştirilmiştir. İBAN numaranızı girdiğinizde, sistem otomatik olarak İBAN doğrulama işlemini yapar ve size hesap numarası, şube kodu ve banka bilgilerini sunar. Bu sayede, İBAN numarasından gerekli bilgileri öğrenmek hızlı ve etkili bir şekilde mümkün hale gelir. Bu araç, özellikle banka işlemlerinde ihtiyaç duyabileceğiniz kritik bilgileri anında elde etmenize yardımcı olmak için tasarlanmıştır.</p>
                    </div>
                </div>
            </div>
            <div class="iban-form">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>İBAN Çözümleme</h2>
                    </div>
                    <div class="card-body">
                        <form id="ibanForm">
                            <div class="form-group">
                                <label for="iban">İBAN Numarası:</label>
                                <input type="text" class="form-control" id="iban" placeholder="İBAN numaranızı girin" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Çözümle</button>
                        </form>
                    </div>
                </div>

                <div class="card result-card" id="result" style="display: none;">
                    <div class="card-body">
                        <h4>Çözümleme Sonuçları</h4>
                        <hr>
                        <p><strong>Banka Adı:</strong> <span id="bankName"></span></p>
                        <p><strong>Banka Kodu:</strong> <span id="bankCode"></span></p>
                        <p><strong>Hesap No:</strong> <span id="accountNumber"></span></p>
                        <p><strong>İban:</strong> <span id="ibanP"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#ibanForm').on('submit', function(e) {
            e.preventDefault();
            const iban = $('#iban').val();

            $.ajax({
                url: '/',
                method: 'POST',
                data: { iban: iban },
                success: function(response) {
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: response.error
                        });
                    } else {
                        $('#bankName').text(response.bank_name);
                        $('#bankCode').text(response.bank_code);
                        $('#accountNumber').text(response.account_number);
                        $('#ibanP').text(response.iban);
                        $('#result').show();
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Bir hata oluştu!',
                        text: 'Lütfen daha sonra tekrar deneyin.'
                    });
                }
            });
        });
    </script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
