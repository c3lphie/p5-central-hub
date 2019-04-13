// Refresh side
function refresh() {
  location.reload(true);
}

// Initialisering af ny event siden
function EventInit(){
  AddDeviceToSelect('http://127.0.0.1/api/getdevices.php','devicelist');
  AddDeviceToSelect('http://127.0.0.1/api/[HVAD END DEN HEDDER].php','udevicelist');
}

// Initialisering af index
function HubInit(){
  GetDevices('http://10.0.0.1/api/getdevices.php');
  GetEvents('http://127.0.0.1/api/getevents.php');
  GetUserdevice('http://127.0.0.1/api/getuserdevices.php');
}



// Send event to api
function NewEvent( eventName, eventDesc, deviceName, uDeviceName) {
  
  
  
  const Http = new XMLHttpRequest();
  const api = 'http://10.0.0.1/api/addevent.php?'+;
  Http.open("GET",api);
  Http.send();
}



// Tilføj til listbox
function AddDeviceToSelect(urlJson, selectId) {
  fetch(urlJson)
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    if (myJson.hasOwnProperty('error')) {
      alert("An error has occured, check console for more information!");
      console.log(myJson.error);
    } else {
      let select = document.getElementById(selectId);
      let option = document.createElement('option');
      for (var i in myJson) {
        option.text = myJson[i].name;        
        selectId.add(option);
      }
    }
  });
}



//--------------------
//    Tabel Id'er:
// - eventList
// - userDeviceList
// - deviceList
//--------------------

// Ny tabelrække
function newTableRow(tableId, cell1Text, cell2Text, cell3Text) {
  var table = document.getElementById(tableId).getElementsByTagName('tbody')[0];
  var row = table.insertRow(1);

  if (cell3Text == null) {
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    cell1.innerText = cell1Text;
    cell2.innerText = cell2Text;
  } else {
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    cell1.innerText = cell1Text;
    cell2.innerText = cell2Text;
    cell3.innerText = cell3Text;
  }
}


// Skaf devices
// "localhost:80/api/getdevices.php"
function GetDevices(urlJson) {
  fetch(urlJson)
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    if (myJson.hasOwnProperty('error')) {
      alert("An error has occured, check console for more information!");
      console.log(myJson.error);
    } else {
      for (var i in myJson) {
        newTableRow('deviceList',myJson[i].name, myJson[i].ip, myJson[i].type);
      }
    }
  });
}

// Skaf Events
function GetEvents(urlJson) {
  fetch(urlJson)
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    if (myJson.hasOwnProperty('error')) {
      alert("An error has occured, check console for more information!");
      console.log(myJson.error);
    } else {
      for (var i in myJson) {
        newTableRow('eventList',myJson[i].name, myJson[i].ip);
      }
    }
  });
}

// Skaf userdevices
function GetUserdevice(urlJson) {
  fetch(urlJson)
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    if (myJson.hasOwnProperty('error')) {
      alert("An error has occured, check console for more information!");
      console.log(myJson.error);
    } else {
      for (var i in myJson) {
        newTableRow('userDeviceList',myJson[i].name, myJson[i].ip, myJson[i].mac);
      }
    }
  });
}
