<?php

include 'header.php';

$id = $_GET['id'];

// Call getProduct method
$detail = $product->getProduct($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest';

  $data = [
    'product_id' => $product_id,
    'user_id' => $user_id,
    'quantity' => $quantity
  ];

  if (addToCart($data) && $user_id !== 'guest' && $data['quantity'] > 0) {
    // reset post
    $_POST = [];
    echo "
    <script>
        alert('Product berhasil ditambahkan ke cart');
        window.location.href = 'cart.php';
    </script>
    ";
  } else {
    // reset post
    echo "
    <script>
        alert('Product gagal ditambahkan ke cart');
        window.location.href = '/';
    </script>
    ";
  }
}

if ($detail):
  ?>
  <div class="container mt-5">
    <h1 class="text-center mb-4">Product Detail</h1>
    <div class="row">
      <div class="col-md-6 mb-5">
        <img src="/assets/<?= htmlspecialchars($detail['image']) ?>" alt="Product Image" class="img-fluid rounded shadow">
      </div>
      <div class="col-md-6 border py-5 rounded shadow-sm">
        <h2><?= htmlspecialchars($detail['name']) ?></h2>
        <hr>
        <p><?= nl2br(htmlspecialchars($detail['description'])) ?></p> <!-- Convert \n to <br> -->
        <hr>
        <p class="h4">Price: Rp. <?= htmlspecialchars(number_format($detail['price'])) ?></p>
        <p>Stock: <?= htmlspecialchars($detail['stock']) ?></p>
        <form action="" method="post">
          <input type="hidden" name="product_id" value="<?= $detail['id'] ?>">
          <div class="d-flex align-items-center my-3">
            <label for="quantity" class="me-2">Quantity:</label>
            <?php if ($detail['stock'] > 1): ?>
              <input type="number" name="quantity" class="form-control w-25" id="quantity" value="1" min="0"
                max="<?= $detail['stock'] ?>">
            <?php else: ?>
              <input type="number" name="quantity" class="form-control w-25" id="quantity" value="0" min="0"
                max="<?= $detail['stock'] ?>" disabled>
            <?php endif; ?>
          </div>
          <?php if (isset($_SESSION['login'])): ?>
            <?php if ($detail['stock'] > 0): ?>
              <button type="submit" class="btn btn-primary"><i class="bi bi-cart-plus"></i> Add to Cart</button>
            <?php else: ?>
              <button type="button" class="btn btn-danger" disabled>Out of Stock</button>
            <?php endif; ?>
          <?php endif; ?>
        </form>
        <a href="/" class="btn btn-secondary mt-3">Return to Homepage</a>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="container mt-5 text-center">
    <p>Product not found.</p>
    <a href="/" class="btn btn-secondary">Return to Homepage</a>
  </div>
<?php endif; ?>

<script type="text/javascript">
  document.title = "<?= $title = $detail ? $detail['name'] : "Product Not Found" ?>";
</script>

<?php
include 'footer.php';

?>