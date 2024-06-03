let search = document.getElementById("search");

search.addEventListener("keydown", e => {
    if (e.key == "Enter") {
      searchData();
    }
});

function searchData() {
  window.location = "system.php?search=" + search.value;
}
