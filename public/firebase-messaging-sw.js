importScripts("https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js");

firebase.initializeApp({
  apiKey: "AIzaSyACXfzTll68-rbX7Q0qd7UCf96RroDJqyU",
  authDomain: "fixup-c687c.firebaseapp.com",
  projectId: "fixup-c687c",
  messagingSenderId: "43823771068",
  appId: "1:43823771068:web:d9bc0f522b0501b23e4fb9"
});

const messaging = firebase.messaging();