<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add GitHub Token</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="create">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>GitHub Token</label>
                                    <input type="text" class="form-control" v-model="user_data.github_token" required placeholder="Please enter GitHub Token">
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-primary" :disabled="github_token_flag == false" @click="getStarredRepo">Get Starred Repos</button>
                            </div>
                        </div>                        
                    </form>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    <h4>GitHub Token</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <span v-html="github_token_display"></span>
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
            <br>
            <div class="card" v-if="is_data_fetched != null">
                <div class="card-header">
                    <h4>GitHub Starred Repo</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2" v-if="is_data_fetched == true">
                            <table id="github-repo">
                                <tr>
                                    <th>Repositery</th>
                                </tr>
                                <tr v-for="repo in starred_repo">
                                    <td>{{ repo.name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12 mb-2" v-if="is_data_fetched == false">
                            Data Fetching
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:"add-github-token",
    data(){
        return {
            user_data:{
                github_token:"",
            },
            github_token_display: "",
            github_token_flag: false,
            starred_repo: [],
            is_data_fetched: null
        }
    },
    methods:{
        async getGitHubToken(){
            await axios.get('/get-github-token').then(response=>{
                this.github_token_display = response.data.github_token_display;
                this.github_token_flag = response.data.github_token_flag;
            }).catch(error=>{
                Vue.$toast.error(error.response.data.message);
            })
        },
        create(){
            axios.post('/save-github-token',this.user_data).then(response=>{
                Vue.$toast.success(response.data.message);
                this.getGitHubToken();
                this.user_data.github_token = '';
            }).catch(error=>{
                Vue.$toast.error(error.response.data.message);
            })
        },
        getStarredRepo(){
            this.is_data_fetched = false;
            axios.get('/get-github-starred-repo').then(response=>{
                this.starred_repo = response.data.starred_repo;
                this.is_data_fetched = true;
            }).catch(error=>{
                this.is_data_fetched = null;
                Vue.$toast.error(error.response.data.message);
            })
        }
    },
    mounted() {
        this.getGitHubToken()
    }
}
</script>