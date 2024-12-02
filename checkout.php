<?php
include 'header.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
  echo "
    <script>
        alert('Please log in to view this.');
        window.location.href = '/auth/login.php';
    </script>
  ";
  exit;
}

$cartItems = getCart($user_id);
$total = getCartTotal($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
  $data = [
    'user_id' => $user_id,
    'total' => $total
  ];

  if (checkout($data)) {
    echo "
      <script>
          alert('Checkout successful');
          window.location.href = '/cart.php';
      </script>
      ";
  } else {
    echo "
      <script>
          alert('Checkout failed');
          window.location.href = '/checkout.php';
      </script>
      ";
  }
}

// var_dump(getCart($user_id));exit;
?>

<div class="container mt-5">
  <h2>Your Items</h2>
  <div class="row">
    <div class="col-8">
      <?php if (empty($cartItems)): ?>
        <hr>
        <div class="alert alert-warning" role="alert">
          Your cart is empty.
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody class="table-group-divider">
              <?php
              $i = 0;
              foreach ($cartItems as $item):
                $product = getProduct($item['product_id']);
                ?>
                <tr>
                  <td class="align-middle"><?= $i + 1 ?></td>
                  <td>
                    <a href="detail.php?id=<?= htmlspecialchars($product['id']) ?>" class="text-white fw-bold">
                      <?= htmlspecialchars($product['name']) ?>
                    </a>
                  </td>
                  <td><?= 'Rp. ' . number_format($product['price'], 2) ?></td>
                  <td><?= htmlspecialchars($item['quantity']) ?></td>
                  <td><?= 'Rp. ' . number_format($product['price'] * $item['quantity'], 2) ?></td>
                </tr>
                <?php
                $i++;
              endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
    <div class="col-4 border mt-3 py-5 rounded shadow-sm">
      <div class="cart-total">
        <h3 class="m-0">Total: <?= 'Rp. ' . number_format($total, 2) ?></h3>
        <span class="text-secondary fs-6">Item total: <?= isset($i) ? $i : 0 ?></span>
      </div>
      <div class="cart-actions mt-3">
        <?php if (!empty($cartItems)): ?>
          <form method="post">
            <input type="hidden" name="checkout" value="1">
            <button type="submit" class="btn btn-primary">Proceed</button>
          </form>
        <?php else: ?>
          <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php
include 'footer.php';
