<canvas id="<?php echo $chart_id; ?>" style="width: 100%;"></canvas>
<script>
$(function () {

    var barChartData = <?php echo json_encode($chart_data); ?>;

    var config = {
        type: 'doughnut',
        data: barChartData,
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: '<?php echo $chart_title; ?>'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };

    var ctx = document.getElementById('<?php echo $chart_id; ?>').getContext('2d');
    new Chart(ctx, config);
});
</script>