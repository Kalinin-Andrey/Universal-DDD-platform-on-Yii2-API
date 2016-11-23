<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/element-class/26');
$I->sendGET('common/element-class/26');
$I->seeResponseEquals('{"id":26,"contextId":5,"name":"interior\\\\Model","sysname":"interior_Model","description":""}');