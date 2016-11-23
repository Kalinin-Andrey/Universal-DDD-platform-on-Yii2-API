<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/relation-class/1');
$I->sendGET('common/relation-class/1');
$I->seeResponseEquals('{"id":1,"name":"material contains material","sysname":"material1material","description":"","relationTypeId":1}');