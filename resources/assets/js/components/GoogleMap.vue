<template>
    <div>
        <div ref="map" style="width:100%;height:100%;min-height:100px;"></div>
        <input v-model="keyword" @keyup.enter="search" class="map-item form-control" style="top: 60px; left: 150px;" placeholder="Please input"></input>
    </div>
</template>

<script>    
    export default {
        data() {
            return {
                mustDraw: false,
                map: undefined,
                keyword: '',
                markerDatas: [],
                markers: []
            };
        },
        props: {

        },
        mounted() {
            Event.$on('map-loaded', google => {
                googleLoaded = true;
                if(this.mustDraw) {
                    this.mustDraw = false;
                    this.draw();
                }
            });
            this.draw();
        },
        watch: {
            markerDatas() {
                for(let i=0,len=this.markers.length;i<len;i++) {
                    this.markers[i].setMap(null);
                    delete this.markers[i];
                }
                this.markers = [];
                for(let i=0,len=this.markerDatas.length;i<len;i++) {
                    let markerData = this.markerDatas[i];
                    let marker = new google.maps.Marker({
                        position: {lat: parseFloat(markerData.latitude), lng: parseFloat(markerData.longitude)},
                        map: this.map,
                        title: markerData.title
                    });
                    this.markers.push(marker);
                }
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0; i < this.markers.length; i++) {
                    bounds.extend(this.markers[i].getPosition());
                }

                this.map.fitBounds(bounds);
            }
        },
        methods: {
            draw() {
                let self = this;
                if (googleLoaded){
                    this.map = new google.maps.Map(this.$refs.map, {
                        center: {lat: -34.397, lng: 150.644},
                        zoom: 8
                    });
                    this.map.addListener('click', function(e) {
                        self.addPoint(e.latLng);
                        self.$emit('click',e.latLng);
                    });
                } else {
                    this.mustDraw = true;
                }
            },
            addPoint(latLng) {
                if(this.marker) this.marker.setMap(null);
                this.marker = new google.maps.Marker({
                    position: latLng,
                    map: this.map,
                    title: 'New Point'
                });
            },
            search() {
                this.markerDatas = [];
                axios.post('/api/node/search',{
                    keyword: this.keyword
                }).then(e => {
                    let data = e.data;
                    for(let i=0,len = data.length; i<len; i++) {
                        this.markerDatas.push({
                            id: data[i].id,
                            latitude: data[i].latitude,
                            longitude: data[i].longitude,
                            title: data[i].title
                        });
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        }
    }
</script>

<style>
    html, body, #app {
        width: 100%;
        height: 100%;
    }

    .map-item {
        width: 400px;
        height: 35px;
        position: fixed;
        opacity: 0.9;
    }
</style>
