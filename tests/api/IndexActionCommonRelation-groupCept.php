<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/relation-group');
$I->sendGET('common/relation-group');
$I->seeResponseEquals('{"1":{"id":1,"relationClassId":10,"rootId":2,"name":"materialMaterial","relationClass":null,"relations":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\relationGroup\\\\RelationGroup"},"2":{"id":2,"relationClassId":2,"rootId":3,"name":"materialSubstance","relationClass":null,"relations":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\relationGroup\\\\RelationGroup"},"3":{"id":3,"relationClassId":10,"rootId":15,"name":"materialSubstance","relationClass":null,"relations":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\relationGroup\\\\RelationGroup"}}');