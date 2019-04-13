let devicelist = document.getElementById("devicelist");
let eventname = document.getElementById("eventname");
let eventdesc = document.getElementById("eventdesc");
let udevicelist = document.getElementById("udevicelist");
let addEvent = document.getElementById("addevent");

document.addEventListener('DOMContentLoaded', EventInit, false);

// Initialisering af ny event siden
function EventInit(){
    AddDeviceToSelect('http://10.0.0.1/api/getdevices.php','devicelist');
    AddDeviceToSelect('http://10.0.0.1/api/getuserdevices.php','udevicelist');    
  }



 

  addEvent.onclick = NewEvent(eventname.innerText, eventdesc.innerText, devicelist[devicelist.selectedIndex].value, udevicelist[udevicelist.selectedIndex].value)