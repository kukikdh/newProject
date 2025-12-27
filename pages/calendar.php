<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="../public/css/4-calendar.css" />

<div class="main-content">
  <div class="calendar-container">
    <div class="calendar-main">
      <h2>Calendar</h2>
      <?php
      include('../config/db.php');
      $user_id = $_SESSION['user_id'];

      $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
      $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

      $firstDay = mktime(0, 0, 0, $month, 1, $year);
      $daysInMonth = date('t', $firstDay);
      $dayOfWeek = date('w', $firstDay);
      $today = date('Y-m-d');
      $currentMonth = date('Y-m');

      // Get all tasks for the month
      $start = sprintf('%04d-%02d-01', $year, $month);
      $end = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);
      $stmt = $pdo->prepare("SELECT id, title, due_date, status FROM tasks WHERE user_id = ? AND due_date BETWEEN ? AND ? ORDER BY due_date, created_at");
      $stmt->execute([$user_id, $start, $end]);
      $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $taskDays = [];
      $overdueDays = [];
      foreach ($tasks as $task) {
        $day = (int)date('j', strtotime($task['due_date']));
        $taskDays[$day][] = $task;
        if ($task['status'] == 'pending' && $task['due_date'] < $today) {
          $overdueDays[$day] = true;
        }
      }

      echo "<h3>" . date('F Y', $firstDay) . "</h3>";
      echo "<table class='calendar-table'>";
      echo "<thead><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead><tbody><tr>";

      // Fill empty cells
      for ($i = 0; $i < $dayOfWeek; $i++) {
        echo "<td class='empty'></td>";
      }

      for ($day = 1; $day <= $daysInMonth; $day++) {
        $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $classes = ['calendar-day'];
        if ($dateStr == $today) $classes[] = 'today';
        if (isset($overdueDays[$day])) $classes[] = 'has-overdue';
        $classStr = implode(' ', $classes);

        if (($day + $dayOfWeek - 1) % 7 == 0 && $day != 1) echo "</tr><tr>";

        echo "<td class='$classStr' data-date='$dateStr'>";
        echo "<div class='day-number'>$day</div>";
        if (isset($taskDays[$day])) {
          $dayTasks = $taskDays[$day];
          $count = count($dayTasks);
          if ($count <= 2) {
            echo "<div class='task-titles'>";
            foreach ($dayTasks as $task) {
              $statusClass = $task['status'] == 'completed' ? 'completed' : 'pending';
              echo "<div class='task-title $statusClass'>" . htmlspecialchars(substr($task['title'], 0, 12)) . "</div>";
            }
            echo "</div>";
          } else {
            echo "<div class='task-dots'>";
            $shown = 0;
            foreach ($dayTasks as $task) {
              if ($shown >= 3) break;
              $dotClass = $task['status'] == 'completed' ? 'completed' : ($task['due_date'] < $today && $task['status'] == 'pending' ? 'overdue' : 'pending');
              echo "<span class='task-dot $dotClass'></span>";
              $shown++;
            }
            if ($count > 3) {
              echo "<span class='more-tasks'>+" . ($count - 3) . "</span>";
            }
            echo "</div>";
          }
        }
        echo "</td>";
      }

      // Fill remaining cells
      while (($day + $dayOfWeek - 1) % 7 != 0) {
        echo "<td class='empty'></td>";
        $day++;
      }

      echo "</tr></tbody></table>";

      // Navigation
      $prevMonth = $month - 1;
      $prevYear = $year;
      if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear--;
      }
      $nextMonth = $month + 1;
      $nextYear = $year;
      if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
      }
      echo "<div class='calendar-nav'>";
      echo "<a href='?month=$prevMonth&year=$prevYear' class='btn'>Previous</a>";
      echo "<a href='?month=$nextMonth&year=$nextYear' class='btn'>Next</a>";
      echo "</div>";
      ?>
    </div>

    <div class="day-details">
      <h3>Day Details</h3>
      <div id="selected-date">Select a date to view tasks</div>
      <div id="day-tasks"></div>
      <button id="add-task-btn" class="btn" style="display:none;">Add Task</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const calendarDays = document.querySelectorAll('.calendar-day');
  const selectedDateEl = document.getElementById('selected-date');
  const dayTasksEl = document.getElementById('day-tasks');
  const addTaskBtn = document.getElementById('add-task-btn');
  let selectedDate = null;

  // Task data from PHP
  const taskData = <?php echo json_encode($taskDays); ?>;

  calendarDays.forEach(day => {
    day.addEventListener('click', function() {
      const date = this.dataset.date;
      const dayNum = parseInt(date.split('-')[2]);

      // Remove previous selection
      document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
      this.classList.add('selected');

      selectedDate = date;
      selectedDateEl.textContent = new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });

      // Show tasks for this day
      const dayTasks = taskData[dayNum] || [];
      dayTasksEl.innerHTML = '';
      if (dayTasks.length === 0) {
        dayTasksEl.innerHTML = '<p>No tasks for this day</p>';
      } else {
        dayTasks.forEach(task => {
          const taskEl = document.createElement('div');
          taskEl.className = 'task-item';
          taskEl.innerHTML = `
            <span class="task-status ${task.status}">${task.status === 'completed' ? '✔' : '○'}</span>
            <span class="task-title">${task.title}</span>
          `;
          dayTasksEl.appendChild(taskEl);
        });
      }

      // Show add task button
      addTaskBtn.style.display = 'block';
      addTaskBtn.onclick = function() {
        window.location.href = `tasks.php?due_date=${date}`;
      };
    });
  });
});
</script>