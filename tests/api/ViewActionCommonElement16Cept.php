<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/element/16');
$I->sendGET('common/element/16');
$I->seeResponseEquals('{"id":16,"name":"constructionProcess","schemaElementId":11,"isSchemaElement":null,"isActive":true}');