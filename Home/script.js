
document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Message sent successfully!');
});

document.addEventListener("DOMContentLoaded", () => {
    const aboutProject = document.getElementById('aboutproject');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                aboutProject.classList.add('animate');
            }
        });
    });

    observer.observe(aboutProject);
});



