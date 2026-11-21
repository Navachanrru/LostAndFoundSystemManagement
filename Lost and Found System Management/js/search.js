
function searchItems() {

    // Get search input and filter values
    let searchInput = document.getElementById("searchInput").value.toLowerCase();
    let categoryFilter = document.getElementById("categoryFilter").value;
    let statusFilter = document.getElementById("statusFilter").value;

    // Get all item cards
    let items = document.getElementsByClassName("browse-item-card");

    // Check each item
    for (let i = 0; i < items.length; i++) {

        let itemName = items[i].querySelector("h3").innerText.toLowerCase();
        let category = items[i].getAttribute("data-category");
        let status = items[i].querySelector(".status").innerText;

        // Check search, category and status
        let matchSearch = itemName.includes(searchInput);
        let matchCategory = categoryFilter === "" || category === categoryFilter;
        let matchStatus = statusFilter === "" || status === statusFilter;

        // Display matching items
        if (matchSearch && matchCategory && matchStatus) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}
