<?php

if(isset($_POST['accountName']) && isset($_POST['bankName']) && isset($_POST['accountType'])
&& isset($_POST['accountCurrency']) && isset($_POST['accountBalance']))
{
    echo json_encode(array('success' => 1));
}
else
{
    echo json_encode(array('success' => 0));
}