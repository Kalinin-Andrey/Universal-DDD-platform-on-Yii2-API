<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/element-type/2');
$I->sendGET('common/element-type/2');
$I->seeResponseEquals('{"id":2,"name":"elementType2","sysname":"sysname2","elementClassId":2}');