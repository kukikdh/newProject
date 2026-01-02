<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="../public/css/4-tasks.css" />

<div class="main-content">
  <div class="tasks-container">
    <h1>Tasks</h1>

    <!-- Add New Task Card -->
    <div class="card">
      <h2>Add New Task</h2>
      <?php if (isset($error)): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method="post" class="task-form">
        <div class="form-group">
          <label for="title">Task Title</label>
          <input type="text" id="title" name="title" placeholder="Enter task title" required>
        </div>

        <div class="form-group">
          <label for="description">Description (optional)</label>
          <textarea id="description" name="description" placeholder="Enter task description" rows="3"></textarea>
        </div>

        <div class="form-group">
          <label for="due_date">Due Date (optional)</label>
          <input type="date" id="due_date" name="due_date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($_GET['due_date']) ? htmlspecialchars($_GET['due_date']) : ''; ?>">
        </div>

        <div class="form-group">
          <label for="reminder_time">Reminder Time (optional)</label>
          <input type="datetime-local" id="reminder_time" name="reminder_time" min="<?php echo date('Y-m-d\TH:i'); ?>">
        </div>

        <button type="submit" name="add_task" class="btn primary-btn">Add Task</button>
      </form>
    </div>

    <!-- Tasks Card -->
    <div class="card">
      <h2>Your Tasks</h2>
      <div class="tasks-list">
        <?php
        include('../config/db.php');
        $user_id = $_SESSION['user_id'];

        if (isset($_POST['add_task'])) {
          $title = trim($_POST['title']);
          $description = trim($_POST['description']);
          $due_date = $_POST['due_date'] ?: null;
          $reminder_time = $_POST['reminder_time'] ?: null;

          // Validate due date is not in the past
          if ($due_date && $due_date < date('Y-m-d')) {
            $error = "Due date cannot be in the past.";
          } elseif ($reminder_time && strtotime($reminder_time) <= time()) {
            $error = "Reminder time must be in the future.";
          } else {
            $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $title, $description, $due_date]);
            $task_id = $pdo->lastInsertId();
            if ($reminder_time) {
              $stmt = $pdo->prepare("INSERT INTO reminders (task_id, reminder_time) VALUES (?, ?)");
              $stmt->execute([$task_id, $reminder_time]);
            }
            header('Location: tasks.php');
          }
        }

        if (isset($_GET['complete'])) {
          $id = $_GET['complete'];
          $stmt = $pdo->prepare("UPDATE tasks SET status = CASE WHEN status = 'pending' THEN 'completed' ELSE 'pending' END WHERE id = ? AND user_id = ?");
          $stmt->execute([$id, $user_id]);
          header('Location: tasks.php');
        }

        if (isset($_GET['delete'])) {
          $id = $_GET['delete'];
          $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
          $stmt->execute([$id, $user_id]);
          header('Location: tasks.php');
        }

        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($tasks) {
          foreach ($tasks as $task) {
            $statusClass = $task['status'] == 'completed' ? 'completed' : 'pending';
            $statusText = $task['status'] == 'completed' ? 'Completed' : 'Pending';
            $dueText = $task['due_date'] ? date('M j, Y', strtotime($task['due_date'])) : 'No due date';
            $overdue = $task['due_date'] && $task['status'] == 'pending' && $task['due_date'] < date('Y-m-d') ? 'overdue' : '';

            echo "<div class='task-item $statusClass $overdue'>";
            echo "<div class='task-content'>";
            echo "<h3 class='task-title'>" . htmlspecialchars($task['title']) . "</h3>";
            if ($task['description']) {
              echo "<p class='task-description'>" . htmlspecialchars($task['description']) . "</p>";
            }
            echo "<div class='task-meta'>";
            echo "<span class='task-status $statusClass'>$statusText</span>";
            echo "<span class='task-due'>Due: $dueText</span>";
            echo "</div>";
            echo "</div>";
            echo "<div class='task-actions'>";
            echo "<a href='?complete={$task['id']}' class='btn small-btn " . ($task['status'] == 'completed' ? 'secondary-btn' : 'primary-btn') . "'>";
            echo $task['status'] == 'completed' ? 'Undo' : 'Complete';
            echo "</a>";
            echo "<a href='?delete={$task['id']}' class='btn small-btn danger-btn' onclick=\"return confirm('Are you sure you want to delete this task?')\">Delete</a>";
            echo "</div>";
            echo "</div>";
          }
        } else {
          echo "<div class='no-tasks'>";
          echo "<p>You don't have any tasks yet. Add your first task above!</p>";
          echo "</div>";
        }
        ?>
      </div>
    </div>
  </div>
</div>