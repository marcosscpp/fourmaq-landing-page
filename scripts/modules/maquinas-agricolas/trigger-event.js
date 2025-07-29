export default function triggerEvent(eventName) {
  fetch("../php/maquinas-agricolas/fbevent.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ eventName }),
  });
}
