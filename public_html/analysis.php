<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("database/db.php");
$db = new Database();
$con = $db->connect();

// Total Products
$total_products = $con->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];

// Total Categories
$total_categories = $con->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];

// Total Brands
$total_brands = $con->query("SELECT COUNT(*) as count FROM brands")->fetch_assoc()['count'];

// Low Stock Products
$low_stock = $con->query("SELECT COUNT(*) as count FROM products WHERE products_stock < 5")->fetch_assoc()['count'];

// Total Sales (from invoices)
$total_sales = $con->query("SELECT SUM(net_total) as total FROM invoice")->fetch_assoc()['total'] ?? 0;
?>


<!DOCTYPE html>
<html>
<head>
  <title>Inventory Analysis</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-5">

  <h2 class="mb-4 text-center">📊 Inventory Analysis Dashboard</h2>

  <div class="row text-center">
    <div class="col-md-3">
      <div class="card shadow p-3">
        <h5>Total Products</h5>
        <h2><?php echo $total_products; ?></h2>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow p-3">
        <h5>Total Categories</h5>
        <h2><?php echo $total_categories; ?></h2>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow p-3">
        <h5>Total Brands</h5>
        <h2><?php echo $total_brands; ?></h2>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow p-3">
        <h5>Low Stock Products</h5>
        <h2><?php echo $low_stock; ?></h2>
      </div>
    </div>
  </div>

  <div class="mt-5 text-center">
    <div class="card shadow p-3 mx-auto" style="max-width: 400px;">
      <h5>Total Sales</h5>
      <h2>$<?php echo number_format($total_sales, 2); ?></h2>
    </div>
  </div>

</body>
</html>
