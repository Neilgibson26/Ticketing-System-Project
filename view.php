<?php
$conn = new mysqli("localhost", "neil", "root", "ticketingdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, description, status, created_at, priority, assigned_to FROM tickets ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Tickets View</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9fafb;
    margin: 0;
    padding: 20px;
    color: #333;
  }
  .container {
    max-width: 1200px;
    margin: 0 auto;
  }
  h2 {
    margin-bottom: 20px;
    font-weight: 700;
    color: #222;
  }
  .table-wrapper {
    overflow-x: auto;
  }
  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
    min-width: 900px;
  }
  th, td {
    padding: 12px 15px;
    vertical-align: middle;
    font-size: 0.95rem;
    color: #444;
    white-space: nowrap;
  }
  th {
    text-align: left;
    font-weight: 700;
    color: #555;
    border-bottom: 2px solid #e1e8ed;
  }
  td.title {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  td.description {
    max-width: 350px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    color: #007bff;
    text-decoration: underline;
  }
  td.description:hover {
    color: #0056b3;
  }
  td.priority {
    width: 100px;
    text-align: center;
  }
  td.created_at {
    width: 140px;
  }
  td.status {
    width: 110px;
    text-align: center;
  }
  td.assigned_to {
    width: 120px;
    text-align: center;
  }
  .badge {
    display: inline-block;
    padding: 4px 10px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 12px;
    color: white;
    min-width: 70px;
    text-align: center;
  }
  .status-Open {
    background-color: #f39c12;
  }
  .status-Resolved {
    background-color: #27ae60;
  }
  .priority-High {
    background-color: #e74c3c;
  }
  .priority-Medium {
    background-color: #f39c12;
  }
  .priority-Low {
    background-color: #3498db;
  }
  tbody tr {
    background: white;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.05);
    border-radius: 8px;
    transition: box-shadow 0.3s ease;
  }
  tbody tr:hover {
    box-shadow: 0 6px 12px rgb(0 0 0 / 0.1);
  }
  @media (max-width: 768px) {
    td.title {
      max-width: 150px;
    }
    td.description {
      max-width: 220px;
    }
  }

  /* Modal styles */
  .modal {
    display: none; 
    position: fixed; 
    z-index: 9999; 
    padding-top: 80px; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgba(0,0,0,0.5);
  }
  .modal-content {
    background-color: #fff;
    margin: auto;
    padding: 20px 30px;
    border-radius: 8px;
    max-width: 600px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    position: relative;
  }
  .modal-close {
    color: #aaa;
    position: absolute;
    right: 15px;
    top: 12px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }
  .modal-close:hover,
  .modal-close:focus {
    color: #000;
    text-decoration: none;
  }
  .modal-title {
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 1.2rem;
  }
  .modal-description {
    white-space: pre-wrap; /* preserve line breaks */
    font-size: 1rem;
    color: #444;
  }
</style>
</head>
<body>
<div class="container">
  <h2>Tickets List</h2>
  <div class="table-wrapper">
    <table aria-describedby="tickets-description">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Status</th>
          <th>Priority</th>
          <th>Created At</th>
          <th>Assigned To</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
            $priority = $row['priority'] ?? 'N/A';
            $priorityClass = in_array($priority, ['High','Medium','Low']) ? $priority : 'Low';
          ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td class="title" title="<?= htmlspecialchars($row['title']) ?>"><?= htmlspecialchars($row['title']) ?></td>
            <td class="description" tabindex="0" role="button" aria-label="View full description"
                data-full="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>"
                data-title="<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>">
              <?= htmlspecialchars(mb_strimwidth($row['description'], 0, 80, '...')) ?>
            </td>
            <td class="status">
              <span class="badge status-<?= htmlspecialchars($row['status']) ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
            </td>
            <td class="priority">
              <span class="badge priority-<?= htmlspecialchars($priorityClass) ?>">
                <?= htmlspecialchars($priority) ?>
              </span>
            </td>
            <td class="created_at"><?= htmlspecialchars($row['created_at']) ?></td>
            <td class="assigned_to"><?= htmlspecialchars($row['assigned_to']) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" style="text-align:center; padding: 20px;">No tickets found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal structure -->
<div id="descriptionModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc">
  <div class="modal-content">
    <span class="modal-close" aria-label="Close modal">&times;</span>
    <h3 id="modalTitle" class="modal-title"></h3>
    <p id="modalDesc" class="modal-description"></p>
  </div>
</div>

<script>
  const modal = document.getElementById('descriptionModal');
  const modalTitle = document.getElementById('modalTitle');
  const modalDesc = document.getElementById('modalDesc');
  const modalClose = modal.querySelector('.modal-close');

  // Show modal with full description
  function showModal(title, description) {
    modalTitle.textContent = title;
    modalDesc.textContent = description;
    modal.style.display = 'block';
    modalClose.focus();
  }

  // Close modal function
  function closeModal() {
    modal.style.display = 'none';
  }

  // Click event on description cells
  document.querySelectorAll('td.description').forEach(td => {
    td.addEventListener('click', () => {
      const fullText = td.getAttribute('data-full');
      const title = td.getAttribute('data-title');
      showModal(title, fullText);
    });

    // Allow keyboard users to open modal with Enter or Space
    td.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        const fullText = td.getAttribute('data-full');
        const title = td.getAttribute('data-title');
        showModal(title, fullText);
      }
    });
  });

  // Close modal when clicking close button
  modalClose.addEventListener('click', closeModal);

  // Close modal on clicking outside modal-content
  window.addEventListener('click', e => {
    if (e.target === modal) {
      closeModal();
    }
  });

  // Close modal on pressing Escape key
  window.addEventListener('keydown', e => {
    if (e.key === 'Escape' && modal.style.display === 'block') {
      closeModal();
    }
  });
</script>

</body>
</html>

<?php
$conn->close();
?>
