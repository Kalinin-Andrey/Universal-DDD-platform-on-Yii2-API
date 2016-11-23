<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/relation-class/37');
$I->sendGET('common/relation-class/37');
$I->seeResponseEquals('{"id":37,"name":"interiorRoom has interiorModel","sysname":"interiorRoom2interiorModel","description":"","relationTypeId":2}');