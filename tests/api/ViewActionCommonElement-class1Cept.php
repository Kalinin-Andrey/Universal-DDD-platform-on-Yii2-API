<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/element-class/1');
$I->sendGET('common/element-class/1');
$I->seeResponseEquals('{"id":1,"contextId":6,"name":"material\\\\Material","sysname":"material_Material","description":""}');