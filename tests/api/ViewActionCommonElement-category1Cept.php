<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/element-category/1');
$I->sendGET('common/element-category/1');
$I->seeResponseEquals('{"description":"elementCategory1Description","elementTypeId":1,"id":1,"name":"elementCategory1","parentId":null,"rootId":1,"sysname":"elementCategory1Sysname","isActive":true,"isParent":false}');