<template>
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>オンライン</h5>
    <template v-for="user in users">
        <div :key="user.id">
            <div class="">
                <i class="fa fa-user mr-2"></i> {{ user.name }}
            <span class="float-right text-yellow text-sm">{{ user.role.name_jp }}</span>
            </div>                
            <div class="dropdown-divider"> </div>          
        </div>
    </template>

      <!-- <a href="#" class="">
        <i class="fa fa-envelope mr-2"></i> 4 new messages
        <span class="float-right text-muted text-sm">3 mins</span>
      </a>
      <div class="dropdown-divider"></div>
      <a href="#" class="">See All Notifications</a>


      <p>Sidebar content</p> -->
    </div>
</template>

<script>
    export default {
        props:['online_users'],
        data() {
            return {
                users:[]
            }
        },
        methods: {
            loadUsers:function(){
            // axios.get('api/user?page=' + page
            axios.get('api/online_user',{
                // params: {
                // // ここにクエリパラメータを指定する
                //     'side_menu_bar':true
                // }
            })
            .then(({ data }) => { 
                    this.users = data;
                
                })
                // .finally(() => this.loading = false)
                ; 
            },           
            connectChannel() {
                ///重要！！！ medcheck_database_のprefixをチャネル名に付ける事！！
                Echo.channel("medcheck_database_login").listen("LoginEvent", e => {
                    this.loadUsers();              
                });
            }            
        },
        mounted() {
            this.users = this.online_users;
            console.log('Component mounted.');
            this.connectChannel();            
        }
    }
</script>
