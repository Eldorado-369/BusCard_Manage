// toggle sidebar
document.addEventListener('DOMContentLoaded', () => {
    //toggling
    const toggleButton = document.getElementById('toggleButton');
    const sideNavbar = document.getElementById('sideNavigationBar');
    const rightPane = document.querySelector('.rightPane');
    const dashboardTextAndIcons = document.querySelector('.dashboardTextAndIcons');
    const topIcons = document.querySelector('.topIcons');
    const applyButton = document.querySelector('.item2');
    const busCardLoader = document.querySelector('.loader');
    const busBodyContainer = document.querySelector('.busBodyContainer');

    toggleButton.addEventListener('click', () => {
        sideNavbar.classList.toggle('minimised');

        // Toggle icon based on state
        if (sideNavbar.classList.contains('minimised')) {
            toggleButton.classList.remove('fa-minimize');
            toggleButton.classList.add('fa-maximize');
            rightPane.style.width = '83em';
            rightPane.style.marginLeft = '10em';
            dashboardTextAndIcons.style.width = '80em';
            topIcons.style.marginLeft = '15.5em';
        } else {
            toggleButton.classList.remove('fa-maximize');
            toggleButton.classList.add('fa-minimize');
            rightPane.style.width = '69.5em';
            rightPane.style.marginLeft = '23.5em';
            dashboardTextAndIcons.style.width = '66em';
            topIcons.style.marginLeft = '1.5em';
        }
    });
    //toggling (end)

    applyButton.addEventListener('click', () => {
        busCardLoader.style.display = 'none';
        busBodyContainer.classList.add('no-busBodyContainer');
    });
});