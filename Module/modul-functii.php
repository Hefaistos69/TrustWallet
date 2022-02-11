<?php

function Loggedin()
{
  if(isset($_SESSION['userId']))
    return $_SESSION['userId'];
  return false;
}