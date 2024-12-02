const ctx = document.getElementById("powerChart").getContext("2d");


export const dataListrik = {
  labels: [], // To hold time labels from the API (e.g., "00:01:04", "01:00:00")
  datasets: [
    {
      label: "Tegangan PLN (V)",
      labelKey: "pln_volt",
      data: [],
      borderColor: "darkblue",
      borderWidth: 2,
      fill: false,
      yAxisID: "y1",
    },
    {
      label: "Arus PLN (A)",
      labelKey: "pln_current",
      data: [],
      borderColor: "orange",
      borderWidth: 2,
      fill: false,
      yAxisID: "y2",
    },
  ],
};

exports.dataListrik = dataListrik;


// Data untuk Grafik Accu
const dataAccu = {
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
      label: "Tegangan Accu (V)",
      data: [
        12.5, 12.3, 12.1, 12.0, 11.8, 11.6, 11.4, 11.3, 11.5, 11.8, 12.0, 12.2,
        12.4, 12.6, 12.8, 12.7, 12.5, 12.3, 12.1, 11.9, 11.8, 11.6, 11.4, 11.2,
      ],
      borderColor: "darkblue",
      borderWidth: 2,
      yAxisID: "y1",
    },
    {
      label: "Arus Accu (A)",
      data: [
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
      ],
      borderColor: "orange",
      borderWidth: 2,
      yAxisID: "y2",
    },
  ],
};

// Data untuk Grafik UPS
const dataUPS = {
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
      label: "Tegangan UPS (V)",
      data: [
        12.0, 12.0, 12.0, 12.0, 12.0, 11.9, 11.7, 11.5, 11.4, 11.3, 11.2, 11.0,
        10.8, 10.7, 10.6, 10.5, 10.4, 10.3, 10.2, 10.1, 10.0, 9.9, 9.8, 9.7,
      ],
      borderColor: "red",
      borderWidth: 2,
      yAxisID: "y1",
    },
    {
      label: "Arus UPS (A)",
      data: [
        0.5, 0.5, 0.5, 0.5, 0.5, 0.4, 0.4, 0.4, 0.5, 0.5, 0.5, 0.5, 0.4, 0.4,
        0.4, 0.4, 0.4, 0.4, 0.4, 0.5, 0.5, 0.5, 0.5, 0.5,
      ],
      borderColor: "purple",
      borderWidth: 2,
      yAxisID: "y2",
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
function fetchData(id, date) {
  $.ajax({
    url: `http://localhost:8081/api/monitoring.php?device_id=${id}&date=${date}`,
    method: "GET",
    dataType: "json",
    success: function (result) {
      // Update the chart data and labels
      dataListrik.labels = result.labels;

      result.data.forEach((dataset) => {
        if (dataset.label_key === "pln_volt") {
          dataListrik.datasets[0].data = dataset.data;
        } else if (dataset.label_key === "pln_current") {
          dataListrik.datasets[1].data = dataset.data;
        }
      });

      // const plnCurrentChart = dataListrik.datasets.find(
      //   (dataset) => dataset.labelKey === "pln_current"
      // );
      // const plnVoltChart = dataListrik.datasets.find(
      //   (dataset) => dataset.labelKey === "pln_volt"
      // );

      // const plnCurrentDataset = result.data.find(
      //   (dataset) => dataset.label_key === "pln_current"
      // );
      // const plnVoltDataset = result.data.find(
      //   (dataset) => dataset.label_key === "pln_volt"
      // );

      // plnCurrentChart.data = plnCurrentDataset.data;
      // plnVoltChart.data = plnVoltDataset.data;

      // Refresh the chart to display the new data
      currentChart.update();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error fetching data:", textStatus, errorThrown);
    },
  });
}

// Update chart every 2 seconds with the new data
// setInterval(fetchData, 2000);
// fetchData(); // Initial call to populate data

// Fungsi untuk memperbarui grafik berdasarkan pilihan
document
  .getElementById("chartSelector")
  .addEventListener("change", function () {
    currentChart.destroy(); // Hancurkan grafik saat ini
    let selectedValue = this.value;
    let dataToUse;
    let maxY1; // Untuk skala maksimum sumbu y1
    let maxY2; // Untuk skala maksimum sumbu y2

    if (selectedValue === "listrik") {
      dataToUse = dataListrik;
      maxY1 = 230; // Skala maksimum untuk Grafik Listrik (tegangan)
      maxY2 = 30; // Skala maksimum untuk Grafik Listrik (arus)
    } else if (selectedValue === "accu") {
      dataToUse = dataAccu;
      maxY1 = 13; // Skala maksimum untuk Grafik Accu (tegangan)
      maxY2 = 20; // Skala maksimum untuk Grafik Accu (arus)
    } else if (selectedValue === "ups") {
      dataToUse = dataUPS;
      maxY1 = 13; // Skala maksimum untuk Grafik UPS (tegangan)
      maxY2 = 10; // Skala maksimum untuk Grafik UPS (arus)
    }

    currentChart = new Chart(ctx, {
      type: "line",
      data: dataToUse,
      options: {
        maintainAspectRatio: false,
        scales: {
          y1: {
            type: "linear",
            position: "left",
            beginAtZero: true,
            max: maxY1,
          },
          y2: {
            type: "linear",
            position: "right",
            beginAtZero: true,
            max: maxY2,
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
  });

// function webSocketConnection(id) {
//   // Connect to WebSocket server
//   const socket = new WebSocket(
//     `wss://s13783.blr1.piesocket.com/v3/${id}?api_key=7SEqHklfXLf4YSvF8OmgAd147ewDT0RT2tZrCE3f`
//   );

//   // Event: Connection opened
//   socket.onopen = function () {
//     console.log("WebSocket connection established.");
//   };

//   // Event: Message received
//   socket.onmessage = function (event) {
//     const data = JSON.parse(event.data);

//     // Update the temperature
//     if (data.temperature !== undefined) {
//       document.getElementById(
//         "temperature-value"
//       ).textContent = `${data.temperature}°C`;
//     }

//     // Update PLN data
//     if (data.pln) {
//       if (data.pln.voltage !== undefined) {
//         document.getElementById(
//           "pln-voltage"
//         ).textContent = `${data.pln.voltage} V`;
//       }
//       if (data.pln.current !== undefined) {
//         document.getElementById(
//           "pln-current"
//         ).textContent = `${data.pln.current} A`;
//       }
//       if (data.pln.activity !== undefined) {
//         document.getElementById("pln-activity").textContent = data.pln.activity;
//       }
//       if (data.pln.status !== undefined) {
//         const statusElement = document.getElementById("pln-status");
//         statusElement.textContent = data.pln.status;
//         statusElement.className = `status ${
//           data.pln.status === "Aktif" ? "aktif" : "tidak-aktif"
//         }`;
//       }
//     }

//     // Update Accu data
//     if (data.accu) {
//       if (data.accu.voltage !== undefined) {
//         document.getElementById(
//           "accu-voltage"
//         ).textContent = `${data.accu.voltage} V`;
//       }
//       if (data.accu.current !== undefined) {
//         document.getElementById(
//           "accu-current"
//         ).textContent = `${data.accu.current} A`;
//       }
//       if (data.accu.activity !== undefined) {
//         document.getElementById("accu-activity").textContent =
//           data.accu.activity;
//       }
//       if (data.accu.status !== undefined) {
//         const statusElement = document.getElementById("accu-status");
//         statusElement.textContent = data.accu.status;
//         statusElement.className = `status ${
//           data.accu.status === "Aktif" ? "aktif" : "tidak-aktif"
//         }`;
//       }
//     }

//     // Update UPS data
//     if (data.ups) {
//       if (data.ups.voltage !== undefined) {
//         document.getElementById(
//           "ups-voltage"
//         ).textContent = `${data.ups.voltage} V`;
//       }
//       if (data.ups.current !== undefined) {
//         document.getElementById(
//           "ups-current"
//         ).textContent = `${data.ups.current} A`;
//       }
//       if (data.ups.activity !== undefined) {
//         document.getElementById("ups-activity").textContent = data.ups.activity;
//       }
//       if (data.ups.status !== undefined) {
//         const statusElement = document.getElementById("ups-status");
//         statusElement.textContent = data.ups.status;
//         statusElement.className = `status ${
//           data.ups.status === "Aktif" ? "aktif" : "tidak-aktif"
//         }`;
//       }
//     }
//   };

//   // Event: Connection closed
//   socket.onclose = function () {
//     console.log("WebSocket connection closed.");
//   };

//   // Event: Error occurred
//   socket.onerror = function (error) {
//     console.error("WebSocket error:", error);
//   };
// }



