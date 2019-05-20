# AddUser:

curl -X POST --header "Content-Type: text/xml;charset=UTF-8" --data @addUserExample.xml http://127.0.0.1/soap_server_php/service.php

# deactivate
curl -X POST --header "Content-Type: text/xml;charset=UTF-8" --data @deactivateUserExample.xml http://127.0.0.1/soap_server_php/service.php

# activate
curl -X POST --header "Content-Type: text/xml;charset=UTF-8" --data @activateUserExample.xml http://127.0.0.1/soap_server_php/service.php

# get
curl -X POST --header "Content-Type: text/xml;charset=UTF-8" --data @addUserExample.xml http://127.0.0.1/soap_server_php/service.php
