<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $user_id = $_SESSION['user_id'];

  $data = [
    'product_id' => $product_id,
    'user_id' => $user_id,
    'quantity' => $quantity
  ];

  if (addToCart($data)) {
    echo "
    <script>
        if (confirm('Product berhasil ditambahkan ke cart.\nProceed to cart?')) {
          window.location.href = 'cart.php';
        } else {
          window.location.href = 'cart.php';
        }
    </script>
    ";
  } else {
    echo "
    <script>
        alert('Product sudah ada di cart');
        window.location.href = '/';
    </script>
    ";
  }
}
?>
<h1 class="mb-4 text-center">Produk</h1>
<div class="row">
  <?php $i = 1;
  if ($products):
    foreach ($products as $row): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-img-container">
            <img src="assets/<?= $row['image'] ?>" class="card-img-top img-1-1" alt="Product Image 1"
              onerror="this.src='https://via.placeholder.com/150'">
          </div>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title text-black">
              <a href="/detail.php?id=<?= $row['id'] ?>" class="text-white"><?= $row['name'] ?></a>
            </h5>
            <p class="card-text"><?= excerpt($row['description']) ?></p>
            <p class="card-text"><strong>Rp. <?= number_format($row['price']) ?></strong></p>
            <div class="mt-auto d-flex flex-column gap-2">
              <!-- Form to add product to cart -->
              <form action="" method="post" class="d-flex flex-column gap-3">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <div class="d-flex gap-2 align-items-center">
                  <label for="quantity">Quantity:</label>
                  <?php if ($row['stock'] > 1): ?>
                    <input type="number" name="quantity" class="form-control w-25" id="quantity" value="1" min="0"
                      max="<?= $row['stock'] ?>">
                  <?php else: ?>
                    <input type="number" name="quantity" class="form-control w-25" id="quantity" value="0" min="0"
                      max="<?= $row['stock'] ?>" disabled>
                  <?php endif; ?>
                  <!-- Default quantity set to 1 -->
                </div>
                <?php if (isset($_SESSION['login'])): ?>
                  <?php if ($row['stock'] > 1): ?>
                    <button type="submit" class="btn btn-primary">Buy Now</button>
                  <?php else: ?>
                    <button class="btn btn-danger disabled">Not Available</button>
                  <?php endif; ?>
                <?php endif; ?>
              </form>
              <span class="text-secondary fs-6">Stock: <?= $row['stock'] ?></span>
              <?php if (isAdmin()): ?>
                <a class="btn btn-success" href="/product/edit.php?id=<?= $row['id'] ?>">Edit</a>
                <a class="btn btn-danger" href="/product/remove.php?id=<?= $row['id'] ?>">Remove</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php $i++ ?>
    <?php endforeach;
  else: ?>
    <div class="container mt-5 text-center">
      <p>Produk Kosong nihh!.</p>
      <a href="/" class="btn btn-secondary">Kembali ke halaman utama </a>
    </div>
  <?php endif; ?>
</div>