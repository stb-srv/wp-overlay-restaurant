const toggleDarkMode = () => {
    document.body.classList.toggle('darkmode');
    localStorage.setItem('darkmode', document.body.classList.contains('darkmode'));
};

document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('darkmode') === 'true') {
        document.body.classList.add('darkmode');
    }
    document.getElementById('darkmode-toggle')?.addEventListener('click', toggleDarkMode);
});
