<?php
ob_start();
include 'header.php';

?>

<!-- Hero Section -->
<div class="container-fluid py-5 my-5">
  <div class="d-flex align-items-center justify-content-center text-center text-white">
    <div class="overlay"></div>
    <div class="hero-content">
      <h1>Welcome to BoShop</h1>
      <p>Ayo Temukan Barang Kesukaan mu Sekarang !!</p>
      <a href="#products" class="btn btn-primary btn-lg">Beli Sekarang </a>
    </div>
  </div>
</div>

<!-- Main content area -->
<div class="container my-5" id="products">
  <?php include './template/_products.php'; ?>
</div>

<?php
include 'footer.php';
?>