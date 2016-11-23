<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /construction/method');
$I->sendGET('construction/method');
$I->seeResponseEquals('{"17":{"id":17,"name":"constructionMethod","schemaElementId":11,"isSchemaElement":null,"isActive":true,"elementClasses":null,"elementTypes":null,"models":null,"properties":null,"parent":null,"children":null,"root":null,"hierarchy":null,"inclusions":null,"relationClasses":null,"relationGroups":null,"schemaElement":null,"variants":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\construction\\\\method\\\\Method"}}');