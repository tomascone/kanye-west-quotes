<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Quotes</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex flex-column" v-if="!quotes">
                                Loading
                            </li>
                            <li class="list-group-item d-flex flex-column" v-else-if="!quotes.length">
                                Empty
                            </li>
                            <li class="list-group-item d-flex flex-column" v-else v-for="quote in quotes" :key="quote">
                                {{ quote }}
                                <button @click="addToFavourites($event, quote)" type="button" class="btn btn-primary btn-sm">Add to Favourites</button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <button @click="getQuotes($event)" type="button" class="btn btn-primary btn-sm">Refresh</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import { useToast } from "vue-toastification";

export default {
    name:"dashboard",
    data(){
        return {
            user:this.$store.state.auth.user,
            quotes:[],
            processing:false,
        }
    },
    setup() {
      const toast = useToast();

      return { toast }
    },
    methods: {
        async getQuotes(ev = false){
            this.processing = true

            if (ev) {
                ev.target.innerHTML = 'Waiting';
                ev.target.classList.toggle("btn-primary");
                ev.target.classList.toggle("btn-secondary");
            }

            await axios.get('/sanctum/csrf-cookie')
            await axios.get('/api/quotes/5').then(response=>{
                this.quotes = response.data.quotes

                const filteredList = this.quotes.filter((q) => q.id !== id).map((q) => { return q});

                this.quotes = filteredList;
            }).catch(({response})=>{
                if(response.status===422){
                    this.validationErrors = response.data.errors
                }else{
                    this.validationErrors = {}
                    alert(response.data.message)
                }
            }).finally(()=>{
                this.processing = false
            })
        },
        async addToFavourites(ev, quote){
            this.processing = true

            ev.target.innerHTML = 'Waiting';
            ev.target.classList.toggle("btn-primary");
            ev.target.classList.toggle("btn-secondary");

            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/api/quotes/add_to_fav', {'quote' : quote}).then(response=>{
                

                this.toast.success("Quote Added!", {
                    timeout: 5000
                });

                const filteredList = this.quotes.filter((q) => q !== quote).map((q) => { return q});

                this.quotes = filteredList;
            }).catch(({response})=>{
                if(response.status===422){
                    this.validationErrors = response.data.errors
                }else{
                    this.validationErrors = {}
                    alert(response.data.message)
                }
            }).finally(()=>{
                this.processing = false
            })
        },
    },
    mounted() {
        this.getQuotes();
    }
}
</script>