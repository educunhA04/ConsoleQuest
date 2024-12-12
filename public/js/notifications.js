document.addEventListener("DOMContentLoaded", () => {
    const notifications = document.querySelectorAll(".notification-item");

    notifications.forEach(notification => {
        notification.addEventListener("click", () => {
            const notificationId = notification.dataset.id; // Get the notification ID
            console.log(`Marking notification ${notificationId} as viewed.`);

            notification.classList.toggle("expanded");
            
            fetch(`/notifications/${notificationId}/view`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json",
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        
                        notification.classList.add("viewed"); // Visually mark it as viewed
                    } else {
                        console.error(`Failed: ${data.error}`);
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                });
        });
    });
});
