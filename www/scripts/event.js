let devicelist;
let eventname;
let eventdesc;
let udevicelist;
let addEvent;

// Initialisering af ny event siden

document.onload = function(){
    function EventInit(){
        AddDeviceToSelect('http://10.0.0.1/api/getdevices.php','devicelist');
        AddDeviceToSelect('http://10.0.0.1/api/getuserdevices.php','udevicelist');
    }
    
    devicelist = document.getElementById("devicelist");
    eventname = document.getElementById("eventname");
    eventdesc = document.getElementById("eventdesc");
    udevicelist = document.getElementById("udevicelist");
    addEvent = document.getElementById("addevent"); 

    addEvent.onclick = function(){
        NewEvent(eventname.innerText, eventdesc.innerText, devicelist[devicelist.selectedIndex].value, udevicelist[udevicelist.selectedIndex].value)
    }

}
