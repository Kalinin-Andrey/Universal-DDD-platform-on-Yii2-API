<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/material/3/parent?relationGroupId=1');
$I->sendGET('material/material/3/parent?relationGroupId=1');
$I->seeResponseEquals('{"id":2,"name":"material1","schemaElementId":1,"isSchemaElement":null,"isActive":true,"elementClasses":null,"elementTypes":null,"models":null,"properties":null,"parent":null,"children":null,"root":null,"hierarchy":null,"inclusions":null,"relationClasses":null,"relationGroups":null,"schemaElement":null,"variants":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\element\\\\Element"}');