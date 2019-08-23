<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-md-12">
        <h2>検査エリア一覧</h2>

        <div class="card mt-5 card-outline card-dark">
          <div class="card-header">
            <h3 class="card-title"></h3>
            <div class="card-tools">
                <button class="btn btn-success"  @click="newModal()">
                    <i class="fa fa-user-plus"></i>
                    新規エリア追加
                </button>
            </div>

            <div class="float-left">
              <div class="input-group input-group-sm">
                <input
                  type="text"
                  @keyup.enter="search"
                  v-model="search_data.search_key"
                  name="table_search"
                  class="form-control float-right"
                  placeholder="Search"
                />

                <div class="input-group-append">
                  <button type="submit" @click="search" class="btn btn-default">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
                <div class="input-group-append">
                  <a href="#filter" data-toggle="collapse">
                    &nbsp;
                    <i class="fa fa-bars text-secondary h3" aria-hidden="true"></i>
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
                <h5 class="card-title">検査エリアの絞込</h5>
                <div class="col-12">
                  <div class="form-group row">
                    <div class="col-2 col-form-label">
                      <label class="control-label">エリアカテゴリ</label>
                    </div>

                    <div class="col-4">
                      <select v-model="search_data.exam_category_id" name="exam_category_id" class="form-control">
                        <option value="">--エリアカテゴリを選択--</option>
                        <option :value="id" v-for="(item,id) in category_list" :key="id">{{ item }}</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
              <div class="card-footer">
                <a href.prevent="#" class="btn btn-outline-secondary" @click="search">絞り込む</a>
              </div>
            </div>
            <!-- /.filter -->

            <table class="table table-hover table-bordered progress-table text-center">
              <thead>
                <tr class="bg-secondary">
            　　　<th scope="col"  style="min-width: 120px;">操作</th>  
                  <th scope="col" style="min-width: 150px;">エリア名</th>
                  <th scope="col" style="min-width: 150px;">エリアカテゴリ</th>
                  <th  v-for="(column,idx) in columns" :key="idx" scope="col" style="min-width: 120px;">
                    {{ column }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="area in exam_areas.data" :key="area.id">
                    <td>
                      <div class="btn-group" role="group" aria-label="レコード操作グループ">
                        <button type="button" @click.prevent="editModal(area)" class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i></button>
                        <button type="button" @click.prevent="deleteExamArea(area.id)" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash"></i></button>                   
                      </div>
                    </td> 
                  <td>{{ area.name }}</td>
                  <td>{{ area.exam_category.name_jp }}</td>

                  <td
                    v-for="(nameJp,columnName) in columns" :key="columnName">
                    <span v-if="area[columnName] > 0" class="text-success">✔</span>
                  </td>
                </tr>
                <div class="overlay" v-show="loading">
                  <i class="fa fa-refresh fa-spin text-secondary"></i>
                </div>
              </tbody>
            </table>
            <!-- page control -->
            <pagination :data="exam_areas" @pagination-change-page="search"></pagination>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>



<!-- Modal -->
<div class="modal fade" id="editArea" tabindex="-1" role="dialog" aria-labelledby="editAreaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header"  :class="{'bg-primary':editmode,'bg-success':!editmode}">
        <h5 v-show="editmode" class="modal-title" id="editAreaLabel">検査エリアの更新 </h5>
        <h5 v-show="!editmode" class="modal-title" id="editAreaLabel">検査エリアの新規作成 </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="editmode ? updateExamArea() : createExamArea()"  @keydown.enter.prevent>
      <div class="modal-body">


      <div class="container">
        <div class="row">
          <div class="col-6">
            <legend class="text-primary">〇エリア基本情報</legend>       
          <div class="card">
            <div class="card-body">
            <div class="row">

            <div class="form-group col-12">
              <label>エリア名称</label>
              <input v-model="form.name" type="text" name="name" @keyup.enter="moveToNext(1)" :ref="1"
                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
              <has-error :form="form" field="name"></has-error>
            </div>
            <div class="form-group col-6">
              <label>エリアカテゴリ</label>
              <select v-model="form.exam_category_id" name="exam_category_id" @keyup.enter="moveToNext(2)" :ref="2"
                class="form-control" :class="{ 'is-invalid': form.errors.has('exam_category_id') }">
                    <option :value="id" v-for="(item,id) in category_list" :key="id">{{ item }}</option>
              </select>              
              <has-error :form="form" field="exam_category_id"></has-error>
            </div>        
 
            </div>
            <!-- row -->
            
          </div>
            <!-- col-6 -->
        </div></div>
          <!-- card -->
          <div class="col-6">
            <legend class="text-primary">Picture</legend>       
          <div class="card">
            <div class="card-body">
            <div class="preview text-center">
                <img :src="area_image_path" class="rounded img-thumbnail" alt="..."> 
            </div>

            <!-- preview -->
            <div class="image-upload"> 
              <div class="form-group mt-4">       
                    <input id="area_image" @change="selectedFile" type="file" style="display:none" ref="input_file">
                    <div class="input-group">
                      <input type="text" v-model="fileName" id="display_input" class="form-control" @click="onChangeFile()" readonly  :class="{ 'is-invalid': form.errors.has('file') }">
                      <span class="input-group-btn"><button type="button" class="btn btn-secondary" @click="onChangeFile()" @keyup.enter="onChangeFile()" :ref="3">選択</button></span>
                      <has-error :form="form" field="file"></has-error>
                    </div>  
              </div>
            </div>
            <!-- upload -->

        </div></div>
        <!-- card -->
          </div>

        </div></div>
        <!-- container row -->


      <div class="container mt-3">
        <div class="row">

        <legend class="text-primary">〇対象となる検査項目</legend>

          <div class="card">
            <div class="card-body">
                    <div class="container">
                  <div class="row">

                  <template v-for="(name_jp, colName) in this.columns">
                    <div class="form-group col-3" :key="colName">
                      <input v-model="form[colName]" type="checkbox"  :name="colName" class="form-check-input" true-value="1" false-value="0"
                      :class="{ 'is-invalid': form.errors.has(colName) }" id="colName" >
                      <label class="form-check-label" :for="colName">{{ name_jp }}</label>
                      <has-error :form="form" :field="colName"></has-error>                
                    </div>
                  </template>

                  </div>
                  <!-- div.row -->
                  </div>
                  <!-- div.container --> 
            </div>
            <!-- card-body -->
          </div>
          <!-- card -->



        </div>
        <!-- div.row -->
        </div>
        <!-- div.container -->

      <div class="container mt-3">
        <div class="row">

        <legend class="text-primary">〇関連する役職</legend>

          <div class="card">
            <div class="card-body">
                    <div class="container">
                  <div class="row">

                  <template v-for="(name_jp, id) in this.roles">
                    <div class="form-group col-3" :key="'role_' + id">
                      <input v-model="form.role_ids[id]" type="checkbox" :name="'role_' + id" class="form-check-input" true-value="1" false-value="0"
                      :id="'role_' + id" >
                      <label class="form-check-label" :for="'role_' + id">{{ name_jp }}</label>
                      <has-error :form="form" :field="'role_' + id"></has-error>                
                    </div>
                  </template>

                  </div>
                  <!-- div.row -->
                  </div>
                  <!-- div.container --> 
            </div>
            <!-- card-body -->
          </div>
          <!-- card -->



        </div>
        <!-- div.row -->
        </div>
        <!-- div.container -->

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
<style scoped>
.img-thumbnail {
  width:100px;
}

</style>
<script>
import { isUndefined, isNull, isNumber } from 'util';
import objectToFormData from 'object-to-formdata';
import { cloneDeep } from 'lodash';


    export default {
        data: function() {
            return {
            search_data: {
                search_key: '',
                exam_category_id: '',
            },
            fileName:'',
            area_image_path:'',
            currentUrl:'',
            editmode:false,
            form:new Form({
                id:'',
                name:'',
                exam_category_id:'',
                role_ids:{},
                file:null,
            }),            
            loading: false,
            load_mode:'init',
            exam_areas: {},
            columns: {},
            category_list:{},
            roles:{}
            };
        },    
        methods: {
            // アップロードした画像を表示
            createImage() {
              const reader = new FileReader();
              reader.onload = e => {
                this.area_image_path = e.target.result;
              };
              reader.readAsDataURL(this.form.file);
            },          
            selectedFile: function(e) {
                // 選択された File の情報を保存しておく
                e.preventDefault();
                var files;
                if(e.target.files){
                    files =  e.target.files;
                    let temp_filename = this.$refs.input_file.value;            
                    this.fileName = temp_filename.replace("C:\\fakepath\\", "");                    
                }else{

                    files =  e.dataTransfer.files;
                    this.fileName = files[0].name;
                }
              

                this.form.file = files[0];
                this.createImage();
            },            
            onChangeFile:function(){
                this.$refs.input_file.click();
            },
            getImagePath:function(area_id){
              axios.get("api/exam_area/image_path/" + area_id).then(({ data }) => {
                this.area_image_path = this.currentUrl+'medcheck/public/img/' + data;
              });
            },
            setImagePath:function(area_id=null){
              this.area_image_path = '';
              if(isNull(area_id)){
                this.area_image_path = this.currentUrl+'medcheck/public/img/area_default.jpg';

              }else{
                this.getImagePath(area_id);
              }
              this.fileName = '';
              this.$refs.input_file.value = '';
            },
            moveToNext:function(input_idx){
              if(!isUndefined(this.$refs[input_idx+1])){
                  this.$refs[input_idx+1].focus();//[0]

              }
            }, 
            createForm:function(){
                Object.keys(this.columns).forEach(key => {
                this.form[key] = 0;
                });

            },                          
            editModal:function(area){
              this.form.reset();
              this.form.errors.clear(); 
              this.editmode = true;          
              $('#editArea').modal('show');
              this.form.fill(area);
              this.fillRoleIds(area);
              this.mutateForm();
              this.setImagePath(area.id);
              },
            fillRoleIds:function(area = null){
              var ids = {};
                Object.keys(this.roles).forEach(key => {
                ids[key] = 0;
                });
              if(!isNull(area)){
                for(const role of area.roles){
                  ids[role.id] = 1;
                }

              }

              Vue.set(this.form,"role_ids",ids);
            
            },
            mutateForm:function(){
              let clonedForm = cloneDeep(this.form);
              this.form = clonedForm;
            },
            newModal:function(){
              this.form.reset();
              this.form.errors.clear(); 
              this.editmode = false;          
              $('#editArea').modal('show');
              this.fillRoleIds();
              this.mutateForm();
              this.setImagePath();                         
            },

            search: function(page = 1) {
                this.loadExamAreas(page);
            },            
            loadExamAreas: function(page = 1) {
              this.loading = true;
              axios.get("api/manage_exam_area", {
              params: {
                  // ここにクエリパラメータを指定する
                  page: page,
                  search_key: this.search_data.search_key,
                  exam_category_id: this.search_data.exam_category_id,
              }
              })
              .then(({ data }) => {
                  this.exam_areas = data.exam_areas;

                  if(this.load_mode == 'init'){             
                      this.category_list = data.category_list;
                      this.roles = data.roles;
                      this.load_mode = "update";
                  } 

              })
              .finally(() => (this.loading = false));
            },
            loadTableColumns: function() {
              axios.get("api/exam_area/columns").then(({ data }) => {
                this.columns = data;
                this.createForm();
              });
            },
            updateExamArea:function(){
                this.$Progress.start();
            
                // Submit the form via a PUT request 
                this.form.submit('post','api/manage_exam_area/' + this.form.id
                , {//axios config
                    headers: {
                    // ここでPUTに置き換える
                        'X-HTTP-Method-Override': 'PUT',
                    },                
                    // Transform form data to FormData
                    transformRequest: [function (data, headers) {
                        return objectToFormData(data)
                    }]
                }
                )
                .then(({ data }) => { 
                    console.log(data);

                    this.$Progress.finish(); 
                    Toast.fire({
                    type: 'success',
                    title: data.message
                    });
                    this.search(this.exam_areas.current_page);                
                    $('#editArea').modal('hide');

                    this.$eventHub.$emit('examAreaUpdated');
                })
                .catch(error => {
                    console.log(error);
                    this.$Progress.fail();
                    if(Object.keys(error.response).length > 0){
                      var message = error.response.data.message;
                    }else{
                      var message = "検査エリア保存エラー！";
                    }

                    Toast.fire({
                    type: 'error',
                    title: message
                    });             
                });        
            },             
            createExamArea:function(){
                this.$Progress.start();
        
                // Submit the form via a POST request
                this.form.submit('post','api/manage_exam_area'
                , {//axios config          
                    // Transform form data to FormData
                    transformRequest: [function (data, headers) {
                        return objectToFormData(data)
                    }]
                }
                )            
                // this.form.post('api/manage_exam_area')
                .then(({ data }) => { 
                    console.log(data);

                    this.$Progress.finish(); 
                    Toast.fire({
                    type: 'success',
                    title: data.message
                    });
                    this.search(this.exam_areas.current_page);                
                    $('#editArea').modal('hide');

                    this.$eventHub.$emit('examAreaUpdated');
                })
                .catch(error => {
                    console.log(error);
                    this.$Progress.fail();
                    if(Object.keys(error.response).length > 0){
                      var message = error.response.data.message;
                    }else{
                      var message = "検査エリア保存エラー！";
                    }

                    Toast.fire({
                    type: 'error',
                    title: message
                    });             
                });        
            },
            deleteExamArea(id){
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
                  this.form.delete("api/manage_exam_area/"+id)
                  .then(({ data }) => { 
                    Swal.fire(
                      '削除完了!',
                      data.message,
                      'success'
                    );    
                    this.search(this.exam_areas.current_page);                    
                    this.$eventHub.$emit('examAreaUpdated');                       
                  })
                  .catch(error => {
                    Swal.fire('削除失敗！','サーバー上でエリア削除に失敗しました。','warning');
                    console.log(error);
                  });              


                }
              });

            },                                    
        },    
        mounted() {
            this.loadTableColumns();
            this.loadExamAreas();
            this.currentUrl = window.location.protocol + "//" + window.location.host + "/";            
            console.log('Component mounted.')
        }
    }
</script>
