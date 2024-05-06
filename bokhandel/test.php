<?php


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    echo 'Forwarded IP: ' . $_SERVER['HTTP_CLIENT_IP'];
  } else {
      echo 'No shared network IP address available';
  }
  


?>