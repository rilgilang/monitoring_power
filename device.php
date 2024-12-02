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
      <h1><?= deviceChecker($_GET['id']) ?></h1>
      <div style="position: relative;">
        <i class="fas fa-bell notification-icon" id="notification-icon"></i>
        <span class="notification-badge" id="notification-badge">0</span>
      </div>
      <div id="notification-message" class="notification-message">Tidak ada notifikasi baru</div>
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
              <th>Aktivitas</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><i class="fas fa-bolt"></i> Listrik PLN</td>
              <td id="device-<?= $_GET['id'] ?>-pln-voltage">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-pln-current">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-pln-activity">Reading...</td>
              <td><span id="device-<?= $_GET['id'] ?>-pln-status" class="status aktif">Reading...</span></td>
            </tr>
            <tr>
              <td><i class="fas fa-car-battery"></i> Accu</td>
              <td id="device-<?= $_GET['id'] ?>-accu-voltage">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-accu-current">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-accu-activity">Reading...</td>
              <td><span id="device-<?= $_GET['id'] ?>-accu-status" class="status tidak-aktif">Reading...</span></td>
            </tr>
            <tr>
              <td><i class="fas fa-battery-full"></i> Batterai UPS</td>
              <td id="device-<?= $_GET['id'] ?>-ups-voltage">Reading...</td>
              <td id="device-<?= $_GET['id'] ?>-ups-current">Reading...</td>
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
              <option value="listrik">Grafik Listrik</option>
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

  <script src="js/websocket.js"></script>
  <script src="js/deviceData.js"></script>
  <script>
    // Update Date Functionality
    function updateDate() {
      const now = new Date();
      const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        weekday: "long"
      };
      const dateStr = now.toLocaleDateString("id-ID", options);

      document.getElementById("currentDate").querySelector("span").textContent = dateStr;
      document.getElementById("datePicker").value = now.toISOString().split("T")[0];
    }

    let isDateSelected = false
    let dateselected = null

    document.getElementById("datePicker").addEventListener("change", function(event) {
      isDateSelected = true
      const rawDate = event.target.value; // Get the value from the datepicker
      const selectedDate = new Date(rawDate); // Parse it into a Date object
      fetchData(1, selectedDate.toISOString().split("T")[0]) // Call your main function
    });

    // if (!isDateSelected){
    //   // Set an interval to fetch data without a date if no change event is triggered
    //   setInterval(fetchData, 2000);
    // }else{
    //   console.log("isDateSelected --> ", isDateSelected)
    //   fetchData(1, dateselected);
    //   isDateSelected = false;
    // }

    updateDate();

    // Notification Logic
    const menuIcon = document.getElementById("menu-icon");
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");
    const notificationIcon = document.getElementById("notification-icon");
    const notificationMessage = document.getElementById("notification-message");
    const notificationBadge = document.getElementById("notification-badge");

    const notifications = [
      "Notifikasi 1: Sistem berjalan dengan baik.",
      "Notifikasi 2: Pembaruan perangkat lunak tersedia.",
      "Notifikasi 3: Perubahan status pada sumber daya.",
    ];

    let displayedNotifications = [];

    function showNotification() {
      const randomIndex = Math.floor(Math.random() * notifications.length);
      const newNotification = notifications[randomIndex];

      if (!displayedNotifications.includes(newNotification)) {
        displayedNotifications.push(newNotification);
        const notificationDiv = document.createElement("div");
        notificationDiv.textContent = newNotification;
        notificationDiv.className = "notification-item";
        notificationMessage.appendChild(notificationDiv);

        notificationBadge.textContent = displayedNotifications.length;
      }
    }

    setInterval(showNotification, 5000);

    menuIcon.addEventListener("click", () => {
      sidebar.classList.toggle("active");
      sidebar.classList.toggle("small");
      content.classList.toggle("active");
    });

    notificationIcon.addEventListener("click", () => {
      notificationMessage.style.display =
        notificationMessage.style.display === "none" || notificationMessage.style.display === "" ?
        "block" :
        "none";
    });

    document.addEventListener("click", (event) => {
      if (!notificationIcon.contains(event.target) && !notificationMessage.contains(event.target)) {
        notificationMessage.style.display = "none";
      }
    });
  </script>
</body>

</html>