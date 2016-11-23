<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/material/3/hierarchy?relationGroupId=1');
$I->sendGET('material/material/3/hierarchy?relationGroupId=1');
$I->seeResponseEquals('{"id":2,"name":"material1","schema_element_id":1,"is_active":true,"children":{"1":{"id":3,"name":"material2","schema_element_id":1,"is_active":true,"children":{"3":{"id":4,"name":"material3","schema_element_id":1,"is_active":true,"children":{"4":{"id":5,"name":"material4","schema_element_id":1,"is_active":true}}}}},"2":{"id":6,"name":"material5","schema_element_id":1,"is_active":true}}}');