<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/construction/11');
$I->sendGET('construction/construction/11');
$I->seeResponseEquals('{"id":11,"name":"abstract construction","schemaElementId":null,"isSchemaElement":null,"isActive":true}');