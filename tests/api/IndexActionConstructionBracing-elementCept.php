<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/bracing-element');
$I->sendGET('construction/bracing-element');
$I->seeResponseEquals('{"14":{"id":14,"name":"constructionBracingElement","schemaElementId":11,"isSchemaElement":null,"isActive":true,"elementClasses":null,"elementTypes":null,"models":null,"properties":null,"parent":null,"children":null,"root":null,"hierarchy":null,"inclusions":null,"relationClasses":null,"relationGroups":null,"schemaElement":null,"variants":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\construction\\\\bracingElement\\\\BracingElement"}}');