<script type="text/javascript">
$(document).ready(function() {
	$('#timebirthdaycalendar').fullCalendar({
	header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month'
		},
	businessHours: true,
	events: "<?php echo Yii::app()->createUrl('site/birthdate') ?>"
});
});
</script>
<div id='timebirthdaycalendar'></div>