document.addEventListener('DOMContentLoaded', HubInit, false);

// Initialisering af index
function HubInit(){
  GetDevices('http://10.0.0.1/api/getdevices.php');
  GetEvents('http://127.0.0.1/api/getevents.php');
  GetUserdevice('http://127.0.0.1/api/getuserdevices.php');

}