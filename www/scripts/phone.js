document.addEventListener('DOMContentLoaded', phoneInit, false);

// Initialisering af ny event siden
function phoneInit(){
    let addPhone = document.getElementById('addphone');
    let phoneName = document.getElementById('phonename');
    let phoneMAC = document.getElementById('phonemac');

    addPhone.addEventListener("click", function(){
        NewDevice(phoneName.value, phoneMAC.value);
        // window.location.replace("http://10.0.0.1/");
    });
}