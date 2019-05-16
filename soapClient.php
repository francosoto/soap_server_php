<?php
$options = ['location' => 'http://localhost/debug.php', 'uri' => 'http://localhost/everth'];
$client = new SoapClient(NULL,$options);
echo $client->getMessage();  //Hello,World!
echo $client->addNumbers(3,5); //  8
