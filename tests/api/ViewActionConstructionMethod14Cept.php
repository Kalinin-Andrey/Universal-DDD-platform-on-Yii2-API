<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/method/17');
$I->sendGET('construction/method/17');
$I->seeResponseEquals('{"id":17,"name":"constructionMethod","schemaElementId":11,"isSchemaElement":null,"isActive":true}');