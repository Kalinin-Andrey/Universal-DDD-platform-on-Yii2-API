<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/material/1');
$I->sendGET('material/material/1');
$I->seeResponseEquals('{"id":1,"name":"abstract material","schemaElementId":null,"isSchemaElement":null,"isActive":true}');