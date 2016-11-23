<?php 
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/material/3');
$I->sendGET('material/material/3');
$I->seeResponseEquals('{"id":3,"name":"material2","schemaElementId":1,"isSchemaElement":null,"isActive":true}');