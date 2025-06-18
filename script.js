document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search");
    const serviceCards = document.querySelectorAll(".service-card");

    searchInput.addEventListener("keyup", () => {
        let filter = searchInput.value.toLowerCase();

        serviceCards.forEach(card => {
            let serviceName = card.querySelector("h3").innerText.toLowerCase();
            if (serviceName.includes(filter)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
});
