<?php
include('../includes/auth_check.php');
requireLogin();
include('../includes/header.php');
include('../config/db.php');
$user_id = $_SESSION['user_id'];

// Check for due reminders
$stmt = $pdo->prepare("SELECT t.title FROM reminders r JOIN tasks t ON r.task_id = t.id WHERE t.user_id = ? AND t.status = 'pending' AND r.reminder_time <= NOW() ORDER BY r.reminder_time ASC LIMIT 1");
$stmt->execute([$user_id]);
$reminder = $stmt->fetch();
if ($reminder) {
    echo "<script>alert('Reminder: " . addslashes($reminder['title']) . "');</script>";
}

// Success message
if(isset($_SESSION['success'])) {
    echo "<script>alert('".$_SESSION['success']."');</script>";
    unset($_SESSION['success']);
}

// KPI Data
$today = date('Y-m-d');
$weekStart = date('Y-m-d', strtotime('monday this week'));

// Focus Today
$stmt = $pdo->prepare("SELECT SUM(duration_seconds) as total FROM timer_sessions WHERE user_id = ? AND date = ?");
$stmt->execute([$user_id, $today]);
$result = $stmt->fetch();
$focusToday = $result ? $result['total'] : 0;
$focusHours = floor($focusToday / 3600);
$focusMins = floor(($focusToday % 3600) / 60);

// Pending Tasks
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$result = $stmt->fetch();
$pendingTasks = $result ? $result['count'] : 0;

// Completed This Week
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND status = 'completed' AND created_at >= ?");
$stmt->execute([$user_id, $weekStart . ' 00:00:00']);
$result = $stmt->fetch();
$completedWeek = $result ? $result['count'] : 0;

// Next Due Task
$stmt = $pdo->prepare("SELECT title FROM tasks WHERE user_id = ? AND status = 'pending' AND due_date IS NOT NULL ORDER BY due_date ASC LIMIT 1");
$stmt->execute([$user_id]);
$result = $stmt->fetch();
$nextDue = $result ? $result['title'] : 'No upcoming tasks';

// Today's Tasks
$stmt = $pdo->prepare("SELECT title, status FROM tasks WHERE user_id = ? AND (due_date = ? OR due_date IS NULL) ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id, $today]);
$todayTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../public/css/4-dashboard.css" />

<div class="main-content">
    <div class="dashboard-wrapper">
        <div class="title">
            <h1>Dashboard</h1>
        </div>
        <div class="welcome">
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
        </div>
    <!-- KPI Cards -->
    <div class="stats-grid">
        <div class="card">
            <h3>Focus Today ⏱️</h3>
            <p><?php echo $focusHours; ?>h <?php echo $focusMins; ?>m</p>
        </div>
        <div class="card">
            <h3>Pending Tasks </h3>
            <p><?php echo $pendingTasks; ?></p>
        </div>
        <div class="card">
            <h3>Completed (Week) </h3>
            <p><?php echo $completedWeek; ?></p>
        </div>
        <div class="card">
            <h3>Next Due </h3>
            <p><?php echo htmlspecialchars(substr($nextDue, 0, 20)); ?></p>
        </div>
    </div>
    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Today's Tasks -->
            <div class="card">
                <h3>Today's Tasks</h3>
                <ul class="task-list">
                    <?php if ($todayTasks): ?>
                        <?php foreach ($todayTasks as $task): ?>
                            <li><?php echo $task['status'] == 'completed' ? '✔' : '◻'; ?> <?php echo htmlspecialchars($task['title']); ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No tasks for today</li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

        <!-- Right Column -->
        <div class="right-column">
            <!-- Quick Actions haru -->
            <div class="card">
                <h3>Quick Actions</h3>
                <a href="tasks.php" class="btn"> Add Task</a>
                <a href="timer.php" class="btn">▶ Start Timer</a>
            </div>

            <!-- study streak ko bareyma -->
            <div class="card">
                <h3>Study Streak </h3>
                <p>5 days in a row</p> <!-- Placeholder -->
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<script src="../public/js/script.js"></script>
</body>
</html>
