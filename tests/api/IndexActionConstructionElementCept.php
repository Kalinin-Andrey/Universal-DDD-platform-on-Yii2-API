<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/element');
$I->sendGET('construction/element');
$I->seeResponseEquals('{"12":{"id":12,"name":"constructionElement","schemaElementId":11,"isSchemaElement":null,"isActive":true,"elementClasses":null,"elementTypes":null,"models":null,"properties":null,"parent":null,"children":null,"root":null,"hierarchy":null,"inclusions":null,"relationClasses":null,"relationGroups":null,"schemaElement":null,"variants":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\construction\\\\element\\\\Element"}}');