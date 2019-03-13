// Refresh side
function refresh() {
  location.reload(true);
}

//--------------------
//    Tabel Id'er:
// - eventList
// - userDeviceList
// - deviceList
//--------------------

// Ny tabelrække
function newTableRow(tableId, cell1Text, cell2Text, cell3Text) {
  var table = document.getElementById(tableId);
  var row = table.insertRow(1);

  if (cell3Text == null) {
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    cell1.innerHTML = cell1Text;
    cell2.innerHTML = cell2Text;
  } else {
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    cell1.innerHTML = cell1Text;
    cell2.innerHTML = cell2Text;
    cell3.innerHTML = cell3Text;
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
    for (var i in myJson) {
      newTableRow('deviceList',myJson[i].name, myJson[i].email,myJson[i].id);
    }
  });
}
