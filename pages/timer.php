<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="../public/css/04-timer.css" />

<div class="main-content">
  <h2>Focus Timer</h2>
  <div id="timer">00:00:00</div>
  <button id="start">Start</button>
  <button id="pause">Pause</button>
  <button id="reset">Reset</button>
  <br><br>
  <input type="text" id="label" placeholder="Session Label (optional)">
  <br><br>
  <div id="total">Today's Total Focus Time: Loading...</div>

  <form id="sessionForm" method="post" style="display:none;">
    <input type="hidden" name="start_time" id="start_time">
    <input type="hidden" name="end_time" id="end_time">
    <input type="hidden" name="duration" id="duration">
    <input type="hidden" name="label" id="form_label">
  </form>

  <?php
  include('../config/db.php');
  $user_id = $_SESSION['user_id'];

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $duration = $_POST['duration'];
    $label = trim($_POST['label']);
    $date = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO timer_sessions (user_id, start_time, end_time, duration_seconds, date, label) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $start_time, $end_time, $duration, $date, $label]);
    echo "<p>Session saved!</p>";
  }

  // Get today's total
  $today = date('Y-m-d');
  $stmt = $pdo->prepare("SELECT SUM(duration_seconds) as total FROM timer_sessions WHERE user_id = ? AND date = ?");
  $stmt->execute([$user_id, $today]);
  $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?: 0;
  $hours = floor($total / 3600);
  $minutes = floor(($total % 3600) / 60);
  $seconds = $total % 60;
  echo "<script>document.getElementById('total').innerText = \"Today's Total Focus Time: {$hours}h {$minutes}m {$seconds}s\";</script>";
  ?>
</div>

<script>
let timerInterval;
let startTime;
let elapsed = 0;
let running = false;

const timerDisplay = document.getElementById('timer');
const startBtn = document.getElementById('start');
const pauseBtn = document.getElementById('pause');
const resetBtn = document.getElementById('reset');
const labelInput = document.getElementById('label');
const form = document.getElementById('sessionForm');

function updateDisplay() {
  const totalSeconds = Math.floor(elapsed / 1000);
  const hours = Math.floor(totalSeconds / 3600);
  const minutes = Math.floor((totalSeconds % 3600) / 60);
  const seconds = totalSeconds % 60;
  timerDisplay.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

startBtn.addEventListener('click', () => {
  if (!running) {
    running = true;
    startTime = Date.now() - elapsed;
    timerInterval = setInterval(() => {
      elapsed = Date.now() - startTime;
      updateDisplay();
    }, 1000);
    document.getElementById('start_time').value = new Date().toISOString();
  }
});

pauseBtn.addEventListener('click', () => {
  if (running) {
    running = false;
    clearInterval(timerInterval);
    const endTime = new Date().toISOString();
    const duration = Math.floor(elapsed / 1000);
    document.getElementById('end_time').value = endTime;
    document.getElementById('duration').value = duration;
    document.getElementById('form_label').value = labelInput.value;
    form.submit();
  }
});

resetBtn.addEventListener('click', () => {
  running = false;
  clearInterval(timerInterval);
  elapsed = 0;
  updateDisplay();
  labelInput.value = '';
  // If running, save current session
  if (startTime) {
    const endTime = new Date().toISOString();
    const duration = Math.floor((Date.now() - startTime) / 1000);
    document.getElementById('end_time').value = endTime;
    document.getElementById('duration').value = duration;
    document.getElementById('form_label').value = labelInput.value;
    form.submit();
  }
});

updateDisplay();
</script>