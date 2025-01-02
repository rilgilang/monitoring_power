const ctx = document.getElementById("socChart").getContext("2d");

// Data untuk grafik SoC
const dataSoC = {
  labels: [],
  datasets: [
    {
      label: "SoC Route Utama (%)",
      data: [],
      borderColor: "#27ae60",
      backgroundColor: "rgba(39, 174, 96, 0.2)",
      borderWidth: 2,
      fill: true
    },
    {
      label: "SoC BTS 1 (%)",
      data: [],
      borderColor: "#2980b9",
      backgroundColor: "rgba(41, 128, 185, 0.2)",
      borderWidth: 2,
      fill: true
    },
    {
      label: "SoC BTS 2 (%)",
      data: [],
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
  fetch('api/soc.php')
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
setInterval(updateSoCData, 300000); // Update setiap 5 menit