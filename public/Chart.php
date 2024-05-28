<?php
$chart1 = <<<CHARTONE
<script>
  const ctx1 = document.getElementById('canvas1');

  new Chart(ctx1, {
    type: 'doughnut',
    data: {
      labels: ['Uploaded', 'Not Uploaded'],
      datasets: [{
        label: '# of Booklets',
        data: [2377, 123],
        borderWidth: 1
      }]
    },
    options: {
      responsive: false,
      maintainAspectRatio: true
    }
  });
</script>
CHARTONE;

$chart2 = <<<CHARTTWO
<script>
  const ctx2 = document.getElementById('canvas2');

  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ['Matched', 'Not Matched'],
      datasets: [{
        label: '# of Booklets',
        data: [2177, 200],
        borderWidth: 1
      }]
    },
    options: {
      responsive: false,
      maintainAspectRatio: true
    }
  });
</script>
CHARTTWO;

$chart3 = <<<CHARTTHREE
<script>
  const ctx3 = document.getElementById('canvas3');

  new Chart(ctx3, {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Graded',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      },
      {
        label: '# of Not Graded',
        data: [20, 29, 13, 15, 12, 13],
        borderWidth: 1
      }]
    },
    options: {
      responsive: false,
      maintainAspectRatio: true,
      scales: {
        x: {
            stacked: true,
          },
          y: {
            stacked: true,
          }
      }
    }
  });
</script>
CHARTTHREE;


$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chart</title>
    <style>
        .canvas-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px; /* Optional: add spacing between canvases */
        }
        .canvas-container canvas {
            width: 100%; /* Ensure canvases take up full grid cell width */
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="canvas-container">
        <canvas id="canvas1" width="200" height="200" style="border:1px solid #000000;"></canvas>
        <canvas id="canvas2" width="200" height="200" style="border:1px solid #000000;"></canvas>
        <canvas id="canvas3" width="800" height="200" style="border:1px solid #000000;"></canvas>
</div>
$chart1
$chart2
$chart3
</body>
</html>
HTML;

echo $html;
?>