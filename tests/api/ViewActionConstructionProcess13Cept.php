<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/process/16');
$I->sendGET('construction/process/16');
$I->seeResponseEquals('{"id":16,"name":"constructionProcess","schemaElementId":11,"isSchemaElement":null,"isActive":true}');