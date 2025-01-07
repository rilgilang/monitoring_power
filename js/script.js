   // Other existing JS functionality (e.g., menu and notifications)
   const menuIcon = document.getElementById("menu-icon");
   const sidebar = document.getElementById("sidebar");
   const content = document.getElementById("content");
   const notificationIcon = document.getElementById("notification-icon");
   const notificationMessage = document.getElementById("notification-message");
   const notificationBadge = document.getElementById("notification-badge");

   const notifications = [
   ];



   let displayedNotifications = [];

   function showNotification() {
       const randomIndex = Math.floor(Math.random() * notifications.length);
       const newNotification = notifications[randomIndex];

       if (!displayedNotifications.includes(newNotification)) {
           displayedNotifications.push(newNotification);

           const notificationDiv = document.createElement("div");
           notificationDiv.textContent = newNotification;
           notificationDiv.className = "notification-item";
           notificationMessage.appendChild(notificationDiv);

           notificationBadge.textContent = displayedNotifications.length;
       }
   }

   setInterval(showNotification, 5000);

   menuIcon.addEventListener("click", () => {
       sidebar.classList.toggle("active");
       sidebar.classList.toggle("small");
       content.classList.toggle("active");
   });

   notificationIcon.addEventListener("click", () => {
       notificationMessage.style.display = notificationMessage.style.display === "none" || notificationMessage.style.display === "" ? "block" : "none";
   });

   document.addEventListener("click", (event) => {
       if (!notificationIcon.contains(event.target) && !notificationMessage.contains(event.target)) {
           notificationMessage.style.display = "none";
       }
   });

   const ws = new WebSocket("wss://monitoring-power-web-socket-production.up.railway.app/");

   ws.onopen = () => {
       console.log("Connected to WebSocket server");
    //    ws.send("Hello Server!");
   };

   ws.onmessage = (event) => {
       const data = JSON.parse(event.data);
        notifications.unshift(data.message)
   };

   ws.onclose = () => {
       console.log("Disconnected from WebSocket server");
   };