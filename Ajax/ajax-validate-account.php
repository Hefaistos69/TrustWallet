<?php
session_start();
include "../Module/modul-functii.php";
//validate the create account form

if (isset($_POST['createAccountForm'])) {
    if (
        isset($_POST['accountName']) && isset($_POST['accountType'])
        && isset($_POST['accountCurrency']) && isset($_POST['accountBalance'])
    ) {
        $accountName = $_POST['accountName'];
        $accountType = $_POST['accountType'];
        $accountCurrency = $_POST['accountCurrency'];
        $accountBalance = $_POST['accountBalance'];
        $error = false;
        //Empty input
        if (EmptyInput($accountBalance, $accountCurrency, $accountName, $accountType) !== false) {
            $error = true;
            $_SESSION['error'] = 'emptyInput';
        }
        //Invalid account name (== Invalid username)
        else if (InvalidUsername($accountName) !== false) {
            $error = true;
            $_SESSION['error'] = 'invalidAccountName';
        }
        //Invalid account currency
        else if (!in_array($accountCurrency, ['RON', 'EUR', 'USD'])) {
            $error = true;
            $_SESSION['error'] = 'invalidCurrency';
        }
        //Invalid account type
        else if (!in_array($accountType, ['Economie', 'Salariu', 'Credit'])) {
            $error = true;
            $_SESSION['error'] = 'invalidAccountType';
        }
        //Invalid account balance
        else if (!is_numeric($accountBalance)) {
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


        echo json_encode(array('success' => 1, 'transaction' => false ));
        die();
    }
}

//validate the edit accounts form 
if (isset($_POST['editAccountForm'])) {
    if (
        isset($_POST['accountName']) && isset($_POST['accountType'])
    ) {

        $accountName = $_POST['accountName'];
        $accountType = $_POST['accountType'];

        $error = false;
        //Empty input
        if (EmptyInput($accountName, $accountType) !== false) {
            $error = true;
            $_SESSION['error'] = 'emptyInput';
        }
        //Invalid account name (== Invalid username)
        else if (InvalidUsername($accountName) !== false) {
            $error = true;
            $_SESSION['error'] = 'invalidAccountName';
        }
        //Invalid account type
        else if (!in_array($accountType, ['Economie', 'Salariu', 'Credit'])) {
            $error = true;
            $_SESSION['error'] = 'invalidAccountType';
        }

        if ($error) {
            echo json_encode(array('success' => 0, 'error' => ShowError()));
            die();
        }


        echo json_encode(array('success' => 1, 'transaction' => false));
        die();
    }
}

// validating the add transaction form

if (isset($_POST['addTransaction']) || isset($_POST['editTransaction'])) {
    
    
    if (
        isset($_POST['transactionCurrency']) && isset($_POST['transactionBalance'])
        && isset($_POST['transactionType']) && isset($_POST['transferToAccount'])
        && isset($_POST['transactionMemo'])
    ) {
        
        $transactionCurrency = $_POST['transactionCurrency'];
        $transactionBalance = $_POST['transactionBalance'];
        $transactionType = $_POST['transactionType'];
        $transferToAccount = $_POST['transferToAccount'];
        $transactionMemo = $_POST['transactionMemo'];
        
        $error = false;
        //Empty input
        if (EmptyInput($transactionBalance, $transactionCurrency, $transactionMemo, $transactionType) !== false) {
            $error = true;
            $_SESSION['error'] = 'emptyInput';
        } else if($transactionType == 'Transfer' && EmptyInput($transferToAccount) !== false)
        {
            $error = true;
            $_SESSION['error'] = 'emptyInput';
        }
        //Invalid transaction currency
        else if (!in_array($transactionCurrency, ['RON', 'EUR', 'USD'])) {
            $error = true;
            $_SESSION['error'] = 'invalidCurrency';
        }
        //Invalid transaction balance
        else if (!is_numeric($transactionBalance)) {
            $error = true;
            $_SESSION['error'] = 'balanceNotNumeric';
        } else if (intval($transactionBalance) < 0 || intval($transactionBalance) > 999999999) {
            $error = true;
            $_SESSION['error'] = 'balanceOverflow';
        }
        //Invalid transaction type
        else if (!in_array($transactionType, ['Depunere', 'Cheltuire', 'Transfer'])) {
            $error = true;
            $_SESSION['error'] = 'invalidTransactionType';
        }
        //invalid memo
        else if (strlen($transactionMemo) > 20) {
            $error = true;
            $_SESSION['error'] = 'invalidMemo';
        }

        if ($error) {
            echo json_encode(array('success' => 0, 'error' => ShowError()));
            die();
        }


        echo json_encode(array('success' => 1, 'transaction' => true));
        die();
    }
}

//Validate edit transaction form


echo json_encode(array('success' => 0));
die();
