<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/bracing-element/14');
$I->sendGET('construction/bracing-element/14');
$I->seeResponseEquals('{"id":14,"name":"constructionBracingElement","schemaElementId":11,"isSchemaElement":null,"isActive":true}');