const ctx = document.getElementById("socChart").getContext("2d");

// Data untuk grafik SoC
const dataSoC = {
  labels: [
    "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", 
    "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", 
    "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", 
    "21:00", "22:00", "23:00"
  ],
  datasets: [
    {
      label: "SoC Route Utama (%)",
      data: [
        100, 95, 90, 88, 85, 82, 80, 78, 75, 73, 
        70, 68, 65, 63, 60, 58, 55, 53, 50, 48, 
        45, 43, 40, 38
      ],
      borderColor: "#27ae60",
      backgroundColor: "rgba(39, 174, 96, 0.2)",
      borderWidth: 2,
      fill: true
    },
    {
      label: "SoC BTS 1 (%)",
      data: [
        95, 92, 89, 86, 83, 80, 77, 74, 71, 68,
        65, 62, 59, 56, 53, 50, 47, 44, 41, 38,
        35, 32, 29, 26
      ],
      borderColor: "#2980b9",
      backgroundColor: "rgba(41, 128, 185, 0.2)",
      borderWidth: 2,
      fill: true
    },
    {
      label: "SoC BTS 2 (%)",
      data: [
        90, 88, 86, 84, 82, 80, 78, 76, 74, 72,
        70, 68, 66, 64, 62, 60, 58, 56, 54, 52,
        50, 48, 46, 44
      ],
      borderColor: "#8e44ad",
      backgroundColor: "rgba(142, 68, 173, 0.2)",
      borderWidth: 2,
      fill: true
    }
  ]
};

// Konfigurasi grafik
const config = {
  type: "line",
  data: dataSoC,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true,
        max: 100,
        min: 0,
        ticks: {
          stepSize: 10, // Menampilkan tick setiap 10%
          callback: function(value) {
            return value + '%';
          }
        },
        grid: {
          color: function(context) {
            if (context.tick.value === 20) { // Garis merah untuk level kritis
              return 'rgba(255, 0, 0, 0.5)';
            }
            return 'rgba(0, 0, 0, 0.1)';
          },
          lineWidth: function(context) {
            if (context.tick.value === 20) {
              return 2;
            }
            return 1;
          }
        },
        title: {
          display: true,
          text: "State of Charge (%)",
          font: {
            size: 14,
            weight: 'bold'
          }
        }
      },
      x: {
        grid: {
          display: true,
          drawBorder: true,
        },
        title: {
          display: true,
          text: "Waktu",
          font: {
            size: 14,
            weight: 'bold'
          }
        }
      }
    },
    plugins: {
      title: {
        display: true,
        text: "Grafik State of Charge (SoC) Accu",
        font: {
          size: 16,
          weight: 'bold'
        },
        padding: {
          top: 10,
          bottom: 30
        }
      },
      legend: {
        position: "bottom",
        labels: {
          padding: 20,
          boxWidth: 40,
          usePointStyle: true,
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        titleFont: {
          size: 14
        },
        bodyFont: {
          size: 13
        },
        padding: 15,
        callbacks: {
          label: function(context) {
            let label = context.dataset.label || '';
            if (context.parsed.y !== null) {
              label += ': ' + context.parsed.y + '%';
            }
            // Tambahkan peringatan jika di bawah 20%
            if (context.parsed.y < 20) {
              label += ' (Level Kritis!)';
            }
            return label;
          }
        }
      }
    },
    interaction: {
      intersect: false,
      mode: 'index'
    },
    elements: {
      point: {
        radius: 3,
        hoverRadius: 6
      },
      line: {
        tension: 0.3 // Membuat garis lebih smooth
      }
    },
    animation: {
      duration: 2000,
      easing: 'easeInOutQuart'
    }
  }
};

// Buat grafik
const socChart = new Chart(ctx, config);

// Fungsi untuk update data realtime (opsional)
function updateSoCData() {
  fetch('api/getSoCData.php')
    .then(response => response.json())
    .then(data => {
      socChart.data.datasets[0].data = data.routeUtama;
      socChart.data.datasets[1].data = data.bts1;
      socChart.data.datasets[2].data = data.bts2;
      socChart.update('none'); // Update tanpa animasi
    })
    .catch(error => console.error('Error:', error));
}

// Uncomment baris berikut untuk mengaktifkan update otomatis
// setInterval(updateSoCData, 300000); // Update setiap 5 menit