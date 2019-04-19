// Refresh side
function refresh() {
  location.reload(true);
}

let ip = window.location.hostname;

// Send event to api
function NewEvent( eventName, eventDesc, deviceName, uDeviceName) {
 
  // const Http = new XMLHttpRequest();
  const api = 'http://'+ip+'/api/addevent.php?eventname=' + eventName + "&description=" + eventDesc + "&devicename=" + deviceName + "&userdevicename="+ uDeviceName;
 
  console.log(api);
  
  // Http.open("GET",api);
  // Http.send();
}

function NewDevice(name,mac) {

  let api = 'http://'+ip+'/api/addphone.php?name=' + name + '&mac='+ mac;
  let Http = new XMLHttpRequest();
  
  Http.open("GET", api);
  Http.send();
  Http.onreadystatechange =(e)=>{
    console.log(api);
    console.log(Http.response);
  }
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
      for (let i in myJson) {
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
  let table = document.getElementById(tableId).getElementsByTagName('tbody')[0];
  let row = table.insertRow(1);

  if (cell3Text == null) {
    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    cell1.innerText = cell1Text;
    cell2.innerText = cell2Text;
  } else {
    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    let cell3 = row.insertCell(2);
    cell1.innerText = cell1Text;
    cell2.innerText = cell2Text;
    cell3.innerText = cell3Text;
  }
}


// Skaf devices
function JSONToTable(urlJson, tableId) {
  fetch(urlJson)
  .then(function(response) {
    return response.json();
  })
  .then(function(myJson) {
    if (myJson.hasOwnProperty('error')) {
      alert("An error has occured, check console for more information!");
      console.log(myJson.error);
    } else {
      if(tableId === 'deviceList'){
        for (let i in myJson) {
          if(myJson[i].type === 0){
            newTableRow('deviceList',myJson[i].name, myJson[i].ip, "WiFi-Tracker");
          } else if(myJson[i].type === 1){
            newTableRow('deviceList',myJson[i].name, myJson[i].ip, "Wifi-Lås");
          } else if(myJson[i].type === 2){
              newTableRow('deviceList',myJson[i].name, myJson[i].ip, "Wifi-Lys");
          }

        }
      } else if(tableId === 'eventList') {
        for (let i in myJson) {
          if(myJson[i].type === 0){
            newTableRow('eventList',myJson[i].name, myJson[i].description);
          }
        }
      } else if (tableId === 'userDeviceList'){
        for (let i in myJson) {
          newTableRow('userDeviceList',myJson[i].name, myJson[i].mac);
        }
      }
    }
  });
}