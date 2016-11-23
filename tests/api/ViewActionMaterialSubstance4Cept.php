<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/substance/8');
$I->sendGET('material/substance/8');
$I->seeResponseEquals('{"id":8,"name":"substance1","schemaElementId":7,"isSchemaElement":null,"isActive":true}');