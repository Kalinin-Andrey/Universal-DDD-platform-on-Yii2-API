<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/tool');
$I->sendGET('construction/tool');
$I->seeResponseEquals('{"19":{"id":19,"name":"constructionTool","schemaElementId":11,"isSchemaElement":null,"isActive":true,"elementClasses":null,"elementTypes":null,"models":null,"properties":null,"parent":null,"children":null,"root":null,"hierarchy":null,"inclusions":null,"relationClasses":null,"relationGroups":null,"schemaElement":null,"variants":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\construction\\\\tool\\\\Tool"}}');