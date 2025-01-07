// Fetch and update chart data
function updateMonitoringTable() {
    fetch(`api/monitoring.php`)
        .then(response => response.json())
        .then(data => {
            updateTable(data);
        })
        .catch(error => console.error("Error updating chart data:", error));
}

// Fetch and update the table dynamically
function updateTable(data) {
    const tableBody1 = document.getElementById("tableBody1");
    tableBody1.innerHTML = ""; // Clear existing rows
    const tableBody2 = document.getElementById("tableBody2");
    tableBody2.innerHTML = ""; // Clear existing rows
    const tableBody3 = document.getElementById("tableBody3");
    tableBody3.innerHTML = ""; // Clear existing rows


    const tableRow1 = `
                    <tr>
                                <td><i class="fas fa-bolt"></i> Listrik PLN</td>
                                    <td id="device-1-pln-voltage">${data[0].pln_volt}</td>
                                    <td id="device-1-pln-current">${data[0].pln_current}</td>
                                    <td id="device-1-pln-activity">${data[0].pln_activity}</td>
                                    <td><span id="device-1-pln-status" class="status tidak-aktif">${data[0].pln_status}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-car-battery"></i> Accu</td>
                                    <td id="device-1-accu-voltage">${data[0].accu_volt}</td>
                                    <td id="device-1-accu-current">${data[0].accu_volt}</td>
                                    <td id="device-1-accu-activity">${data[0].accu_activity}</td>
                                    <td><span id="device-1-accu-status" class="status tidak-aktif">${data[0].accu_status}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-battery-full"></i> Batterai UPS</td>
                                    <td id="device-1-ups-voltage">${data[0].ups_volt}</td>
                                    <td id="device-1-ups-current">${data[0].ups_current}</td>
                                    <td id="device-1-ups-activity">${data[0].ups_activity}</td>
                                    <td><span id="device-1-ups-status" class="status tidak-aktif">${data[0].ups_status}</span></td>
                        </tr>
                    `;

    const tableRow2 = `  
        <tr>
            <td><i class="fas fa-bolt"></i> Listrik PLN</td>
            <td id="device-2-pln-voltage">${data[1].pln_volt}</td>
            <td id="device-2-pln-current">${data[1].pln_current}</td>
            <td id="device-2-pln-activity">${data[1].pln_activity}</td>
            <td><span id="device-2-pln-status" class="status tidak-aktif">${data[1].pln_status}</span></td>
        </tr>
        <tr>
            <td><i class="fas fa-car-battery"></i> Accu</td>
            <td id="device-2-accu-voltage">${data[1].accu_volt}</td>
            <td id="device-2-accu-current">${data[1].accu_volt}</td>
            <td id="device-2-accu-activity">${data[1].accu_activity}</td>
            <td><span id="device-2-accu-status" class="status tidak-aktif">${data[1].accu_status}</span></td>
        </tr>
        <tr>
            <td><i class="fas fa-battery-full"></i> Batterai UPS</td>
            <td id="device-2-ups-voltage">${data[1].ups_volt}</td>
            <td id="device-2-ups-current">${data[1].ups_current}</td>
            <td id="device-2-ups-activity">${data[1].ups_activity}</td>
            <td><span id="device-2-ups-status" class="status tidak-aktif">${data[1].ups_status}</span></td>
        </tr>`

    const tableRow3 = `
        <tr>
            <td><i class="fas fa-bolt"></i> Listrik PLN</td>
            <td id="device-2-pln-voltage">${data[2].pln_volt}</td>
            <td id="device-2-pln-current">${data[2].pln_current}</td>
            <td id="device-2-pln-activity">${data[2].pln_activity}</td>
            <td><span id="device-2-pln-status" class="status tidak-aktif">${data[2].pln_status}</span></td>
        </tr>
        <tr>
            <td><i class="fas fa-car-battery"></i> Accu</td>
            <td id="device-2-accu-voltage">${data[2].accu_volt}</td>
            <td id="device-2-accu-current">${data[2].accu_volt}</td>
            <td id="device-2-accu-activity">${data[2].accu_activity}</td>
            <td><span id="device-2-accu-status" class="status tidak-aktif">${data[2].accu_status}</span></td>
        </tr>
        <tr>
            <td><i class="fas fa-battery-full"></i> Batterai UPS</td>
            <td id="device-2-ups-voltage">${data[2].ups_volt}</td>
            <td id="device-2-ups-current">${data[2].ups_current}</td>
            <td id="device-2-ups-activity">${data[2].ups_activity}</td>
            <td><span id="device-2-ups-status" class="status tidak-aktif">${data[2].ups_status}</span></td>
        </tr>`;

    tableBody1.insertAdjacentHTML("beforeend", tableRow1);
    tableBody2.insertAdjacentHTML("beforeend", tableRow2);
    tableBody3.insertAdjacentHTML("beforeend", tableRow3);

    const temperature1 = document.getElementById("device-1-temperature");
    const temperature2 = document.getElementById("device-2-temperature");
    const temperature3 = document.getElementById("device-3-temperature");

    temperature1.textContent= `${data[0].temperature} °C`;
    temperature2.textContent= `${data[1].temperature} °C`;
    temperature3.textContent= `${data[2].temperature} °C`;
}

updateMonitoringTable();