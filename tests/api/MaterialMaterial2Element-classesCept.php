<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/material/2/element-classes');
$I->sendGET('material/material/2/element-classes');
$I->seeResponseEquals('{"1":{"id":1,"contextId":6,"name":"material\\\\Material","sysname":"material_Material","description":"","relationClasses":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\elementClass\\\\ElementClass"}}');