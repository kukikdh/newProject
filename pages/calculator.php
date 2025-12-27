<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="../public/css/4-calculator.css" />

<div class="main-content">
  <div id="calculator">
    <h2>Calculator</h2>
    <input type="text" id="display" readonly>
    <div class="buttons">
      <button onclick="append('7')">7</button>
      <button onclick="append('8')">8</button>
      <button onclick="append('9')">9</button>
      <button onclick="operation('/')">/</button>
      <button onclick="append('4')">4</button>
      <button onclick="append('5')">5</button>
      <button onclick="append('6')">6</button>
      <button onclick="operation('*')">*</button>
      <button onclick="append('1')">1</button>
      <button onclick="append('2')">2</button>
      <button onclick="append('3')">3</button>
      <button onclick="operation('-')">-</button>
      <button onclick="append('0')">0</button>
      <button onclick="append('.')">.</button>
      <button onclick="calculate()">=</button>
      <button onclick="operation('+')">+</button>
      <button onclick="clearDisplay()">C</button>
    </div>
  </div>
</div>

<script>
let display = document.getElementById('display');
let currentInput = '';
let operator = '';
let previousInput = '';

function append(value) {
  currentInput += value;
  display.value = currentInput;
}

function operation(op) {
  if (currentInput === '') return;
  if (previousInput !== '') calculate();
  operator = op;
  previousInput = currentInput;
  currentInput = '';
}

function calculate() {
  if (previousInput === '' || currentInput === '') return;
  let result;
  const prev = parseFloat(previousInput);
  const curr = parseFloat(currentInput);
  switch (operator) {
    case '+': result = prev + curr; break;
    case '-': result = prev - curr; break;
    case '*': result = prev * curr; break;
    case '/': result = prev / curr; break;
  }
  display.value = result;
  currentInput = result.toString();
  previousInput = '';
  operator = '';
}

function clearDisplay() {
  display.value = '';
  currentInput = '';
  previousInput = '';
  operator = '';
}
</script>