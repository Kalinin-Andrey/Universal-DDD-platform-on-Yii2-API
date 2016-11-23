<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/tool/19');
$I->sendGET('construction/tool/19');
$I->seeResponseEquals('{"id":19,"name":"constructionTool","schemaElementId":11,"isSchemaElement":null,"isActive":true}');