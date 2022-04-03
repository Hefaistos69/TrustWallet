<?php
session_start();
include "../Module/modul-functii.php";
//validate the create form

if (isset($_POST['createAccountForm'])) {
    if (
        isset($_POST['accountName']) && isset($_POST['bankName']) && isset($_POST['accountType'])
        && isset($_POST['accountCurrency']) && isset($_POST['accountBalance'])
    ) {
        $accountName = $_POST['accountName'];
        $bankName = $_POST['bankName'];
        $accountType = $_POST['accountType'];
        $accountCurrency = $_POST['accountCurrency'];
        $accountBalance = $_POST['accountBalance'];
        $error = false;
        //Empty input
        if (EmptyInput($accountBalance, $accountCurrency, $accountName, $accountType, $bankName) !== false) {
            $error = true;
            $_SESSION['error'] = 'emptyInput';
        } else
            //Invalid account name (== Invalid username)
            if (InvalidUsername($accountName) !== false) {
                $error = true;
                $_SESSION['error'] = 'invalidAccountName';
            } else
                //Invalid bank name (== Invalid account name)
                if (InvalidUsername($bankName) !== false) {
                    $error = true;
                    $_SESSION['error'] = 'invalidBankName';
                } else

                    //Invalid account currency
                    if (!in_array($accountCurrency, ['RON', 'EUR', 'USD'])) {
                        $error = true;
                        $_SESSION['error'] = 'invalidAccountCurrency';
                    } else

                        //Invalid account type
                        if (!in_array($accountType, ['Economie', 'Salariu', 'Credit'])) {
                            $error = true;
                            $_SESSION['error'] = 'invalidAccountType';
                        } else

                            //Invalid account balance
                            if (!is_numeric($accountBalance)) {
                                $error = true;
                                $_SESSION['error'] = 'balanceNotNumeric';
                            } else if (intval($accountBalance) < 0 || intval($accountBalance) > 999999999) {
                                $error = true;
                                $_SESSION['error'] = 'balanceOverflow';
                            }


        if ($error) {
            echo json_encode(array('success' => 0, 'error' => ShowError()));
            die();
        }


        echo json_encode(array('success' => 1));
    }
}

//validate the edit form 
if (isset($_POST['editAccountForm'])) {
    if (
        isset($_POST['accountName']) && isset($_POST['bankName']) && isset($_POST['accountType'])
    ) {

        $accountName = $_POST['accountName'];
        $bankName = $_POST['bankName'];
        $accountType = $_POST['accountType'];

        $error = false;
        //Empty input
        if (EmptyInput($accountName, $accountType, $bankName) !== false) {
            $error = true;
            $_SESSION['error'] = 'emptyInput';
        } else
            //Invalid account name (== Invalid username)
            if (InvalidUsername($accountName) !== false) {
                $error = true;
                $_SESSION['error'] = 'invalidAccountName';
            } else
                //Invalid bank name (== Invalid account name)
                if (InvalidUsername($bankName) !== false) {
                    $error = true;
                    $_SESSION['error'] = 'invalidBankName';
                } else
                    //Invalid account type
                    if (!in_array($accountType, ['Economie', 'Salariu', 'Credit'])) {
                        $error = true;
                        $_SESSION['error'] = 'invalidAccountType';
                }

        if ($error) {
            echo json_encode(array('success' => 0, 'error' => ShowError()));
            die();
        }


        echo json_encode(array('success' => 1));
        die();
    }
}

echo json_encode(array('success' => 0));
die();
