const ctx = document.getElementById("powerChart").getContext("2d");

// Initial data structure for the chart
const dataListrik = {
  labels: [
    "00:00",
    "01:00",
    "02:00",
    "03:00",
    "04:00",
    "05:00",
    "06:00",
    "07:00",
    "08:00",
    "09:00",
    "10:00",
    "11:00",
    "12:00",
    "13:00",
    "14:00",
    "15:00",
    "16:00",
    "17:00",
    "18:00",
    "19:00",
    "20:00",
    "21:00",
    "22:00",
    "23:00",
  ],
  datasets: [
    {
      label: "Tegangan PLN (V)",
      data: [],
      borderColor: "lime",
      borderWidth: 1,
      yAxisID: "y1",
    },
    {
      label: "Arus PLN (A)",
      data: [],
      borderColor: "blue",
      borderWidth: 2,
      yAxisID: "y2",
    },
  ],
};

// Function to fetch and update data for the chart
function fetchDataTegangan() {
  $.ajax({
    url: "http://localhost:8001/api/getRealtimeDataTegangan.php?sumberdaya_id=1", // Replace with your API endpoint
    method: "GET",
    dataType: "json",
    success: function (result) {
      // Update the dataset with the new data from the API
      dataListrik.datasets[0].data = Object.values(result);
      currentChart.update(); // Refresh the chart to reflect the new data
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error fetching data:", textStatus, errorThrown);
    },
  });
}

// Function to fetch and update data for the chart
function fetchDataArus() {
  $.ajax({
    url: "http://localhost:8001/api/getRealtimeDataArus.php?sumberdaya_id=1", // Replace with your API endpoint
    method: "GET",
    dataType: "json",
    success: function (result) {
      // Update the dataset with the new data from the API
      dataListrik.datasets[1].data = Object.values(result);
      currentChart.update(); // Refresh the chart to reflect the new data
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error fetching data:", textStatus, errorThrown);
    },
  });
}

// Initialize the chart
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
        max: 230, // Adjust as needed
      },
      y2: {
        type: "linear",
        position: "right",
        beginAtZero: true,
        max: 30, // Adjust as needed
        grid: {
          drawOnChartArea: false,
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
            } at ${time}`;
          },
        },
      },
    },
  },
});

// Update chart every 2 seconds with the new data
setInterval(fetchDataTegangan, 2000);
fetchDataTegangan(); // Initial call to populate data

setInterval(fetchDataArus, 2000);
fetchDataArus(); // Initial call to populate data

console.log("data --> ", dataListrik.datasets[1].data);

// Event listener to switch datasets based on selection
document
  .getElementById("chartSelector")
  .addEventListener("change", function () {
    let selectedValue = this.value;
    if (selectedValue === "listrik") {
      currentChart.data = dataListrik;
      currentChart.options.scales.y1.max = 230;
      currentChart.options.scales.y2.max = 30;
    } else if (selectedValue === "accu") {
      currentChart.data = dataAccu;
      currentChart.options.scales.y1.max = 13;
      currentChart.options.scales.y2.max = 20;
    } else if (selectedValue === "ups") {
      currentChart.data = dataUPS;
      currentChart.options.scales.y1.max = 13;
      currentChart.options.scales.y2.max = 10;
    }
    currentChart.update(); // Refresh the chart with the new data and options
  });
