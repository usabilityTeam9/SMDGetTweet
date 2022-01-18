let map;

function initMap() {
    var center;
    var marker;
    var infoWindow;

    var pinLatStr = latStr;
    var pinLngStr = lngStr;
    var pinLat = parseFloat(pinLatStr);
    var pinLng = parseFloat(pinLngStr);

    console.log(pinLat);
    console.log(pinLng);
    center = { lat: 35.183961197271, lng: 137.11132629433  };

    map = new google.maps.Map(document.getElementById("map"), {
    center: center,
    zoom: 8,
    });

    marker = new google.maps.Marker({ // マーカーの追加
        position: {
            lat: pinLat,
            lng: pinLng
        }, // マーカーを立てる位置を指定
        map: map // マーカーを立てる地図を指定
    });

    infoWindow = new google.maps.infoWindow({
        content: '<div class="sample">TAM 大阪</div>'
    });

    marker.addListener('click', function() { // マーカーをクリックしたとき
        infoWindow.open(map, marker); // 吹き出しの表示
    });
}
