const ctx = document.getElementById("powerChart").getContext("2d");

// Sample data structure for the chart
const dataListrik = {
  labels: [], // To hold time labels from the API (e.g., "00:01:04", "01:00:00")
  datasets: [
    {
      label: "Arus PLN (A)",
      data: [], // To hold arus values from the API (e.g., 30.00, 5.00)
      borderColor: "blue",
      borderWidth: 2,
      fill: false,
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
      x: {
        type: "category", // Using 'category' instead of 'time' to handle string time labels directly
        title: {
          display: true,
          text: "Time (HH:mm:ss)",
        },
      },
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: "Arus (A)",
        },
      },
    },
    plugins: {
      title: {
        display: true,
        text: "Grafik Arus PLN",
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

// Update chart every 2 seconds with the new data
setInterval(fetchDataArus, 2000);
fetchDataArus(); // Initial call to populate data
