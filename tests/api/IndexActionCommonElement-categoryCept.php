<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/element-category');
$I->sendGET('common/element-category');
$I->seeResponseEquals('{"1":{"id":1,"elementTypeId":1,"parentId":null,"rootId":1,"isParent":false,"isActive":true,"name":"elementCategory1","sysname":"elementCategory1Sysname","description":"elementCategory1Description","elementType":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\elementCategory\\\\ElementCategory"}}');