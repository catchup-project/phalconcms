用法：
$thumb=Yii::app()->phpThumb->create('../images/myImage.jpg');
$thumb->resize(100,100);
$thumb->save('../images/thumb.jpg');