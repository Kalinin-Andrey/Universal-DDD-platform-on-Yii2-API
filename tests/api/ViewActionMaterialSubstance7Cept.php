<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/substance/7');
$I->sendGET('material/substance/7');
$I->seeResponseEquals('{"id":7,"name":"abstract substance","schemaElementId":null,"isSchemaElement":null,"isActive":true}');