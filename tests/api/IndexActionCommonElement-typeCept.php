<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/element-type');
$I->sendGET('common/element-type');
$I->seeResponseEquals('{"1":{"id":1,"elementClassId":1,"variantTypeId":1,"name":"elementType1","sysname":"sysname1","elementCategory":null,"elementClass":null,"variant":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\elementType\\\\ElementType"},"2":{"id":2,"elementClassId":2,"variantTypeId":2,"name":"elementType2","sysname":"sysname2","elementCategory":null,"elementClass":null,"variant":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\elementType\\\\ElementType"}}');