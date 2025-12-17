console.log("JS connected to dashboard!");

// Chart.js example
const ctx = document.getElementById('lineChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Study Hours',
            data: [2, 3, 4, 1, 5, 2, 0],
            borderColor: '#6a4fb3',
            backgroundColor: 'rgba(106, 79, 179, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});