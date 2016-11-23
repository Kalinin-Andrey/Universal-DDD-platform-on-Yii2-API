<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/element-type/1');
$I->sendGET('common/element-type/1');
$I->seeResponseEquals('{"id":1,"name":"elementType1","sysname":"sysname1","elementClassId":1}');