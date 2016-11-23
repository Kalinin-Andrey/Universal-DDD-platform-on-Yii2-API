<?php
/**
 * Created by Bogachev.Petr
 * Date: 03.06.2016
 */

$id = Yii::$app->security->generateRandomString(6);
$js = "Slim.parse(document.getElementById('slim-$id'));";

$this->registerJs($js);

commonprj\widgets\slimImage\assets\SlimImageAsset::register($this);

?>


<div id="slim-<?= $id ?>">
    <div class="slim"<?= $data ?>></div>
</div>

<script>
    function csrf(data, ready) {
        data._csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        ready(data);
    }
</script>