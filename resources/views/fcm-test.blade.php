<!DOCTYPE html>
<html>
<head>
  <title>FCM Test</title>
</head>

<body>

<button id="btn">Get Token</button>
<pre id="output"></pre>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

const firebaseConfig = {
  apiKey: "AIzaSyACXfzTll68-rbX7Q0qd7UCf96RroDJqyU",
  authDomain: "fixup-c687c.firebaseapp.com",
  projectId: "fixup-c687c",
  storageBucket: "fixup-c687c.firebasestorage.app",
  messagingSenderId: "43823771068",
  appId: "1:43823771068:web:d9bc0f522b0501b23e4fb9"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

document.getElementById("btn").addEventListener("click", async () => {

    const permission = await Notification.requestPermission();
    console.log("permission:", permission);

    if (permission !== "granted") {
        alert("Permission denied");
        return;
    }

    const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
    await navigator.serviceWorker.ready;

    const token = await getToken(messaging, {
        vapidKey: "BMKK6CJLKM8Ww43BhpfOpKUpx5vNxHHAZhpBT9rq81kqfOaHii3SX3D-DS6dA8UcTmfIL5P_00bE9nph47K0chc",
        serviceWorkerRegistration: registration
    });

    console.log("TOKEN:", token);
    document.getElementById("output").innerText = token || "NO TOKEN";
});

</script>

</body>
</html>