<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Users</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex flex-column" v-if="!users">
                                Loading
                            </li>
                            <li class="list-group-item d-flex flex-column" v-else-if="!users.length">
                                Empty
                            </li>
                            <li class="list-group-item d-flex flex-column mt-3 border-3" v-else v-for="(user, id) in users">
                                <button type="button" class="btn btn-primary">
                                    {{ user.name }} - {{ user.email }} <span class="badge bg-secondary">{{ user.quotes.length }}</span>
                                </button>

                                <div class="btn-group" role="group">
                                    <router-link :to="{name:'updateProfileUser', params: { id: user.id }}" class="btn btn-success btn-sm mt-2">Update Profile<span class="sr-only"></span></router-link>

                                    <button @click="removeFromUsers($event, user.id);" type="button" class="btn btn-danger btn-sm mt-2">Delete User</button>
                                </div>

                                
                                <ul class="list-group mt-2">
                                    <h4>Favourites Quotes</h4>
                                    <li class="list-group-item d-flex flex-column" v-for="(quote, id) in user.quotes">
                                        {{ quote.quote }}
                                        <button @click="removeFromFavourites($event, quote.id);" type="button" class="btn btn-danger btn-sm">Delete Quote</button>
                                    </li>
                                </ul>
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
    name:"users",
    data(){
        return {
            user:this.$store.state.auth.user,
            users:[],
        }
    },
    methods: {
        async getUsers(){
            this.processing = true

            await axios.get('/sanctum/csrf-cookie')
            await axios.get('/api/users').then(response=>{
                this.users = response.data.users
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
        async removeFromUsers(ev, id){
            this.processing = true

            ev.target.innerHTML = 'Waiting';
            ev.target.classList.toggle("btn-danger");
            ev.target.classList.toggle("btn-secondary");

            await axios.get('/sanctum/csrf-cookie')
            await axios.delete(`/api/users/${id}`).then(response=>{

                ev.target.innerHTML = 'Delete';
                ev.target.classList.toggle("btn-danger");
                ev.target.classList.toggle("btn-secondary");

                const filteredList = this.users.filter((q) => q.id !== id).map((q) => { return q});

                this.users = filteredList;
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

                this.getUsers();
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
        this.getUsers();
    }
}
</script>