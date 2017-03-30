export class GoogleMapService {
    constructor() {

    }

    getGeoCoding(lat,lng,callback) {
        $.ajax({
            url: GOOGLE_GEOCODING_URL,
            data: {
                latlng: lat + ',' + lng
            },
            success: function(e) {
                callback(e.results);
            },
            error: function(e) {
                console.log(e);
            }
        });
    }

    getStreetNumber(e) {

    }
}