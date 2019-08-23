<template>
    <div class="card card-success card-outline direct-chat direct-chat-success">
        <div class="card-header">
        <h3 class="card-title"><i class="fa fa-comments" aria-hidden="true"></i>&nbsp;&nbsp;連絡ボード</h3>

        <div class="card-tools">
            <span data-toggle="tooltip" :title="message_counts + 'New Messages'" class="badge bg-success">{{message_counts}}</span>
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <!-- Conversations are loaded here -->
        <div class="direct-chat-messages scroll_box">

            <!-- Message. Default to the left -->
            <div class="direct-chat-msg" :class="{'right':message.own}" v-for="message in message_list" :key="message.id">
            <div class="direct-chat-info clearfix">
                <span class="direct-chat-name" :class="message.own ?  'float-right': 'float-left'">{{ message.user.name }}</span>
                <span class="direct-chat-timestamp" :class="message.own ?  'float-left': 'float-right'">{{ message.created_at }}
                    <button v-if="message.own" type="button" class="btn btn-tool" @click="deleteMessage(message.id)"><i class="fa fa-times"></i>
                  </button>
                </span>
            </div>
            <!-- /.direct-chat-info -->
            <img class="direct-chat-img" :src="setProfileImage(message.image_path)" alt="Message User Image">
            <!-- /.direct-chat-img -->
            <div class="direct-chat-text">
                [{{ message.title }}] <br /> {{ message.message }}
            </div>
            <!-- /.direct-chat-text -->
            </div>
            <!-- /.direct-chat-msg -->
            <div class="overlay"  v-show="loading">
                <i class="fa fa-refresh fa-spin text-secondary"></i>
            </div>   

        </div>
        <!--/.direct-chat-messages-->


        </div>
        <!-- /.card-body -->
        <div class="card-footer">
        <form @submit.prevent="sendMessage()">
            <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Title</span>
                  </div>
                  <input v-model="form.title" type="text" name="title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="件名を入力 ...">
                  <has-error :form="form" field="title"></has-error>
            </div>
                    
            <div class="input-group">
            <input v-model="form.message" type="text" name="message" placeholder="メッセージを入力 ..." class="form-control" :class="{ 'is-invalid': form.errors.has('message') }">
            <has-error :form="form" field="message"></has-error>            
            <span class="input-group-append">
                <button type="submit" class="btn btn-success">送信</button>
            </span>
            </div>

        </form>
        </div>
        <!-- /.card-footer-->
    </div>
</template>
<style scoped>
  .scroll_box{
    overflow:scroll;
    height: 500px;
  }
</style>

<script>
    export default {
        data() {
            return {
                loading:false,
                message_list:[],
                currentUrl:'',
                form:new Form({
                    title:'',
                    message:'',
                }),                
            }
        },
        computed: {
            message_counts(){
                return this.message_list.length;
            }
        },
        methods: {
            sendMessage:function(){
            this.$Progress.start();
            // Submit the form via a POST request
            this.form.post('api/message_board')
                .then(({ data }) => { 
                console.log(data);this.$Progress.finish(); 
                Toast.fire({
                    type: 'success',
                    title: data.message
                    });
                    this.form.reset();
                    this.$eventHub.$emit('messageUpdated');
                })
                .catch(error => {
                console.log(error);
                this.$Progress.fail();
                });        
            }, 
            deleteMessage(id){
            Swal.fire({
                title: '本当に削除しますか?',
                // text: "削除後に取り消しは出来ません!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '削除する'
            }).then((result) => {
                //send request to server

                if (result.value) {
                this.form.delete("api/message_board/"+id)
                .then(({ data }) => { 
                    Swal.fire(
                    '削除完了!',
                    data.message,
                    'success'
                    );    
                    this.$eventHub.$emit('messageUpdated');                       
                })
                .catch(error => {
                    Swal.fire('削除失敗！','コメントの削除に失敗しました。','warning');
                    console.log(error);
                    // this.$Progress.fail();
                });              


                }
            });

            },                    
            setProfileImage:function(image_path){
                return this.currentUrl+'medcheck/public/' + image_path;    
            },            
            loadMessageBoard:function(){
                this.loading = true;
                axios.get('api/message_board')
                .then(({ data }) => {       
                    this.message_list = data;             
                })
                .finally(() => this.loading = false)
                ;  

            },        
            connectChannel() {
                ///重要！！！ medchecker_database_のprefixをチャネル名に付ける事！！
                Echo.channel("medchecker_database_message-updated").listen("MessageUpdated", e => {
                    this.loadMessageBoard();
                });
            }            
        },
        mounted() {
            this.loadMessageBoard();
            this.currentUrl = window.location.protocol + "//" + window.location.host + "/";            
            console.log('Component mounted.')
            this.$eventHub.$on('messageUpdated',()=>{
                this.loadMessageBoard();
            } ); 
            this.connectChannel();           
        }
    }
</script>
