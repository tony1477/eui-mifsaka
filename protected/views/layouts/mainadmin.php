<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">
	<meta name=viewport content="width=device-width, initial-scale=1">	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-easyui/themes/<?php echo Yii::app()->params['themes'] ?>/easyui.min.css"/>	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-easyui/themes/<?php echo Yii::app()->params['themes'] ?>/combo.min.css">	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-easyui/themes/icon.css"/>	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-easyui/themes/<?php echo Yii::app()->params['themes'] ?>/easyui.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/fullcalendar/lib/cupertino/jquery-ui.min.css"/>	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/fullcalendar/fullcalendar.min.css"/>	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/bootstrap.min.css"/>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/highchart/highcharts.js"></script>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/highchart/modules/exporting.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/highchart/modules/data.js"></script>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/fullcalendar/lib/jquery-ui.custom.min.js"></script>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl?>/js/fullcalendar/lib/moment.min.js"></script>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl?>/js/fullcalendar/fullcalendar.min.js"></script>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-easyui/jquery.easyui.min.js"></script>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-easyui/plugins/jquery.edatagrid.js"></script>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl?>/js/jquery-easyui/plugins/datagrid-detailview.js"></script>
</head>
<body class="easyui-layout">
	<?php Yii::app()->setLanguage('id_id');echo $content; ?>	
</body>
</html>