<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/work');
$I->sendGET('construction/work');
$I->seeResponseEquals('{"18":{"id":18,"name":"constructionWork","schemaElementId":11,"isSchemaElement":null,"isActive":true,"elementClasses":null,"elementTypes":null,"models":null,"properties":null,"parent":null,"children":null,"root":null,"hierarchy":null,"inclusions":null,"relationClasses":null,"relationGroups":null,"schemaElement":null,"variants":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\construction\\\\work\\\\Work"}}');