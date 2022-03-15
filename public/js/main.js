let urlCities = "http://localhost/Sortir/public/api/cities/";
let urlPlaces = "http://localhost/Sortir/public/api/places/";


async function mounted() {
    if (document.getElementById('idPlace')) {
        let idPlace = document.getElementById('idPlace').innerText
        this.selectedPlace = await axios.get(urlPlaces + idPlace).then(res => res.data);
        this.placeId = idPlace;

    }
    ;
    this.cities = await axios.get(urlCities).then((res) => res.data);
    this.allPlaces = await axios.get(urlPlaces).then((res) => res.data);
    this.selectedPlaces = this.allPlaces;
}

const app = new Vue({
    el: "#app",
    data: {
        cities: null,
        allPlaces: null,
        selectedPlace: null,
        selectedPlaces: null,
        selectedCity: null,
        placeId: null,
        newPlace: {
            name: null,
            street: null,
            latitude: null,
            longitude: null,
            city: null
        }
    },
    mounted,
    methods: {
        log(place) {
            this.placeId = place.id;
            this.selectedPlace = place;
            console.log(place);
        },
        filter(city) {
            this.selectedPlaces = this.allPlaces.filter(function (p) {
                if (p.city.id == city.id) {
                    return p;
                }
            });
        },
        postPlace() {
            console.log(this.newPlace);
            axios.post(urlPlaces, this.newPlace).then(res => this.allPlaces.push(res.data));
        },
        test() {
            console.log(this.placeId);
        }
    },
});
