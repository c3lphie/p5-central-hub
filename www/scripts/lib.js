// Refresh side
function refresh() {
  location.reload(true);
}


// Send event to api
function NewEvent( eventName, eventDesc, deviceName, uDeviceName) {
 
  // const Http = new XMLHttpRequest();
  const api = 'http://10.0.0.1/api/addevent.php?eventname=' + eventName + "&description=" + eventDesc + "&devicename=" + deviceName + "&userdevicename="+ uDeviceName;
 
  console.log(api);
  
  // Http.open("GET",api);
  // Http.send();
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
        select.add(option);
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
function JSONToTable(urlJson, tableId) {
  let arr = [];

  fetch(urlJson)
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    if (myJson.hasOwnProperty('error')) {
      alert("An error has occured, check console for more information!");
      console.log(myJson.error);
    } else {
      for (const i in myJson) {
        arr.push(myJson[i]);
      }
    }
  });
    
  if(tableId === 'deviceList'){
    for (var i in arr) {
      if(arr[i].type === 0){
        newTableRow('deviceList',arr[i].name, arr[i].ip, "WiFi-Tracker");
      }
    }
  } else if(tableId === 'eventList') {
    for (var i in arr) {
      if(arr[i].type === 0){
        newTableRow('eventList',arr[i].name, arr[i].description);
      }
    }
  } else if (tableId === 'userDeviceList'){
    for (var i in arr) {
      newTableRow('userDeviceList',arr[i].name, arr[i].ip, arr[i].mac);
    }
  }
}