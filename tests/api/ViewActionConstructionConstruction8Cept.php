<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/construction/13');
$I->sendGET('construction/construction/13');
$I->seeResponseEquals('{"id":13,"name":"constructionConstruction","schemaElementId":11,"isSchemaElement":null,"isActive":true}');