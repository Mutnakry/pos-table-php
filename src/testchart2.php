<?php
include("../connection.php");

// Initialize variables
$total = 0;
$arreglo = [];

// Check if POST request and 'action' is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'room') {
        $desde = date('Y') . '-01-01 00:00:00';
        $hasta = date('Y') . '-12-31 23:59:59';
        $query = mysqli_query($conexion, "SELECT 
            SUM(IF(MONTH(dob) = 1, total, 0)) AS ene,
            SUM(IF(MONTH(dob) = 2, total, 0)) AS feb,
            SUM(IF(MONTH(dob) = 3, total, 0)) AS mar,
            SUM(IF(MONTH(dob) = 4, total, 0)) AS abr,
            SUM(IF(MONTH(dob) = 5, total, 0)) AS may,
            SUM(IF(MONTH(dob) = 6, total, 0)) AS jun,
            SUM(IF(MONTH(dob) = 7, total, 0)) AS jul,
            SUM(IF(MONTH(dob) = 8, total, 0)) AS ago,
            SUM(IF(MONTH(dob) = 9, total, 0)) AS sep,
            SUM(IF(MONTH(dob) = 10, total, 0)) AS oct,
            SUM(IF(MONTH(dob) = 11, total, 0)) AS nov,
            SUM(IF(MONTH(dob) = 12, total, 0)) AS dic 
            FROM room WHERE dob BETWEEN '$desde' AND '$hasta'");
        $arreglo = mysqli_fetch_assoc($query);

        // Get total sales
        $query5 = mysqli_query($conexion, "SELECT SUM(`total`) AS total FROM room");
        if ($query5) {
            $totalVentas = mysqli_fetch_assoc($query5);
            $total = $totalVentas['total'] ?? 0; // Default to 0 if no result
        }

        $arreglo['total'] = $total; // Add total to response
        
        echo json_encode($arreglo);
        die();
    }
}
?>


<?php
include('./testchart2.php')
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Sales</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">$<?php echo number_format($total, 2); ?></span>
                            <span>Total</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="sales-chart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fetch sales data from PHP
            var salesData = <?php echo json_encode($arreglo); ?>;

            var ctx = document.getElementById('sales-chart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Monthly Sales',
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            data: [salesData.ene, salesData.feb, salesData.mar, salesData.abr, salesData.may, salesData.jun, salesData.jul, salesData.ago, salesData.sep, salesData.oct, salesData.nov, salesData.dic]
                        },
                        {
                            label: 'Total Sales',
                            data: Array(12).fill(salesData.total), // Add total to all months for highlighting
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            type: 'line', // Line type for total sales
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: 'index',
                        intersect: true
                    },
                    hover: {
                        mode: 'index',
                        intersect: true
                    },
                    legend: {
                        display: true
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: {
                                beginAtZero: true,
                                callback: function (value) {
                                    if (value >= 1000) {
                                        value /= 1000
                                        value += 'k'
                                    }
                                    return '$' + value
                                }
                            }
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });
        });
    </script>
</body>
</html>
