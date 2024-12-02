import { dataListrik } from "./deviceData";

const lastData = [
    {
        "status_changed": true,
        "temperature": 0,
        "pln": {
            "voltage": 0,
            "current": 0,
            "activity": "",
            "status": ""
        },
        "accu": {
            "voltage": 0,
            "current": 0,
            "activity": "",
            "status": ""
        },
        "ups": {
            "voltage": 0,
            "current": 0,
            "activity": "",
            "status": ""
        }
    }
]

function device1Socket() {
    // Connect to WebSocket server
    const socket = new WebSocket(
      `wss://s13783.blr1.piesocket.com/v3/1?api_key=7SEqHklfXLf4YSvF8OmgAd147ewDT0RT2tZrCE3f`
    );
  
    // Event: Connection opened
    socket.onopen = function () {
      console.log("WebSocket 1 connection established.");
    };
  
    // Event: Message received
    socket.onmessage = function (event) {
        
      const data = JSON.parse(event.data);
  
      // Update the temperature
      if (data.temperature !== undefined) {
        document.getElementById(
          "device-1-temperature"
        ).textContent = `${data.temperature}°C`;
        lastData[0].temperature = `${data.temperature}°C`;
      }
  
      // Update PLN data
      if (data.pln) {
        if (data.pln.voltage !== undefined) {
          document.getElementById(
            "device-1-pln-voltage"
          ).textContent = `${data.pln.voltage} V`;
          lastData[0].pln.voltage = `${data.pln.voltage} V`;
        }
        if (data.pln.current !== undefined) {
          document.getElementById(
            "device-1-pln-current"
          ).textContent = `${data.pln.current} A`;
          lastData[0].pln.current = `${data.pln.current} A`;
        }
        if (data.pln.activity !== undefined) {
          document.getElementById("device-1-pln-activity").textContent = data.pln.activity;
          lastData[0].pln.activity = data.pln.activity;
        }
        if (data.pln.status !== undefined) {
          const statusElement = document.getElementById("device-1-pln-status");
          statusElement.textContent = data.pln.status;
          statusElement.className = `status ${
            data.pln.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
          lastData[0].pln.status = `status ${
            data.pln.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`
        }
      }
  
      // Update Accu data
      if (data.accu) {
        if (data.accu.voltage !== undefined) {
          document.getElementById(
            "device-1-accu-voltage"
          ).textContent = `${data.accu.voltage} V`;
          lastData[0].accu.voltage = `${data.accu.voltage} V`;
        }
        if (data.accu.current !== undefined) {
          document.getElementById(
            "device-1-accu-current"
          ).textContent = `${data.accu.current} A`;
          lastData[0].accu.current = `${data.accu.current} A`;
        }
        if (data.accu.activity !== undefined) {
          document.getElementById("device-1-accu-activity").textContent =
            data.accu.activity;
            lastData[0].accu.activity = data.accu.activity;
        }
        if (data.accu.status !== undefined) {
          const statusElement = document.getElementById("device-1-accu-status");
          statusElement.textContent = data.accu.status;
          statusElement.className = `status ${
            data.accu.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
          lastData[0].accu.status = `status ${
            data.accu.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`
        }
      }
  
      // Update UPS data
      if (data.ups) {
        if (data.ups.voltage !== undefined) {
          document.getElementById(
            "device-1-ups-voltage"
          ).textContent = `${data.ups.voltage} V`;
          lastData[0].ups.voltage = `${data.ups.voltage} V`;
        }
        if (data.ups.current !== undefined) {
          document.getElementById(
            "device-1-ups-current"
          ).textContent = `${data.ups.current} A`;
          lastData[0].ups.current = `${data.ups.current} A`;
        }
        if (data.ups.activity !== undefined) {
          document.getElementById("device-1-ups-activity").textContent = data.ups.activity;
          lastData[0].ups.activity = data.ups.activity;
        }
        if (data.ups.status !== undefined) {
          const statusElement = document.getElementById("device-1-ups-status");
          statusElement.textContent = data.ups.status;
          statusElement.className = `status ${
            data.ups.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
          lastData[0].ups.status = `status ${
            data.ups.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
        }
      }
    };
  
    // Event: Connection closed
    socket.onclose = function () {
      console.log("WebSocket connection closed.");
    };
  
    // Event: Error occurred
    socket.onerror = function (error) {
      console.error("WebSocket error:", error);
    };
}

function device2Socket() {
    // Connect to WebSocket server
    const socket = new WebSocket(
      `wss://s13783.blr1.piesocket.com/v3/2?api_key=7SEqHklfXLf4YSvF8OmgAd147ewDT0RT2tZrCE3f`
    );
  
    // Event: Connection opened
    socket.onopen = function () {
      console.log("WebSocket 2 connection established.");
    };
  
    // Event: Message received
    socket.onmessage = function (event) {
      const data = JSON.parse(event.data);
  
      // Update the temperature
      if (data.temperature !== undefined) {
        document.getElementById(
          "device-2-temperature"
        ).textContent = `${data.temperature}°C`;
        lastData[1].temperature = `${data.temperature}°C`;
      }
  
      // Update PLN data
      if (data.pln) {
        if (data.pln.voltage !== undefined) {
          document.getElementById(
            "device-2-pln-voltage"
          ).textContent = `${data.pln.voltage} V`;
          lastData[1].pln = `${data.pln.voltage} V`;
        }
        if (data.pln.current !== undefined) {
          document.getElementById(
            "device-2-pln-current"
          ).textContent = `${data.pln.current} A`;
          lastData[1].pln.current = `${data.pln.current} A`;
        }
        if (data.pln.activity !== undefined) {
          document.getElementById("device-2-pln-activity").textContent = data.pln.activity;
          lastData[1].pln.activity = data.pln.activity;
        }
        if (data.pln.status !== undefined) {
          const statusElement = document.getElementById("device-2-pln-status");
          statusElement.textContent = data.pln.status;
          statusElement.className = `status ${
            data.pln.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
          lastData[1].pln.status = `status ${
            data.pln.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`
        }
      }
  
      // Update Accu data
      if (data.accu) {
        if (data.accu.voltage !== undefined) {
          document.getElementById(
            "device-2-accu-voltage"
          ).textContent = `${data.accu.voltage} V`;
          lastData[1].accu.voltage = `${data.accu.voltage} V`;
        }
        if (data.accu.current !== undefined) {
          document.getElementById(
            "device-2-accu-current"
          ).textContent = `${data.accu.current} A`;
          lastData[1].accu.current = `${data.accu.current} A`;
        }
        if (data.accu.activity !== undefined) {
          document.getElementById("device-2-accu-activity").textContent =
            data.accu.activity;
            lastData[1].accu.activity = data.accu.activity;
        }
        if (data.accu.status !== undefined) {
          const statusElement = document.getElementById("device-2-accu-status");
          statusElement.textContent = data.accu.status;
          statusElement.className = `status ${
            data.accu.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;

          lastData[1].accu.status = `status ${
            data.accu.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
        }
      }
  
      // Update UPS data
      if (data.ups) {
        if (data.ups.voltage !== undefined) {
          document.getElementById(
            "device-2-ups-voltage"
          ).textContent = `${data.ups.voltage} V`;

          lastData[1].ups.voltage = `${data.ups.voltage} V`;
        }
        if (data.ups.current !== undefined) {
          document.getElementById(
            "device-2-ups-current"
          ).textContent = `${data.ups.current} A`;

          lastData[1].ups.voltage = `${data.ups.current} A`;
        }
        if (data.ups.activity !== undefined) {
          document.getElementById("device-2-ups-activity").textContent = data.ups.activity;
          lastData[1].ups.activity = data.ups.activity;
        }

        if (data.ups.status !== undefined) {
          const statusElement = document.getElementById("device-2-ups-status");
          statusElement.textContent = data.ups.status;
          statusElement.className = `status ${
            data.ups.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;

          lastData[1].ups.status = `status ${
            data.ups.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
        }
      }
    };
  
    // Event: Connection closed
    socket.onclose = function () {
      console.log("WebSocket connection closed.");
    };
  
    // Event: Error occurred
    socket.onerror = function (error) {
      console.error("WebSocket error:", error);
    };
}

function device3Socket() {
    // Connect to WebSocket server
    const socket = new WebSocket(
      `wss://s13783.blr1.piesocket.com/v3/3?api_key=7SEqHklfXLf4YSvF8OmgAd147ewDT0RT2tZrCE3f`
    );
  
    // Event: Connection opened
    socket.onopen = function () {
      console.log("WebSocket 3 connection established.");
    };
  
    // Event: Message received
    socket.onmessage = function (event) {
      const data = JSON.parse(event.data);
  
      // Update the temperature
      if (data.temperature !== undefined) {
        document.getElementById(
          "device-3-temperature"
        ).textContent = `${data.temperature}°C`;

        lastData[2].temperature = `${data.temperature}°C`;
      }
  
      // Update PLN data
      if (data.pln) {
        if (data.pln.voltage !== undefined) {
          document.getElementById(
            "device-3-pln-voltage"
          ).textContent = `${data.pln.voltage} V`;

          lastData[2].pln.voltage = `${data.pln.voltage} V`;
        }
        if (data.pln.current !== undefined) {
          document.getElementById(
            "device-3-pln-current"
          ).textContent = `${data.pln.current} A`;
          lastData[2].pln.current = `${data.pln.current} A`;
        }
        if (data.pln.activity !== undefined) {
          document.getElementById("device-3-pln-activity").textContent = data.pln.activity;
          lastData[2].pln.activity = data.pln.activity;
        }
        if (data.pln.status !== undefined) {
          const statusElement = document.getElementById("device-3-pln-status");
          statusElement.textContent = data.pln.status;
          statusElement.className = `status ${
            data.pln.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
          
          lastData[2].pln.status = `status ${
            data.pln.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
        }
      }
  
      // Update Accu data
      if (data.accu) {
        if (data.accu.voltage !== undefined) {
          document.getElementById(
            "device-3-accu-voltage"
          ).textContent = `${data.accu.voltage} V`;
          lastData[2].accu.voltage = `${data.accu.voltage} V`;
        }
        if (data.accu.current !== undefined) {
          document.getElementById(
            "device-3-accu-current"
          ).textContent = `${data.accu.current} A`;
          lastData[2].accu.current = `${data.accu.current} A`;
        }
        if (data.accu.activity !== undefined) {
          document.getElementById("device-3-accu-activity").textContent =
            data.accu.activity;
            lastData[2].accu.activity = data.accu.activity;
        }
        if (data.accu.status !== undefined) {
          const statusElement = document.getElementById("device-3-accu-status");
          statusElement.textContent = data.accu.status;
          statusElement.className = `status ${
            data.accu.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
          lastData[2].status.current = `status ${
            data.accu.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
        }
      }
  
      // Update UPS data
      if (data.ups) {
        if (data.ups.voltage !== undefined) {
          document.getElementById(
            "device-3-ups-voltage"
          ).textContent = `${data.ups.voltage} V`;
          lastData[2].ups.voltage = `${data.ups.voltage} V`;
        }
        if (data.ups.current !== undefined) {
          document.getElementById(
            "device-3-ups-current"
          ).textContent = `${data.ups.current} A`;
          lastData[2].ups.current = `${data.ups.current} A`;
        }
        if (data.ups.activity !== undefined) {
          document.getElementById("device-3-ups-activity").textContent = data.ups.activity;
          lastData[2].ups.activity = data.ups.activity;
        }
        if (data.ups.status !== undefined) {
          const statusElement = document.getElementById("device-3-ups-status");
          statusElement.textContent = data.ups.status;
          statusElement.className = `status ${
            data.ups.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
          lastData[2].ups.status = `status ${
            data.ups.status === "Aktif" ? "aktif" : "tidak-aktif"
          }`;
        }
      }
    };
  
    // Event: Connection closed
    socket.onclose = function () {
      console.log("WebSocket connection closed.");
    };
  
    // Event: Error occurred
    socket.onerror = function (error) {
      console.error("WebSocket error:", error);
    };
}

device1Socket();
device2Socket();
device3Socket();