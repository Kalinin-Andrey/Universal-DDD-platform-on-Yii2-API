<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/process');
$I->sendGET('construction/process');
$I->seeResponseEquals('{"16":{"id":16,"name":"constructionProcess","schemaElementId":11,"isSchemaElement":null,"isActive":true,"elementClasses":null,"elementTypes":null,"models":null,"properties":null,"parent":null,"children":null,"root":null,"hierarchy":null,"inclusions":null,"relationClasses":null,"relationGroups":null,"schemaElement":null,"variants":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\construction\\\\process\\\\Process"}}');