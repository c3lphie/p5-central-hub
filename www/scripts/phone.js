document.addEventListener('DOMContentLoaded', phoneInit, false);

// Initialisering af ny event siden
function phoneInit(){
    let addPhone = document.getElementById('addphone');

    addPhone.addEventListener("click", function(){
        window.location.replace("http://10.0.0.1/")
    });
}