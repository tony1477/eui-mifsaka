<script type="text/javascript">
$(function () {
    $('#compareppop').highcharts({
        chart: {
        type: 'column'
    },
    
    plotOptions: {
        series: {
            dataLabels: {
                enabled: true
            }
        }
    },
    title: {
        text: 'Data input as row arrays'
    },
    xAxis: {
        text: 'Month' 
    },
    data: {
        rows: [
            [null, 'Ola', 'Kari','SPK'], // series names
            ['Januari', 15403.80, 10742.68, 4661.12], // category and values
            ['Februari', 14023, 13245,778], // category and values
            ['Maret', 13452, 9857,3595] // category and values
        ]
    }
});
});
</script>

<div id="compareppop" style="height: 300px"></div>