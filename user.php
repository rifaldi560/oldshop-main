<?php
include './header.php';

$user = getUser($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $update_data = [
    'id' => $user['id'],
    'username' => $_POST['username'] ?? '',
    'firstname' => $_POST['firstname'] ?? '',
    'lastname' => $_POST['lastname'] ?? '',
    'email' => $_POST['email'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'address' => $_POST['address'] ?? ''
  ];

  if (editUser($update_data)) {
    echo "<script>alert('User details updated successfully');</script>";
    // Reload user data after update
    $user = getUser($_SESSION['user_id']);
    echo "<script>window.location.href = '/user.php';</script>";
  } else {
    echo "<script>alert('Failed to update user details');</script>";
  }
}

?>

<style>
  .text-input {
    border: none;
    background: transparent;
    width: 100%;
    padding: 0;
    font-size: 1rem;
  }

  .text-input:focus {
    outline: none;
    border-bottom: 1px var(--bs-secondary-color) solid;
  }

  .text-input[readonly] {
    pointer-events: none;
  }

  .table th,
  .table td {
    vertical-align: middle;
  }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1>Welcome, <?= htmlspecialchars($user['username'] ?? '') ?></h1>
      <p>Here are your details:</p>
      <form method="POST">
        <table class="table">
          <tr>
            <th>Username</th>
            <td><input class="text-input" type="text" name="username"
                value="<?= htmlspecialchars($user['username'] ?? '') ?>" required></td>
          </tr>
          <tr>
            <th>First Name</th>
            <td><input class="text-input" type="text" name="firstname"
                value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required></td>
          </tr>
          <tr>
            <th>Last Name</th>
            <td><input class="text-input" type="text" name="lastname"
                value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required></td>
          </tr>
          <tr>
            <th>Email</th>
            <td><input class="text-input" type="email" name="email"
                value="<?= htmlspecialchars($user['email'] ?? '') ?>" required></td>
          </tr>
          <tr>
            <th>Phone</th>
            <td><input class="text-input" type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                required></td>
          </tr>
          <tr>
            <th>Address</th>
            <td><textarea class="text-input" type="text"
                name="address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea></td>
          </tr>
          <tr>
            <td colspan="2"><button type="submit" class="btn btn-primary">Update</button></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>

<?php
include './footer.php';
?>