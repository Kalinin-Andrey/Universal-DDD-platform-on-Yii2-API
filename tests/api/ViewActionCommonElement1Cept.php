<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/element/1');
$I->sendGET('common/element/1');
$I->seeResponseEquals('{"id":1,"name":"abstract material","schemaElementId":null,"isSchemaElement":null,"isActive":true}');