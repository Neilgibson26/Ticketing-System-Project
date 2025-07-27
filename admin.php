<?php
$conn = new mysqli("localhost", "neil", "root", "ticketingdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update ticket status if "resolve" was clicked
if (isset($_GET['resolve'])) {
    $ticketId = (int) $_GET['resolve'];
    $conn->query("UPDATE tickets SET status='Resolved' WHERE id=$ticketId");
}

// Filter by status
$statusFilter = $_GET['status'] ?? 'All';
$sql = "SELECT * FROM tickets";
if ($statusFilter !== 'All') {
    $sql .= " WHERE status = '" . $conn->real_escape_string($statusFilter) . "'";
}
$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background: #f7f9fb;
      padding: 20px;
      color: #333;
    }
    .container {
      max-width: 1200px;
      margin: auto;
    }
    h2 {
      margin-bottom: 20px;
    }
    form {
      margin-bottom: 20px;
    }
    select {
      padding: 6px 10px;
      font-size: 1rem;
    }
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 10px;
    }
    th, td {
      text-align: left;
      padding: 10px 14px;
      background: #fff;
      font-size: 0.95rem;
      white-space: nowrap;
    }
    th {
      background-color: #eef1f5;
      font-weight: bold;
      border-bottom: 2px solid #dce3ea;
    }
    tbody tr {
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      border-radius: 6px;
    }
    .badge {
      padding: 4px 10px;
      border-radius: 12px;
      color: #fff;
      font-size: 0.85rem;
      font-weight: 600;
    }
    .status-Open { background-color: #f39c12; }
    .status-Resolved { background-color: #27ae60; }
    .priority-High { background-color: #e74c3c; }
    .priority-Medium { background-color: #f39c12; }
    .priority-Low { background-color: #3498db; }
    .action-link {
      color: #007bff;
      text-decoration: underline;
      cursor: pointer;
    }
    .action-link:hover {
      color: #0056b3;
    }
    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Admin Dashboard</h2>

  <form method="GET">
    <label for="status">Filter by status:</label>
    <select name="status" onchange="this.form.submit()">
      <option value="All" <?= $statusFilter == 'All' ? 'selected' : '' ?>>All</option>
      <option value="Open" <?= $statusFilter == 'Open' ? 'selected' : '' ?>>Open</option>
      <option value="Resolved" <?= $statusFilter == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
    </select>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Assigned To</th>
        <th>Created At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
            $priority = $row['priority'] ?? 'N/A';
            $priorityClass = in_array($priority, ['High', 'Medium', 'Low']) ? $priority : 'Low';
          ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td title="<?= htmlspecialchars($row['title']) ?>"><?= htmlspecialchars(mb_strimwidth($row['title'], 0, 50, '...')) ?></td>
            <td>
              <span class="badge status-<?= htmlspecialchars($row['status']) ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
            </td>
            <td>
              <span class="badge priority-<?= htmlspecialchars($priorityClass) ?>">
                <?= htmlspecialchars($priority) ?>
              </span>
            </td>
            <td><?= htmlspecialchars($row['assigned_to'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td>
              <?php if ($row['status'] !== 'Resolved'): ?>
                <a class="action-link" href="admin.php?resolve=<?= $row['id'] ?>&status=<?= $statusFilter ?>">Mark as Resolved</a>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" style="text-align:center;">No tickets found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>

<?php $conn->close(); ?>
