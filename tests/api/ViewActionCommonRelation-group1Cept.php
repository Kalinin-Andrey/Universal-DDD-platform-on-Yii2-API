<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/relation-group/1');
$I->sendGET('common/relation-group/1');
$I->seeResponseEquals('{"id":1,"name":"materialMaterial","relationClassId":10,"rootId":2}');