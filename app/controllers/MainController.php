<?php

class MainController extends Controller {
    
    public function index() {

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $iban = isset($_POST['iban']) ? $_POST['iban'] : null;
            if ($iban) {
                $parsedIban = parseIBAN($iban);
                if (is_array($parsedIban)) {
                    header('Content-Type: application/json');
                    echo json_encode($parsedIban);
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => $parsedIban]);
                    exit;
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'IBAN numarası girilmemiş.']);
                exit;
            }
        }

        return $this->view('home/index');
    }

}
