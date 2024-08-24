<?php
session_start();
include_once "includes/header.php";
include "../connection.php";

$query5 = mysqli_query($conn, "SELECT SUM(`total`) AS total FROM room");
$totalVentas = mysqli_fetch_assoc($query5);

$total = $totalVentas['total'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
  <div class="card">
    <div class="card-header text-center">
      <!-- <h1>Welcome, <?php echo $_SESSION['names']; ?>!</h1>
      <p>Your role: <?php echo $_SESSION['rol']; ?></p> -->
    </div>
    <div class="card-body">

      <div class="row">
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gradient-success">
            <div class="inner">
              <h3>44</h3>
              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="fas fa-credit-card"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gradient-success">
            <div class="inner">
              <h3>44</h3>
              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gradient-success">
            <div class="inner">
              <h3>44</h3>
              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gradient-success">
            <div class="inner">
              <h3>4400000000000</h3>
              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="fas fa-cogs"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gradient-success">
            <div class="inner">
              <h3>4400000000000</h3>
              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="fas fa-cogs"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gradient-success">
            <div class="inner">
              <h3>4400000000000</h3>
              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="fa-duotone fa-solid fa-person-sign"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="small-box bg-gradient-success">
            <div class="inner">
              <h3>4400000000000</h3>
              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="fas fa-cogs"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- <div class="row">
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
                  <span class="text-bold text-lg">$<?php echo $totalVentas['total']; ?></span>
                  <span>Total</span>
                </p>
              </div>
              <div class="position-relative mb-4">
                <canvas id="sales-chart" height="200"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div> -->
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
      <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Sales</h3>
                        </div>
                    </div>
                    <div class="card-body bg-danger">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">$<?php echo $totalVentas['total']; ?></span>
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
    </div>
</body>

</html>
<?php include_once "includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script src="../assets/js/dashboard.js"></script>