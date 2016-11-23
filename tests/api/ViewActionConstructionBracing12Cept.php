<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/bracing/15');
$I->sendGET('construction/bracing/15');
$I->seeResponseEquals('{"id":15,"name":"constructionBracing","schemaElementId":11,"isSchemaElement":null,"isActive":true}');