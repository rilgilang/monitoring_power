<!DOCTYPE html>
<html lang="en">


<head>

  <?php
  include './const/const.php';
  include("./component/header.php");
  ?>
  <title><?= deviceChecker($_GET['id']) ?></title>
</head>

<body>
  <div class="content" id="content">
    <div class="header">
      <i class="fas fa-bars menu-icon" id="menu-icon"></i>
      <h1>
        <?= deviceChecker($_GET['id']) ?>
      </h1>
      <?php include("./component/notification.php"); ?>
    </div>

    <div class="sidebar" id="sidebar">
      <?php include("./component/sidebar.php"); ?>
    </div>

    <div class="route-info" style="margin-top: 20px;">
      <div class="route-utama">Monitoring <?= deviceChecker($_GET['id']) ?></div>
      <div class="temperature">
        Suhu <span id="device-<?= $_GET['id'] ?>-temperature" class="temperature-value">0°C</span>
      </div>
    </div>

    <div class="main-content">
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Sumber Daya</th>
              <th>Tegangan</th>
              <th>Arus</th>
              <th>SoE</th>
              <th>SoC</th>
              <th>Aktivitas</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><i class="fas fa-bolt"></i> Listrik PLN</td>
              <td id="device-<?= $_GET['id'] ?>-pln-voltage">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-pln-current">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-pln-soe">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-pln-soc">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-pln-activity">Reading...</td>
              <td><span id="device-<?= $_GET['id'] ?>-pln-status" class="status aktif">Reading...</span></td>
            </tr>
            <tr>
              <td><i class="fas fa-car-battery"></i> Accu</td>
              <td id="device-<?= $_GET['id'] ?>-accu-voltage">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-accu-current">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-accu-soe">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-accu-soc">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-accu-activity">Reading...</td>
              <td><span id="device-<?= $_GET['id'] ?>-accu-status" class="status tidak-aktif">Reading...</span></td>
            </tr>
            <tr>
              <td><i class="fas fa-battery-full"></i> Batterai UPS</td>
              <td id="device-<?= $_GET['id'] ?>-ups-voltage">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-ups-current">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-ups-soe">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-ups-soc">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-ups-activity">Reading...</td>
              <td><span id="device-<?= $_GET['id'] ?>-ups-status" class="status aktif">Reading...</span></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="chart-container">
        <div class="chart-header">
          <div class="date" id="currentDate">
            <input type="date" id="datePicker" class="date-picker">
          </div>
          <div class="dropdown-container">
            <select id="chartSelector" class="chart-selector">
              <option value="pln">Grafik Listrik</option>
              <option value="accu">Grafik Accu</option>
              <option value="ups">Grafik UPS</option>
            </select>
          </div>
        </div>
        <div class="canvas-container">
          <canvas id="powerChart" width="800" height="400"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- <script src="js/websocket.js"></script> -->
  <script src="js/deviceData.js"></script>
  <script>
    let intervalId; // To store the interval ID
    const today = new Date().toISOString().split("T")[0];
    const datePicker = document.getElementById("datePicker");

    // Initialize the date picker with today's date
    datePicker.value = today;

    // Function to clear the interval
    function clearUpdateInterval() {
      if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
      }
    }

    // Function to handle date change
    function handleDateChange(selectedDate) {
      if (selectedDate === today) {
        // If the selected date is today, run updateSoCData in an interval
        clearUpdateInterval(); // Clear any existing interval
        intervalId = setInterval(() => {
          fetchData(<?= $_GET['id'] ?>, selectedDate);
        }, 2000); // Update every 2 seconds
      } else {
        // If the selected date is not today, run updateSoCData only once
        clearUpdateInterval(); // Clear any existing interval
        fetchData(<?= $_GET['id'] ?>, selectedDate);
      }
    }

    // Add event listener for date picker changes
    datePicker.addEventListener("change", function(event) {
      const selectedDate = event.target.value;
      handleDateChange(selectedDate);
    });

    // Set initial state to today's date
    handleDateChange(today);
  </script>
  <script src="js/script.js"></script>
</body>

</html>