<?php

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
  echo "
    <script>
        alert('Please log in to view your cart.');
        window.location.href = '/auth/login.php';
    </script>
  ";
  exit;
}

$cartItems = getCart($user_id);
$total = getCartTotal($user_id);

// Process remove from cart action if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
  $product_id = $_POST['product_id'];
  removeFromCart([
    'product_id' => $product_id,
    'user_id' => $user_id,
  ]);
  // Redirect back to cart to refresh the cart view after removal
  header("Location: cart.php");
  exit;
}
?>

<div class="container mt-5">
  <h2 class="mb-4">Your Cart</h2>
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
            <th>Action</th>
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
              <td>
                <form method="post">
                  <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                  <input type="hidden" name="action" value="remove">
                  <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                </form>
              </td>
            </tr>
            <?php
            $i++;
          endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
  <div class="cart-total mt-3">
    <h3 class="m-0">Total: <?= 'Rp. ' . number_format($total, 2) ?></h3>
    <span class="text-secondary fs-6">Item total: <?= isset($i) ? $i : 0 ?></span>
  </div>
  <div class="cart-actions mt-3">
    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
  </div>
</div>