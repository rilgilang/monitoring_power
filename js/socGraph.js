const ctx = document.getElementById("socChart").getContext("2d");


// Data untuk grafik SoC
const dataSoC = {
    labels: [],
    datasets: [
      {
        label: "SoC Route Utama (%)",
        data:[],
        borderColor: "#27ae60",
        backgroundColor: "rgba(39, 174, 96, 0.2)",
        borderWidth: 2,
        fill: true
      },
      {
        label: "SoC BTS 1 (%)",
        data:[],
        borderColor: "#2980b9",
        backgroundColor: "rgba(41, 128, 185, 0.2)",
        borderWidth: 2,
        fill: true
      },
      {
        label: "SoC BTS 2 (%)",
        data:[],
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

// Create the chart
const socChart = new Chart(ctx, config);

// Fetch and update chart data
function updateSoCData(date) {
    fetch(`api/soc.php?date=${date}`)
        .then(response => response.json())
        .then(data => {
            updateTable(data.data);

            dataSoC.datasets[0].data = []
            dataSoC.datasets[1].data = []
            dataSoC.datasets[2].data = []
            dataSoC.labels = []

            data.labels.forEach(item => {
                dataSoC.labels.push(item.split(" ")[1])
            });

            graphData = [
                [],
                [],
                []
            ]

            // socChart.data.labels = data.labels;
            data.data.graph.forEach(item => {

                switch (parseInt(item.device_id)){
                    case 1:
                        graphData[0].push(parseInt(item.soc))                        
                    case 2:
                        graphData[1].push(parseInt(item.soc))
                    case 3:
                        graphData[2].push(parseInt(item.soc))
                    default:
                        break;
                }

                dataSoC.datasets[0].data = graphData[0];
                dataSoC.datasets[1].data = graphData[1];
                dataSoC.datasets[2].data = graphData[2];
            })    
            socChart.update('none');


        })
        .catch(error => console.error("Error updating chart data:", error));
}

// Fetch and update the table dynamically
function updateTable(data) {
    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = ""; // Clear existing rows

    data.table.forEach(row => {
        const tableRow = `
            <tr>
                <td><i class="fas fa-car-battery"></i> ${row.device_name}</td>
                <td>${row.accu_volt} V</td>
                <td>${row.accu_current} A</td>
                <td>${row.soc} %</td>
                <td>${convertMinutesToHours(row.accu_estimate_time) || 'N/A'}</td>
                <td>${row.accu_info}</td>
            </tr>`;
        tableBody.insertAdjacentHTML("beforeend", tableRow);
    });
}

function convertMinutesToHours(minutes) {
  // Calculate hours and remaining minutes
  const hours = Math.floor(minutes / 60);
  const remainingMinutes = minutes % 60;

  // Construct the output string
  const hoursPart = hours > 0 ? `${hours} jam` : "";
  const minutesPart = remainingMinutes > 0 ? `${remainingMinutes} menit` : "";

  // Combine hours and minutes with a space
  return [hoursPart, minutesPart].filter(Boolean).join(" ");
}
