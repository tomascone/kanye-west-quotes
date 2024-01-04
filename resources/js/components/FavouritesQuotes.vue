<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Favourites Quotes</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex flex-column" v-if="!quotes">
                                Loading
                            </li>
                            <li class="list-group-item d-flex flex-column" v-else-if="!quotes.length">
                                Empty
                            </li>
                            <li class="list-group-item d-flex flex-column" v-else v-for="(quote, id) in quotes">
                                {{ quote.quote }}
                                <button @click="removeFromFavourites($event, quote.id);" type="button" class="btn btn-danger btn-sm">Delete</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:"favouritesQuotes",
    data(){
        return {
            user:this.$store.state.auth.user,
            quotes:[],
        }
    },
    methods: {
        async getQuotes(){
            this.processing = true

            await axios.get('/sanctum/csrf-cookie')
            await axios.get('/api/favourites').then(response=>{
                this.quotes = response.data
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
        async removeFromFavourites(ev, id){
            this.processing = true

            ev.target.innerHTML = 'Waiting';
            ev.target.classList.toggle("btn-danger");
            ev.target.classList.toggle("btn-secondary");

            await axios.get('/sanctum/csrf-cookie')
            await axios.delete(`/api/favourites/${id}`).then(response=>{

                ev.target.innerHTML = 'Delete';
                ev.target.classList.toggle("btn-danger");
                ev.target.classList.toggle("btn-secondary");

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
    },
    mounted() {
        this.getQuotes();
    }
}
</script>