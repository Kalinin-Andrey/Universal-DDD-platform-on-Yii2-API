<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/work/18');
$I->sendGET('construction/work/18');
$I->seeResponseEquals('{"id":18,"name":"constructionWork","schemaElementId":11,"isSchemaElement":null,"isActive":true}');