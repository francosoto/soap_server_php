Realizar peticiones con:

AddUser:

curl -X POST --header "Content-Type: text/xml;charset=UTF-8" --data @addUserExample.xml http://127.0.0.1/soap_server_php/service.php

deactivateUser:

curl -X POST --header "Content-Type: text/xml;charset=UTF-8" --data @deactivateUserExample.xml http://127.0.0.1/soap_server_php/service.php


activateUser:

curl -X POST --header "Content-Type: text/xml;charset=UTF-8" --data @activateUserExample.xml http://127.0.0.1/soap_server_php/service.php


getUser:

curl --header "Content-Type: text/xml;charset=UTF-8" --data @getUserExample.xml http://127.0.0.1/soap_server_php/service.php
