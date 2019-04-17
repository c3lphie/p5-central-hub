document.addEventListener('DOMContentLoaded', HubInit, false);

// Initialisering af index
function HubInit(){
  // setInterval(() => {
  //   JSONToTable('http://10.0.0.1/api/getdevices.php','eventList');
  //   JSONToTable('http://10.0.0.1/api/getevents.php','deviceList');
  //   JSONToTable('http://10.0.0.1/api/gettargets.php','userDeviceList');
  // }, 5000); 
  JSONToTable('http://'+ip+'/api/getdevices.php','deviceList');
  JSONToTable('http://'+ip+'/api/gettargets.php','userDeviceList');

  let newudev = document.getElementById("newudev");

  newudev.addEventListener("click", function(){
    window.location.replace("http://"+ip+"/newphone.html");
  });

}