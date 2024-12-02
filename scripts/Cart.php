<?php

class Cart
{
  function add($data)
  {
    $product_id = $data['product_id'];
    $user_id = $data['user_id'];
    $quantity = $data['quantity'];

    // Initialize cart session if it doesn't exist
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    // Check if product is already in cart
    if (isset($_SESSION['cart'][$user_id][$product_id])) {
      echo "
        <script>
            alert('Product sudah ada di cart');
        </script>
        ";
      return false;
    }

    // Add product to cart
    $_SESSION['cart'][$user_id][$product_id] = [
      'product_id' => $product_id,
      'quantity' => $quantity,
    ];

    return true;
  }

  function remove($data)
  {
    $product_id = $data['product_id'];
    $user_id = $data['user_id'];

    // Check if product is in cart
    if (!isset($_SESSION['cart'][$user_id][$product_id])) {
      echo "
        <script>
            alert('Product tidak ada di cart');
        </script>
        ";
      return false;
    }

    // Remove product from cart
    unset($_SESSION['cart'][$user_id][$product_id]);

    return true;
  }

  function get($user_id)
  {
    if (!isset($_SESSION['cart'][$user_id])) {
      return [];
    }

    return $_SESSION['cart'][$user_id];
  }

  function getCartTotal($user_id)
  {
    $cart = getCart($user_id);
    $total = 0;

    foreach ($cart as $product) {
      $product_id = $product['product_id'];
      $quantity = $product['quantity'];

      $product = getProduct($product_id);
      $total += $product['price'] * $quantity;
    }

    return $total;
  }

}