let devicelist;
let eventname;
let eventdesc;
let udevicelist;
let addEvent;

document.addEventListener('DOMContentLoaded', EventInit, false);

// Initialisering af ny event siden
function EventInit(){
    AddDeviceToSelect('http://10.0.0.1/api/getdevices.php','devicelist');
    AddDeviceToSelect('http://10.0.0.1/api/gettargets.php','udevicelist');
    devicelist = document.getElementById("devicelist");
    eventname = document.getElementById("eventname");
    eventdesc = document.getElementById("eventdesc");
    udevicelist = document.getElementById("udevicelist");
    addEvent = document.getElementById("addevent"); 

    
    addEvent.addEventListener("click", function(){
        NewEvent(eventname.value, eventdesc.value, devicelist[devicelist.selectedIndex].value, udevicelist[udevicelist.selectedIndex].value);
        window.location.replace("http://10.0.0.1/")
    });
}