const ctx = document.getElementById("powerChart").getContext("2d");

let selectedGraph = ""

// export const dataListrik = {
const dataListrik = {
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

// Data untuk Grafik Accu
const dataAccu = {
  labels: [],
  datasets: [
    {
      label: "Tegangan Accu (V)",
      labelKey: "accu_volt",
      data: [],
      borderColor: "darkblue",
      borderWidth: 2,
      yAxisID: "y1",
    },
    {
      label: "Arus Accu (A)",
      labelKey: "accu_current",
      data: [],
      borderColor: "orange",
      borderWidth: 2,
      yAxisID: "y2",
    },
  ],
};

// Data untuk Grafik UPS
const dataUPS = {
  labels: [
  ],
  datasets: [
    {
      label: "Tegangan UPS (V)",
      labelKey: "ups_volt",
      data: [],
      borderColor: "red",
      borderWidth: 2,
      yAxisID: "y1",
    },
    {
      label: "Arus UPS (A)",
      labelKey: "ups_current",
      data: [],
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
    url: `api/monitoring.php?id=${id}&date=${date}`,
    method: "GET",
    dataType: "json",
    success: function (result) {
      // Update the chart data and labels
      dataListrik.labels = result.labels;
      dataAccu.labels = result.labels;
      dataUPS.labels = result.labels;

      result.data.graph.forEach((dataset) => {
        switch (dataset.label_key) {
          case "pln_volt":
            dataListrik.datasets[0].data = dataset.data;
            break;
          case "pln_current":
            dataListrik.datasets[1].data = dataset.data;
            break;
          case "accu_volt":
            dataAccu.datasets[0].data = dataset.data;
            break;
          case "accu_current":
            dataAccu.datasets[1].data = dataset.data;
            break;
          case "ups_volt":
            dataUPS.datasets[0].data = dataset.data;
            break;
          case "ups_current":
            dataUPS.datasets[1].data = dataset.data;
            break;
          default:
            // Handle cases where label_key doesn't match any of the above
            console.warn(`Unknown label_key: ${dataset.label_key}`);
        }
      });

      currentChart.update();
      fillTable(result.data, id);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error fetching data:", textStatus, errorThrown);
    },
  });
}

// Update chart every 2 seconds with the new data
// setInterval(fetchData, 2000);
// fetchData(); // Initial call to populate data

function fillTable(data, deviceId){
   // Update the temperature
   if (data.temperature !== undefined) {
    document.getElementById(
      `device-${deviceId}-temperature`
    ).textContent = `${data.temperature}°C`;
    // lastData[0].temperature = `${data.temperature}°C`;
  }

  // Update PLN data
  if (data.pln) {
    if (data.pln.voltage !== undefined) {
      document.getElementById(
        `device-${deviceId}-pln-voltage`
      ).textContent = `${data.pln.voltage} V`;
      // lastData[0].pln.voltage = `${data.pln.voltage} V`;
    }
    if (data.pln.current !== undefined) {
      document.getElementById(
        `device-${deviceId}-pln-current`
      ).textContent = `${data.pln.current} A`;
      // lastData[0].pln.current = `${data.pln.current} A`;
    }
    if (data.pln.activity !== undefined) {
      document.getElementById(`device-${deviceId}-pln-activity`).textContent = data.pln.activity;
      // lastData[0].pln.activity = data.pln.activity;
    }
    if (data.pln.status !== undefined) {
      const statusElement = document.getElementById(`device-${deviceId}-pln-status`);
      statusElement.textContent = data.pln.status;
      statusElement.className = `status ${
        data.pln.status === "Aktif" ? "aktif" : "tidak-aktif"
      }`;
      // lastData[0].pln.status = `status ${
      //   data.pln.status === "Aktif" ? "aktif" : "tidak-aktif"
      // }`
    }
  }

  // Update Accu data
  if (data.accu) {
    if (data.accu.voltage !== undefined) {
      document.getElementById(
        `device-${deviceId}-accu-voltage`
      ).textContent = `${data.accu.voltage} V`;
      // lastData[0].accu.voltage = `${data.accu.voltage} V`;
    }
    if (data.accu.current !== undefined) {
      document.getElementById(
        `device-${deviceId}-accu-current`
      ).textContent = `${data.accu.current} A`;
      // lastData[0].accu.current = `${data.accu.current} A`;
    }
    if (data.accu.activity !== undefined) {
      document.getElementById(`device-${deviceId}-accu-activity`).textContent =
        data.accu.activity;
        // lastData[0].accu.activity = data.accu.activity;
    }
    if (data.accu.status !== undefined) {
      const statusElement = document.getElementById(`device-${deviceId}-accu-status`);
      statusElement.textContent = data.accu.status;
      statusElement.className = `status ${
        data.accu.status === "Aktif" ? "aktif" : "tidak-aktif"
      }`;
      // lastData[0].accu.status = `status ${
      //   data.accu.status === "Aktif" ? "aktif" : "tidak-aktif"
      // }`
    }
  }

  // Update UPS data
  if (data.ups) {
    if (data.ups.voltage !== undefined) {
      document.getElementById(
        `device-${deviceId}-ups-voltage`
      ).textContent = `${data.ups.voltage} V`;
      // lastData[0].ups.voltage = `${data.ups.voltage} V`;
    }
    if (data.ups.current !== undefined) {
      document.getElementById(
        `device-${deviceId}-ups-current`
      ).textContent = `${data.ups.current} A`;
      // lastData[0].ups.current = `${data.ups.current} A`;
    }
    if (data.ups.activity !== undefined) {
      document.getElementById(`device-${deviceId}-ups-activity`).textContent = data.ups.activity;
      // lastData[0].ups.activity = data.ups.activity;
    }
    if (data.ups.status !== undefined) {
      const statusElement = document.getElementById(`device-${deviceId}-ups-status`);
      statusElement.textContent = data.ups.status;
      statusElement.className = `status ${
        data.ups.status === "Aktif" ? "aktif" : "tidak-aktif"
      }`;
      // lastData[0].ups.status = `status ${
      //   data.ups.status === "Aktif" ? "aktif" : "tidak-aktif"
      // }`;
    }
  }
}

// Fungsi untuk memperbarui grafik berdasarkan pilihan
document
  .getElementById("chartSelector")
  .addEventListener("change", function () {
    currentChart.destroy(); // Hancurkan grafik saat ini
    let selectedValue = this.value;
    let dataToUse;
    let maxY1; // Untuk skala maksimum sumbu y1
    let maxY2; // Untuk skala maksimum sumbu y2
    selectedGraph = this.value;

    if (selectedValue === "pln") {
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


