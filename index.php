<?php
$conn = new mysqli("localhost", "neil", "root", "ticketingdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $priority = $conn->real_escape_string($_POST['priority']);
    $assigned_to = 'Neil'; // fixed as per your request
    $status = 'Open';

    $sql = "INSERT INTO tickets (title, description, priority, assigned_to, status) VALUES ('$title', '$description', '$priority', '$assigned_to', '$status')";

    if ($conn->query($sql) === TRUE) {
        $message = "Ticket created successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Create Ticket</title>
<style>
*,
*::before,
*::after {
  box-sizing: border-box;
}

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f7f8;
    color: #333;
    margin: 0; padding: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 40px 10px;
  }
  .container {
    background: #fff;
    padding: 25px 30px 30px 30px;
    border-radius: 8px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    max-width: 480px;
    width: 100%;
  }
  h1 {
    margin-bottom: 25px;
    font-weight: 700;
    color: #2c3e50;
    text-align: center;
  }
  label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #34495e;
  }
  input[type="text"], textarea, select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1.8px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
  }
  input[type="text"]:focus, textarea:focus, select:focus {
    border-color: #2980b9;
    outline: none;
  }
  textarea {
    resize: vertical;
    min-height: 100px;
  }
  button {
    width: 100%;
    padding: 14px;
    background: #2980b9;
    border: none;
    border-radius: 6px;
    color: white;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  button:hover {
    background: #1f6391;
  }
  .message {
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
    color: green;
  }
  @media (max-width: 520px) {
    .container {
      padding: 20px 20px 25px 20px;
    }
  }
</style>
</head>
<body>

<div class="container">
  <h1>Create a Ticket</h1>

  <?php if ($message): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
  <?php endif; ?>

  <form method="POST" novalidate>
    <label for="title">Title *</label>
    <input type="text" id="title" name="title" required placeholder="Enter ticket title" />

    <label for="description">Description *</label>
    <textarea id="description" name="description" required placeholder="Describe your issue or request"></textarea>

    <label for="priority">Priority *</label>
    <select id="priority" name="priority" required>
      <option value="" disabled selected>Select priority</option>
      <option value="Low">Low</option>
      <option value="Medium">Medium</option>
      <option value="High">High</option>
      <option value="Urgent">Urgent</option>
    </select>

    <button type="submit">Submit Ticket</button>
  </form>
</div>

</body>
</html>
