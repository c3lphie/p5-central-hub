let devicelist;
let eventname;
let eventdesc;
let udevicelist;
let addEvent = document.getElementById("addevent"); 

document.addEventListener('DOMContentLoaded', EventInit, false);

// Initialisering af ny event siden
function EventInit(){
    AddDeviceToSelect('http://10.0.0.1/api/getdevices.php','devicelist');
    AddDeviceToSelect('http://10.0.0.1/api/getuserdevices.php','udevicelist');
    devicelist = document.getElementById("devicelist");
eventname = document.getElementById("eventname");
eventdesc = document.getElementById("eventdesc");
udevicelist = document.getElementById("udevicelist");
   
  }



 

addEvent.onclick = function(){
    NewEvent(eventname.innerText, eventdesc.innerText, devicelist[devicelist.selectedIndex].value, udevicelist[udevicelist.selectedIndex].value)
}