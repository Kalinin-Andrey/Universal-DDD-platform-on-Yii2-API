<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/element/12');
$I->sendGET('construction/element/12');
$I->seeResponseEquals('{"id":12,"name":"constructionElement","schemaElementId":11,"isSchemaElement":null,"isActive":true}');