<?php
include 'db.php';

if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    // $query = "SELECT * FROM users WHERE status='approved' AND role='user' AND emp_id LIKE '%$search_query%'";
    
     $query = "SELECT * FROM users WHERE emp_id LIKE '%$search_query%' and role='user' and status='approved'";

    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) > 0) {
        foreach ($query_run as $student) {
            ?>
            <tr>
                <td><?= $student['id']; ?></td>
                <td><?= $student['emp_id']; ?></td>
                <td><?= $student['name']; ?></td>
                <td><?= $student['designation']; ?></td>
                <td><?= $student['password']; ?></td>
                <td><?= $student['status']; ?></td>
                <td><?= $student['role']; ?></td>
                <td class='text-center'>
                    <a href="manage_user-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                    <form action="manage_user-code.php" method="POST" class="d-inline">
                        <button type="submit" name="delete_student" value="<?= $student['id']; ?>" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'><h5 class='text-danger'> No Record Found!!! </h5></td></tr>";
    }
}
?>
