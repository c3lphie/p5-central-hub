document.addEventListener('DOMContentLoaded', EventInit, false);

// Initialisering af ny event siden
function EventInit(){
    AddDeviceToSelect('http://10.0.0.1/api/getdevices.php','devicelist');
    AddDeviceToSelect('http://10.0.0.1/api/getuserdevices.php','udevicelist');

    console.log(devicelist[devicelist.selectedIndex].innerText);
    console.log("lol");
    console.log(devicelist[devicelist.selectedIndex].value);
    
  }



  let eventname = document.getElementById("eventname");
  let eventdesc = document.getElementById("eventdesc");
  let devicelist = document.getElementById("devicelist");
  let udevicelist = document.getElementById("udevicelist");
  let addEvent = document.getElementById("addevent");

  //addEvent.onclick = NewEvent(eventname.innerText, eventdesc.innerText, devicelist.val, udevicelist)