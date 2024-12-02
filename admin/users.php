<?php

include '../header.php';

if ($_SESSION['is_admin'] != 1) {
  header('Location: /');
  exit;
}

$users = query("SELECT * FROM users");
?>

<div class="container mt-5">
  <h2 class="mb-4">Users</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Username</th>
        <th scope="col">Role</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php foreach ($users as $user) : ?>
        <tr>
          <th scope="row"><?= $i; ?></th>
          <td><?= $user['username']; ?></td>
          <td><?= $user['is_admin'] ? 'Admin' : 'User'; ?></td>
          <td>
            <a href="edit.php?id=<?= $user['id']; ?>" class="btn btn-warning">Edit</a>
            <a href="remove.php?id=<?= $user['id']; ?>" class="btn btn-danger">Remove</a>
          </td>
        </tr>
        <?php $i++; ?>
      <?php endforeach; ?>
    </tbody>
  </table>

  <a href="add.php" class="btn btn-primary">Add User</a>

</div>

<?php

include '../footer.php';

