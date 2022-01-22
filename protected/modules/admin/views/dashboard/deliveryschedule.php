<script type="text/javascript">
$(document).ready(function() {
	$('#timedeliveryschedulecalendar').fullCalendar({
	header: {
			left: 'prev,next today',
			center: 'title',
            right: 'month,agendaWeek,agendaDay'
		},
        buttonText: {
		today: 'today',
		month: 'month',
		week: 'week',
		day: 'day'
	},
	businessHours: true,
	events: "<?php echo Yii::app()->createUrl('site/deliveryschedule') ?>"
});
});
</script>
<div id='timedeliveryschedulecalendar'></div>