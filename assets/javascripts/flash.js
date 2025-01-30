document.addEventListener("DOMContentLoaded", () => {
    const flashMessage = document.getElementById('flash-message');

    if(flashMessage){
        setTimeout(function() {
            flashMessage.style.display = 'none';
        }, 5000);
    }
});