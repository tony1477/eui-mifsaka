<script type="text/javascript">
$(document).ready(function() {
	$('#timeschedulecalendar').fullCalendar({
	header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month'
		},
	businessHours: true,
	events: "<?php echo Yii::app()->createUrl('site/productionfg') ?>"
});
});
</script>
<div id='timeschedulecalendar'></div>