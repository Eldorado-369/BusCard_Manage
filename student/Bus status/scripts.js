document.addEventListener('DOMContentLoaded', () => {
    const stops = document.querySelectorAll('.stop');
    const progress = document.querySelector('.progress');
    let currentStopIndex = 0;

    function updateProgress() {
        const progressWidth = ((currentStopIndex + 1) / stops.length) * 100 + '%';
        progress.style.width = progressWidth;

        stops.forEach((stop, index) => {
            if (index < currentStopIndex) {
                stop.classList.add('active');
            } else {
                stop.classList.remove('active');
            }
        });
    }

    // Simulate bus progress
    setInterval(() => {
        currentStopIndex = (currentStopIndex + 1) % stops.length;
        updateProgress();
    }, 2000); // Change bus stop every 2 seconds

    updateProgress();
});
