const ctx = document.getElementById("powerChart").getContext("2d");

// Sample data structure for the chart
const dataListrik = {
  labels: [], // To hold time labels from the API (e.g., "00:01:04", "01:00:00")
  datasets: [
    {
      label: "Tegangan UPS (V)",
      data: [], // To hold arus values from the API (e.g., 30.00, 5.00)
      borderColor: "darkblue",
      borderWidth: 2,
      fill: false,
      yAxisID: "y2",
    },
    {
      label: "Arus PLN (A)",
      data: [], // To hold arus values from the API (e.g., 30.00, 5.00)
      borderColor: "orange",
      borderWidth: 2,
      fill: false,
      yAxisID: "y1",
    },
  ],
};

// Initialize the chart with empty data
let currentChart = new Chart(ctx, {
  type: "line",
  data: dataListrik,
  options: {
    maintainAspectRatio: false,
    scales: {
      y1: {
        type: "linear",
        position: "left",
        beginAtZero: true,
        max: 230, // Skala maksimum untuk tegangan
      },
      y2: {
        type: "linear",
        position: "right",
        beginAtZero: true,
        max: 30, // Skala maksimum untuk arus
        grid: {
          drawOnChartArea: false, // Don't draw grid lines for this axis
        },
      },
    },
    plugins: {
      title: {
        display: true,
        text: "Grafik Tegangan dan Arus",
      },
      tooltip: {
        callbacks: {
          label: function (context) {
            let value = context.raw;
            let time = context.label;
            let label = context.dataset.label || "";
            return `${label}: ${value}${
              context.dataset.yAxisID === "y1" ? "V" : "A"
            } at ${time}`; // Format tooltip
          },
        },
      },
    },
  },
});

// Function to fetch data from the API and update the chart
function fetchDataArus() {
  $.ajax({
    url: "http://localhost:8001/api/getRealtimeDataArus.php?sumberdaya_id=1",
    method: "GET",
    dataType: "json",
    success: function (result) {
      const labels = [];
      const data = [];

      // Process the API result into arrays for Chart.js
      for (const [time, arus] of Object.entries(result)) {
        labels.push(time); // Add each time as a label
        data.push(parseFloat(arus)); // Add each corresponding arus value as a float
      }

      // Update the chart data and labels
      currentChart.data.labels = labels;
      currentChart.data.datasets[0].data = data;

      // Refresh the chart to display the new data
      currentChart.update();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error fetching data:", textStatus, errorThrown);
    },
  });
}

// Function to fetch data from the API and update the chart
function fetchDataTegangan() {
  $.ajax({
    url: "http://localhost:8001/api/getRealtimeDataTegangan.php?sumberdaya_id=1",
    method: "GET",
    dataType: "json",
    success: function (result) {
      const labels = [];
      const data = [];

      // Process the API result into arrays for Chart.js
      for (const [time, tegangan] of Object.entries(result)) {
        labels.push(time); // Add each time as a label
        data.push(parseFloat(tegangan)); // Add each corresponding tegangan value as a float
      }

      // Update the chart data and labels
      currentChart.data.labels = labels;
      currentChart.data.datasets[1].data = data;

      // Refresh the chart to display the new data
      currentChart.update();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error fetching data:", textStatus, errorThrown);
    },
  });
}

// Update chart every 2 seconds with the new data
setInterval(fetchDataArus, 2000);
fetchDataArus(); // Initial call to populate data

// Update chart every 2 seconds with the new data
setInterval(fetchDataTegangan, 2000);
fetchDataTegangan(); // Initial call to populate data
