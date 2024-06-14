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
        borderWidth: 0
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
        borderWidth: 0
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
      labels: ['1a', '1b', '1c', '2a', '2b', '2c', '3a', '3b', '3c', '4a', '4b', '5a', '5b','6a', '6b', '7a','7b','8a','8b','9','10'],
      datasets: [{
        label: '# of Graded',
        data: [2000, 2200, 2100, 2300, 2000, 2200, 2100, 2300, 2000, 2200, 2100, 2300, 2000, 2200, 2100, 2300, 2000, 2200, 2100, 2300, 2000],
        borderWidth: 1
      },
      {
        label: '# of Not Graded',
        data: [400, 200, 300, 100, 400, 200, 300, 100, 400, 200, 300, 100, 400, 200, 300, 100, 400, 200, 300, 100, 400],
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

$checkbox = <<<CHECKBOX
  <h2 class="md-typescale-large">Check out these controls in a form!</h2>
  <h3 class="md-typescale-medium">Filter Chip</h3>
    <md-filter-chip label="2024 Euclid A">
    </md-filter-chip>
    <md-filter-chip label="2024 Euclid b">
    </md-filter-chip>
      <md-divider></md-divider>
  <h3 class="md-typescale-medium">Check box</h3>
  <label><md-checkbox></md-checkbox> 2024 Euclid B</label>
  <label><md-checkbox></md-checkbox> 2024 Euclid C</label>            
CHECKBOX;

$button = <<<BUTTON
  <h2 class="md-typescale-large">Check out these buttons in a form!</h2>
    <md-fab label="Refresh" aria-label="Refresh">
      <md-icon slot="icon">refresh</md-icon>
    </md-fab>

    <md-fab label="Remove" aria-label="Remove">
      <md-icon slot="icon">delete</md-icon>
    </md-fab>

    <md-fab label="Generate" aria-label="Generate">
      <md-icon slot="icon">start</md-icon>
    </md-fab>

BUTTON;

$card = <<<CARD
 <md-divider></md-divider>
 <span style="font-size: 48px;"><md-icon slot="icon">counter_1</md-icon></span>
 <md-icon slot="icon">counter_2</md-icon>
 <span class="material-symbols-outlined" style="font-size: 48px;color: #65558f;
  font-variation-settings: 'OPSZ';">counter_2</span>
CARD;



$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chart</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script type="importmap">
      {
        "imports": {
          "@material/web/": "https://esm.run/@material/web/"
        }
      }
    </script>
    <script type="module">
      import '@material/web/all.js';
      import {styles as typescaleStyles} from '@material/web/typography/md-typescale-styles.js';
  
      document.adoptedStyleSheets.push(typescaleStyles.styleSheet);
    </script>    
    <style>
        @import url('../src/css/light.css');
        .canvas-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px; /* Optional: add spacing between canvases */
        }
        .canvas-container canvas, div {
            width: 100%; /* Ensure canvases take up full grid cell width */
        }
        .material-symbols-outlined {
          font-variation-settings:
          'FILL' 0,
          'wght' 400,
          'GRAD' 0,
          'opsz' 24
        }


    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="canvas-container">
  <!--
    -->
        <div id="contest_name" width="200" height="200" style="border:1px solid #000000;">
          <span>Euclid A 2024</span>
        </div>
        <canvas id="canvas1" width="200" height="200" style="border:0px solid #000000;"></canvas>
        <canvas id="canvas2" width="200" height="200" style="border:0px solid #000000;"></canvas>
        <canvas id="canvas3" width="800" height="200" style="border:0px solid #000000;"></canvas>
</div>
$chart1
$chart2
$chart3
<form>
$checkbox
$button
$card
</form>
</body>
</html>
HTML;

echo $html;
?>