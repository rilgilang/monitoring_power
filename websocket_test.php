<!DOCTYPE html>
<html>

<head>
    <title>WebSocket Test</title>
</head>

<body>
    <h1>WebSocket Test</h1>
    <div id="messages"></div>
    <script>
        const ws = new WebSocket("wss://monitoringpowerwebsocket.vercel.app");

        ws.onopen = () => {
            console.log("Connected to WebSocket server");
            ws.send("Hello Server!");
        };

        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            console.log(data);
            const messages = document.getElementById("messages");
            messages.innerHTML += `<p>${data.type}: ${data.message}</p>`;
        };

        ws.onclose = () => {
            console.log("Disconnected from WebSocket server");
        };
    </script>
</body>

</html>