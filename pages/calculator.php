<?php include('../includes/header.php'); ?>

<div class="main-content">
  <h2>Calculator</h2>
  <div id="calculator">
    <input type="text" id="display" readonly>
    <br>
    <button onclick="append('7')">7</button>
    <button onclick="append('8')">8</button>
    <button onclick="append('9')">9</button>
    <button onclick="operation('/')">/</button>
    <br>
    <button onclick="append('4')">4</button>
    <button onclick="append('5')">5</button>
    <button onclick="append('6')">6</button>
    <button onclick="operation('*')">*</button>
    <br>
    <button onclick="append('1')">1</button>
    <button onclick="append('2')">2</button>
    <button onclick="append('3')">3</button>
    <button onclick="operation('-')">-</button>
    <br>
    <button onclick="append('0')">0</button>
    <button onclick="append('.')">.</button>
    <button onclick="calculate()">=</button>
    <button onclick="operation('+')">+</button>
    <br>
    <button onclick="clearDisplay()">C</button>
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