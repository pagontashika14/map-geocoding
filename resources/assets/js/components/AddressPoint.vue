<template>
    <div class="fixed-top-right well well-lg">
        <div class="form-group">
            <label for="sel1">Select list:</label><br>
            <el-select v-model="addressSelected" placeholder="Select" style="width: 100%">
                <el-option
                    v-for="item in addresses"
                    :label="item.formatted_address"
                    :value="item">
                </el-option>
            </el-select>
            <label class="margin-top-20px" for="title">Title:</label>
            <input v-model="title" type="text" class="form-control" name="title">
            <div class="row margin-top-10px">
                <div class="col-md-8">
                    <label for="address">Address:</label>
                    <input v-model="address" type="text" class="form-control" name="address">
                </div>
                <div class="col-md-4">
                    <label for="streetNumber">Number:</label>
                    <input v-model="streetNumber" type="text" class="form-control" name="streetNumber">
                </div>
            </div>
            <div class="row margin-top-10px">
                <div class="col-md-6">
                    <label for="province">City/Provice:</label>
                    <input v-model="province" type="text" class="form-control" name="province">
                </div>
                <div class="col-md-6">
                    <label for="country">Country:</label>
                    <input v-model="country" type="text" class="form-control" name="country">
                </div>
            </div>
            <div class="row margin-top-10px">
                <div class="col-md-6">
                    <label for="lat">Latitude:</label>
                    <input v-model="lat" type="text" class="form-control" name="lat">
                </div>
                <div class="col-md-6">
                    <label for="lng">Longitude:</label>
                    <input v-model="lng" type="text" class="form-control" name="lng">
                </div>
            </div>
            <div class="row margin-top-10px">
                <div class="col-md-3">
                    <button @click="addNode" class="btn btn-success">Add</button>
                </div>
                <div class="col-md-9 flexbox-container" style="height: 36px;">
                    <label class="">{{status}}</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>    
    export default {
        data() {
            return {
                addressSelected: '',
                streetNumber: '',
                title: '',
                address: '',
                province: '',
                country: '',
                status: '<= Click to add a new Point'
            };
        },
        watch: {
            addressSelected(value) {
                if(!value) {
                    this.title = '';
                    this.streetNumber = '';
                    this.address = '';
                    this.province = '';
                    this.country = '';
                    return;
                }
                this.title = this.findType('premise').long_name;
                this.streetNumber = this.findType('street_number').short_name;
                this.province = this.findType('administrative_area_level_1').long_name;
                this.country = this.findType('country').long_name;
                let location = value.geometry.location;
                let streets = [];
                let components = value.address_components;
                for(let i=0, len=components.length; i < len; i++) {
                    let component = components[i];
                    if(_.find(component.types,e => e == 'premise' 
                        || e == 'street_number' 
                        || e == 'administrative_area_level_1' 
                        || e == 'country'
                        || e == 'postal_code'
                        || e == 'locality')
                    ) {
                        continue;
                    }
                    if(_.find(streets,e => e == component.long_name)) {
                        continue;
                    }
                    streets.push(component.long_name);
                }
                let street = '';
                if(streets.length > 0) {
                    street = streets[0];
                    for(let i=1,len=streets.length;i<len;i++) {
                        street += ', ' + streets[i];
                    }
                }
                this.address = street;
            },
            addresses(value) {
                this.addressSelected = value[0];
            }
        },
        props: {
            addresses: {default: []},
            lat: '',
            lng: ''
        },
        mounted() {
            
        },
        methods: {
            findType(type) {
                let addressComponents = this.addressSelected.address_components;
                let t = _.find(addressComponents, e => {
                    return _.find(e.types, l => l == type) ? true : false;
                });
                if(t) return t;
                return {short_name: '', long_name: ''};
            },
            addNode() {
                console.log(this.status);
                let self = this;
                if(this.lat != '0' && this.lng != '0') {
                    axios.post('/api/node/create',{
                        latitude: this.lat,
                        longitude: this.lng,
                        title: this.title,
                        street_number: this.streetNumber,
                        city: this.province,
                        country: this.country,
                        address: this.address
                    }).then(function(response){
                        self.status = 'Success';
                    }).catch(function(error){
                        self.status = 'Error';
                    });
                }
            }
        }
    }
</script>

<style>
    .fixed-top-right {
        position: fixed;
        z-index: 100;
        background-color: rgba(69,90,100 ,0.6);
        top: 70px;
        right: 20px;
        padding: 10px 20px 0px 15px;
    }
    .fixed-top-right label {
        color: white;
    }
</style>
