<?php

include 'header.php';

$search = $_GET['search'] ?? '';

$products = $product->searchProduct($search);

?>

<div class="container mt-5">
  <h2 class="mb-4 text-center">Cari di sini </h2>
  <form action="" method="get" class="mb-4">
    <div class="input-group">
      <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
          fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
          <path
            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
        </svg></span>
      <input type="text" class="form-control" name="search" placeholder="Search products"
        value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
  </form>

  <?php if (count($products) > 0): ?>
    <div class="row">
      <?php foreach ($products as $product): ?>
        <div class="col-md-3 mb-4">
          <a href="detail.php?id=<?= $product['id'] ?>" class="text-decoration-none">
            <div class="card h-100 shadow-sm">
              <img src="assets/<?= htmlspecialchars($product['image']) ?>" class="card-img-top"
                alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='https://via.placeholder.com/150'">
              <div class="card-body">
                <h5 class="card-title">
                  <?= htmlspecialchars($product['name']) ?>
                </h5>
                <p class="card-text">Rp. <?= number_format($product['price']) ?></p>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center" role="alert">
      No products found for your search query.
    </div>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>