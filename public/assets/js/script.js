const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
    const sidebar = document.querySelector("#sidebar");
    sidebar.classList.toggle("expand");
});

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar.classList.contains('expand')) {
        sidebar.classList.add('expand');
    }
});
