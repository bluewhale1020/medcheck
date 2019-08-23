<template>
    <div class="container">

<div class="row mt-5">
          <div class="col-md-12">
            
            <h2>ユーザー一覧</h2>

            <div class="card mt-5 card-outline card-dark">
              <div class="card-header">
                 <h3 class="card-title"></h3>
                <div class="card-tools">
                      <button class="btn btn-success"  @click="newModal()">
                          <i class="fa fa-user-plus"></i>
                          新規ユーザー
                      </button>
                </div>

                <div class="float-left">
                  <div class="input-group input-group-sm">                  
                    <input type="text" @keyup.enter="search" v-model="search_data.search_key" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" @click="search" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="input-group-append">
                        <a href="#filter" data-toggle="collapse">&nbsp;<i class="fa fa-bars text-secondary h3" aria-hidden="true"></i>
                        </a>                      
                    </div>                    
                  </div>


                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">

<!-- .filter -->
<div class="card collapse" id="filter">
  <div class="card-body bg-gray">
    <h5 class="card-title">ユーザーの絞込</h5>
    <div class="col-12">
      <div class="form-group row">
        <div class="col-2 col-form-label"><label class="control-label">役柄</label></div>
          
          <div class="col-4">
            <select v-model="search_data.role_id" name="role_id" class="form-control">
            <option value="">--役柄を選択--</option>
            <option v-for="(role,id) in roles" :value="id" :key="id">{{ role }}</option>
            </select>          

          </div>
      </div>  
  <div class="form-group row">
    <div class="col-sm-2">オンライン</div>
    <div class="col-sm-4">
      <div class="form-check">
        <input v-model="search_data.online" class="form-check-input " type="checkbox" true-value="1" false-value="0" id="online_check">
        <!-- <label class="form-check-label" for="online_check">
          Example checkbox
        </label> -->
      </div>
    </div>
  </div>       

      </div>
    
  </div>
  <div class="card-footer">
      <a href.prevent="#" class="btn btn-outline-secondary" @click="search">絞り込む</a>
    </div>
</div><!-- /.filter -->


                <table class="table table-hover">
                  <thead>
                  <tr class="bg-secondary">
                    <th>ID</th>
                    <th>名前</th>
                    <th>役柄</th>
                    <th>Email</th>
                    <th>最終更新日</th>
                    <th>状況</th>
                    <th>操作</th>
                  </tr>
                  </thead>
                  <tbody>
                  <template v-for="user in users.data">
                  <tr  :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.role.name_jp }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.updated_at | myDate }}</td>
                    <td v-if="user.online == 1">
                      <span class="badge badge-success"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;オンライン</span>
                      </td>
                    <td v-else>
                      <span class="badge badge-danger h3"><i class="fa fa-user-times" aria-hidden="true"></i>&nbsp;&nbsp;オフライン</span>
                      </td>
                    <td>                 
                        <a href="#" @click="editModal(user)" class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i>編集</a>
                        &nbsp;&nbsp;
                        <a href="#" @click="deleteUser(user.id)" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash"></i>削除</a>
                    </td>

                  </tr>
                  </template>
                    <div class="overlay"  v-show="loading">
                      <i class="fa fa-refresh fa-spin text-secondary"></i>
                    </div>                  
                </tbody></table>
                <!-- page control -->
                <pagination :data="users" @pagination-change-page="loadUsers"></pagination>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>


<!-- Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 v-show="editmode" class="modal-title" id="addNewLabel">ユーザーの更新 </h5>
        <h5 v-show="!editmode" class="modal-title" id="addNewLabel">ユーザーの新規作成 </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="editmode ? updateUser() : createUser()">
      <div class="modal-body">

        <div class="form-group">
          <label>名前</label>
          <input v-model="form.name" type="text" name="name"
            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
          <has-error :form="form" field="name"></has-error>
        </div>
        <div class="form-group">
          <label>役柄</label>
          <select v-model="form.role_id" type="text" name="role_id"
            class="form-control" :class="{ 'is-invalid': form.errors.has('role_id') }">
          <option value="">--役柄を選択--</option>
          <option v-for="(role,id) in roles" :value="id" :key="id">{{ role }}</option>
          </select>
          <has-error :form="form" field="role_id"></has-error>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input v-model="form.email" type="text" name="email"
            class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
          <has-error :form="form" field="email"></has-error>
        </div>
        <div class="form-group">
          <label>パスワード</label>
          <input v-model="form.password" type="password" name="password"
            class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
          <has-error :form="form" field="password"></has-error>
        </div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        <button v-show="editmode" type="submit" class="btn btn-primary">更新</button>
        <button v-show="!editmode" type="submit" class="btn btn-success">登録</button>
      </div>

    </form>

    </div>
  </div>
</div>


    </div>
</template>

<script>
    export default {
      data:function(){
        return {
          search_data:{
            search_key: '',
            role_id:'',
            online:''
          } ,
          loading:false,
          editmode:false,
          form:new Form({
            id:'',
            name:'',
            role_id:'',
            email:'',
            password:''
          }),
          users:{},
          roles:{}
        }
      },
      methods: {
        search:function(){
          this.loadUsers();
        },
        editModal:function(user){
          this.form.reset();
          this.editmode = true;          
          $('#addNew').modal('show');
          this.form.fill(user);
        },
        newModal:function(){
          this.form.reset();
          this.editmode = false;          
          $('#addNew').modal('show');
        },
        deleteUser(id){
          Swal.fire({
            title: '本当に削除しますか?',
            text: "削除後に取り消しは出来ません!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '削除する!'
          }).then((result) => {
            //send request to server

              if (result.value) {
              this.form.delete("api/user/"+id)
              .then(({ data }) => { 
                // this.users = data.data;

                Swal.fire(
                  '削除完了!',
                  data.message,
                  'success'
                );    
                this.$eventHub.$emit('usersUpdated');                       
              })
              .catch(error => {
                Swal.fire('削除失敗！','サーバー上でレコード削除に失敗しました。','warning');
                console.log(error);
                // this.$Progress.fail();
              });              


            }
          });

        },
        loadUsers:function(page = 1){
          this.loading = true;
          // axios.get('api/user?page=' + page
          axios.get('api/user',{
            params: {
              // ここにクエリパラメータを指定する
              'page': page,
              'search_key':this.search_data.search_key,
              'role_id':this.search_data.role_id,
              'online':this.search_data.online
            }
          })
          .then(({ data }) => { 
            this.users = data;
            
            })
            .finally(() => this.loading = false); 
        },
        loadRoleList:function(){
          axios.get("api/role")
          .then(({ data }) => { 
            this.roles = data;
            
            });          
        },
        updateUser:function(){
          this.$Progress.start();
          // Submit the form via a PUT request
          this.form.put('api/user/'+this.form.id)
          .then(({ data })=>{
              console.log(data);this.$Progress.finish(); 
              Toast.fire({
                  type: 'success',
                  title: data.message
                });
                $('#addNew').modal('hide');

                this.$eventHub.$emit('usersUpdated');
          })
          .catch((error)=>{
            console.log(error);
            this.$Progress.fail();            
          });
        },
        createUser:function(){
          this.$Progress.start();
          // Submit the form via a POST request
          this.form.post('api/user')
            .then(({ data }) => { 
              console.log(data);this.$Progress.finish(); 
              Toast.fire({
                  type: 'success',
                  title: data.message
                });
                $('#addNew').modal('hide');

                this.$eventHub.$emit('usersUpdated');
              })
            .catch(error => {
            console.log(error);
            this.$Progress.fail();
            });        
        }
      },  
        mounted() {
          this.loadUsers();
          this.loadRoleList();
          console.log('Component mounted.');

          this.$eventHub.$on('usersUpdated',()=>{
            this.loadUsers();
          } );
        }
    }
</script>
