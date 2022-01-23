let map;
var center = { lat: 35.183961197271, lng: 137.11132629433  };
var marker = [];
var infoWindow = [];

var pinLat = [];
var pinLng = [];

function initMap() {
    infoWindow = [];

    latStr.forEach(val => {
        pinLat.push(parseFloat(val))
    });
    lngStr.forEach(val => {
        pinLng.push(parseFloat(val))
    });

    latStr.forEach(val => {
        console.log(val);
        console.log(typeof(val));
    });
    lngStr.forEach(val => {
        console.log(val);
        console.log(typeof(val));
    });

    map = new google.maps.Map(document.getElementById("map"), {
    center: center,
    zoom: 8,
    });

    // マーカー毎の処理
    for (var i = 0; i < pinLat.length; i++) {
        markerLatLng = new google.maps.LatLng({lat: pinLat[i], lng: pinLng[i]}); // 緯度経度のデータ作成
        marker[i] = new google.maps.Marker({ // マーカーの追加
        position: markerLatLng, // マーカーを立てる位置を指定
            map: map // マーカーを立てる地図を指定
        });

        infoWindow[i] = new google.maps.InfoWindow({ // 吹き出しの追加
            content: '<div class="sample">test</div>' // 吹き出しに表示する内容
        });

        markerEvent(i); // マーカーにクリックイベントを追加
    }
}

function markerEvent(i) {
    marker[i].addListener('click', function() { // マーカーをクリックしたとき
      infoWindow[i].open(map, marker[i]); // 吹き出しの表示
    });
}
