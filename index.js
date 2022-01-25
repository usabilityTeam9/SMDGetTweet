let map;
var center = { lat: 35.183961197271, lng: 137.11132629433  }; //中心は愛知工業大学
var marker = []; //マーカーの配列

var pinLat = []; //float型の緯度
var pinLng = []; //float型の経度

function initMap() {

    //緯度情報を全てstring->floatにする
    latStr.forEach(val => {
        pinLat.push(parseFloat(val));
    });
    //経度情報を全てstring->floatにする
    lngStr.forEach(val => {
        pinLng.push(parseFloat(val));
    });

    //マップのインスタンスを生成　中心は愛工大　倍率は8倍(大体愛知県が見えるくらい)
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
            content: windowText[i] // 吹き出しに表示する内容
        });

        markerEvent(i); // マーカーにクリックイベントを追加
    }
}

function markerEvent(i) {
    marker[i].addListener('click', function() { // マーカーをクリックしたとき
      infoWindow[i].open(map, marker[i]); // 吹き出しの表示
    });
}
