const ctx = document.getElementById("powerChart").getContext("2d");

// Data untuk Grafik Listrik
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
      data: [
        200, 190, 180, 175, 170, 165, 160, 155, 150, 155, 160, 165, 170, 175,
        180, 185, 190, 195, 200, 205, 210, 215, 220, 225,
      ],
      borderColor: "lime",
      borderWidth: 1,
      yAxisID: "y1",
    },
    {
      label: "Arus PLN (A)",
      data: [
        5, 4.5, 5, 5.5, 4.8, 5.2, 5, 4.7, 4.9, 5, 5.2, 4.8, 5.1, 5.3, 4.9, 5.2,
        5, 4.8, 5.1, 5.3, 5, 4.7, 4.5, 4.8,
      ],
      borderColor: "blue",
      borderWidth: 2,
      yAxisID: "y2",
    },
  ],
};

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

// Inisialisasi grafik dengan data listrik sebagai default
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
