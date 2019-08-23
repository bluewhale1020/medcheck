<template>
    <div class="container">

<div class="row mt-5">
          <div class="col-md-12">
            
            <h2>予約リスト <small class="badge badge-secondary">{{ reserve_infos.total }}名</small></h2>
          <div class="row mt-5">
            <div class="col-12">
              <div class="card card-outline card-primary">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fa fa-barcode fa-lg"></i>&nbsp;&nbsp;バーコード受付</h3>
           
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="alert alert-success" v-show="barcode_ready">
                    <h5><i class="icon fa fa-check"></i> バーコード入力待ち!</h5>
                    受診票のバーコードをリーダーで読み込んでください
                  </div>
                  <div class="alert alert-danger"  v-show="!barcode_ready">
                    <h5><i class="icon fa fa-ban"></i> バーコード入力不可!</h5>
                    バーコードリーダーを使用する前に、バーコード欄にマウスでフォーカスして下さい。
                  </div>

                    <form class="form-inline">
                      <div class="col-8">
                        <!-- <label class="sr-only" for="barcode-box">バーコード欄</label> -->
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">バーコードを入力</span>
                          </div>
                          <input type="text" ref="barcode" v-model="barcode_no" @keyup.enter="readBarcode()" @focus="barcode_ready = true;barcode_no = ''" @blur="barcode_ready = false"   class="form-control" id="barcode-box" placeholder="バーコード欄">
                        </div>
                      </div>
                      <div class="col-4">
                        <!-- <label class="sr-only" for="serial_number-box">通番</label> -->
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">通番</span>
                          </div>
                          <input v-model="current_number" type="number" class="form-control" id="serial_number-box" placeholder="通番">
                        </div>
                      </div>


                    </form>

                </div>
                <!-- /.card-body -->
              </div>
            </div>
          </div>


            <div class="card mt-5  card-outline card-primary">
              <div class="card-header">
                 <h3 class="card-title"></h3>
                <div class="card-tools">
                  <div class="btn-group">
                    <button class="btn btn-success"  @click="newModal()" ref="addBtn">
                        <i class="fa fa-user-plus"></i>
                        新規予約
                    </button> 
                    <button class="btn btn-warning"  @click="exportReceptionList()">
                        <i class="fa fa-print"></i>
                        健診簿出力
                    </button> 
                  </div>
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
<div class="card collapse " id="filter">
  <div class="card-body  bg-lightblue">
    <h5 class="card-title">受診者の絞込</h5>
    <div class="col-12">
      <div class="form-group row">
        <div class="col-2 col-form-label"><label class="control-label">受診状況</label></div>
          
          <div class="col-4">
            <select v-model="search_data.status" name="status" class="form-control">
            <!-- <option value="">--受診状況を選択--</option> -->
            <option value="0">未受診</option>
            <option value="1">受付済み</option>
            </select>          

          </div>
      </div>  
  <div class="form-row">
    <div class="col-4">
  <div class="form-group row">
    <label for="first_no" class="col-sm-6 col-form-label">通番</label>
    <div class="col-sm-6">
      <input v-model="search_data.first_no"  type="text" class="form-control" id="first_no" placeholder="通番はじめ">
    </div>
    </div>
    </div>
    <div class="col-4">
    <div class="form-group row">
    <label for="last_no" class="col-2 col-form-label">　～</label>
    <div class="col-sm-6">
      <input v-model="search_data.last_no"  type="text" class="form-control" id="last_no" placeholder="通番おわり">
    </div>
    </div>

  </div>  
  </div>    

      </div>
    
  </div>
  <div class="card-footer">
      <a href.prevent="#" class="btn btn-outline-secondary" @click="search">絞り込む</a>
    </div>
</div><!-- /.filter -->

                <table class="table table-hover table-bordered progress-table text-center">
                  <thead>
                    <tr class="bg-primary">
                    <th scope="col"  style="min-width: 80px;">予約編集</th>                        
                    <th scope="col"  style="min-width: 150px;">受付日時</th>
                    <th scope="col"  style="min-width: 60px;">通番</th>
                    <th scope="col"  style="min-width: 90px;">在籍番号</th>
                    <th scope="col"  style="min-width: 150px;">受診日</th>
                    <th scope="col"  style="min-width: 150px;">フリガナ</th>
                    <th scope="col"  style="min-width: 150px;">生年月日</th>
                    <th scope="col"  style="min-width: 90px;">性別</th>
                    <th scope="col"  style="min-width: 150px;">所属</th>
                  <!-- <th v-for="(column,idx) in columns" :key="idx" scope="col"  style="min-width: 150px;">
                    {{ column }}
                  </th>  -->
                    </tr>                 
                  </thead>
                  <tbody>
                  <tr v-for="reserve in reserve_infos.data" :key="reserve.id">
                    <td>
                      <div class="btn-group">
                        <a href="javascript:" @click.prevent="editModal(reserve)" class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i></a>   
                        <a href="javascript:" @click.prevent="deleteReserve(reserve.id)" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash"></i></a>                                               

                      </div>
                    </td>                      
                    <td>{{ reserve.checkup_date | myDate }}</td>
                    <td>{{ reserve.serial_number }}</td>
                    <td>{{ reserve.account.id_number }}</td>
                    <td>{{ reserve.schedule_date | myDate }}</td>
                    <td>{{ reserve.account.kana }}</td>
                    <td>{{ reserve.account.birthdate }}</td>
                    <td>{{ reserve.account.sex }}</td>
                    <td>{{ reserve.account.department }}</td>

                    <!-- <td v-for="(itemData,itemName) in reserve.select_item" :key="itemName.id" :name="itemName">
                      <span v-if="itemData == 1" class="text-danger">●</span>
                      <span v-if="itemData == 2" class="text-success">✔</span>
                      <span v-if="itemData == 3" class="text-warning">△</span>
                    </td> -->
                  </tr>
                    <div class="overlay"  v-show="loading">
                      <i class="fa fa-refresh fa-spin text-secondary"></i>
                    </div>                  
                </tbody></table>
                <!-- page control -->
                <pagination :data="reserve_infos" @pagination-change-page="search"></pagination>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

<!-- Modal -->
<div class="modal fade" id="editReserve" tabindex="-1" role="dialog" aria-labelledby="editReserveLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-header" :class="{'bg-primary':editmode,'bg-success':!editmode}">
        <h5 v-show="editmode" class="modal-title" id="editReserveLabel">予約情報の更新 </h5>
        <h5 v-show="!editmode" class="modal-title" id="editReserveLabel">予約情報の新規作成 </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="editmode ? updateReserve() : createReserve()"  @keydown.enter.prevent>
      <div class="modal-body">

      <div class="container">
        <div class="row">
                <div class="alert alert-info" v-show="alert.confirm_message">
                  <h5><i class="icon fa fa-question"></i> 受診者情報確認</h5>
                  以下のレコードが検索されました。受診票の受診者と一致していれば「確定」を、
                  違う場合は新規登録をしてください。
                </div>
                <div class="alert alert-danger"  v-show="alert.no_match">
                  <h5><i class="icon fa fa-times"></i>該当データ無し！ </h5>
                  バーコードと一致するデータが登録されていません。受診者の新規登録を行ってください。
                </div>
        
        </div>
      </div>

      <div class="container">
        <div class="row">
              <div class="form-group offset-9 col-3 mb-3">

              <label>通番No</label>            
              <input v-model="form.serial_number" type="text" name="serial_number" @keyup.enter="moveToNext(1)" :ref="1"
                class="form-control" :class="{ 'is-invalid': form.errors.has('serial_number'),'bg-primary':editmode,'bg-success':!editmode }">
                <has-error :form="form" field="kana"></has-error>
              </div>

            <div class="clearfix"></div>

          <div class="col-6">
            <legend class="text-primary">〇個人情報</legend>       

          <div class="card">
            <div class="card-body">
            <div class="row">

            <div class="form-group col-6">
              <label>フリガナ</label>
              <input v-model="form.kana" type="text" name="kana" @keyup.enter="moveToNext(2)" :ref="2"
                class="form-control" :class="{ 'is-invalid': form.errors.has('kana') }">
              <has-error :form="form" field="kana"></has-error>
            </div>
            <div class="form-group col-6">
              <label>在籍番号</label>
              <input v-model="form.id_number" type="text" name="id_number" @keyup.enter="moveToNext(3)" :ref="3"
                class="form-control" :class="{ 'is-invalid': form.errors.has('id_number') }">
              <has-error :form="form" field="id_number"></has-error>
            </div>        
            <div class="form-group col-6">
              <label>氏名</label>
              <input v-model="form.name" type="text" name="name" @keyup.enter="moveToNext(4)" :ref="4"
                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
              <has-error :form="form" field="name"></has-error>
            </div>
            <div class="form-group col-6">
              <label>生年月日</label>
          <datepicker v-model="form.birthdate"  :highlighted="highlighted" input-class="form-control" name="birthdate" 
          :class="{ 'is-invalid': form.errors.has('birthdate') }" :bootstrap-styling="true" :typeable="true" :format="'yyyy-MM-dd'" :language="ja"></datepicker>
            <has-error :form="form" field="birthdate"></has-error>

              <!-- <input v-model="form.birthdate" type="text" name="birthdate"
                class="form-control" :class="{ 'is-invalid': form.errors.has('birthdate') }"> -->
            </div>

            <div class="form-group col-6">
              <label>性別</label>
              <select v-model="form.sex" name="sex" @keyup.enter="moveToNext(5)" :ref="5"
                class="form-control" :class="{ 'is-invalid': form.errors.has('sex') }">
              <!-- <option value="">--選択--</option> -->
              <option value="男">男</option>
              <option value="女">女</option>
              </select>
              <has-error :form="form" field="sex"></has-error>
            </div>
            <div class="form-group col-6">
              <label>所属</label>
              <input v-model="form.department" type="text" name="department" @keyup.enter="moveToNext(6)" :ref="6"
                class="form-control" :class="{ 'is-invalid': form.errors.has('department') }">
              <has-error :form="form" field="department"></has-error>
            </div>
            </div>
            <!-- row -->
            </div></div>
            <!-- card -->

          </div>
          <div class="col-6">
            <legend class="text-primary">〇予約情報</legend>
          <div class="card">
            <div class="card-body">
            <div class="row">

            <div class="form-group col-6">
              <label>コース名</label>
              <input v-model="form.course" type="text" name="course" @keyup.enter="moveToNext(7)" :ref="7"
                class="form-control" :class="{ 'is-invalid': form.errors.has('course') }">
              <has-error :form="form" field="course"></has-error>
            </div>

            <div class="form-group col-6">
              <label>協会けんぽ</label>
              <select v-model="form.kenpo" name="role_id" @keyup.enter="moveToNext(8)" :ref="8"
                class="form-control" :class="{ 'is-invalid': form.errors.has('kenpo') }">
              <!-- <option value="">--選択--</option> -->
              <option value="0">対象外</option>
              <option value="1">対象者</option>
              </select>
              <has-error :form="form" field="kenpo"></has-error>
            </div>

            <!-- <div class="form-group col-6">
              <label>協会けんぽ</label>
              <input v-model="form.kenpo" type="text" name="kenpo"
                class="form-control" :class="{ 'is-invalid': form.errors.has('kenpo') }">
              <has-error :form="form" field="kenpo"></has-error>
            </div> -->
            <div class="form-group col-12">
              <label>備考</label>
              <input v-model="form.notes" type="text" name="notes" @keyup.enter="moveToNext(9)" :ref="9"
                class="form-control" :class="{ 'is-invalid': form.errors.has('notes') }">
              <has-error :form="form" field="notes"></has-error>
            </div>
            </div> 
            <!-- row -->
            </div></div>
            <!-- card -->


          </div>

        </div>
        <!-- div.row -->
        </div>
        <!-- div.container -->

      <div class="container mt-3">
        <div class="row">

        <legend class="text-primary">〇検査項目&nbsp;&nbsp;<small>未実施のもの</small> </legend>

          <div class="card">
            <div class="card-body">
                    <div class="container">
                  <div class="row">

                  <template v-for="(name_jp, colName) in this.select_item_list.basic">
                    <div v-show="form[colName] != 2" class="form-group col-3" :key="colName">
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

                  <hr class="mt-2">
                  <template v-for="(group_items, grouping) in this.select_item_list.advance">
                    <div :key="grouping"  v-show="form[group_items[0].name] != 2" class="mt-3">
                    
                    <!-- 尿検項目 -->
                    <template  v-if="['urinary'].includes(grouping)">
                    
                      <div class="form-group form-check-inline">
                        <input v-model="form[group_items[0].name]" type="checkbox"  :name="group_items[0].name" class="form-check-input" true-value="1" false-value="0"
                        :class="{ 'is-invalid': form.errors.has(group_items[0].name) }" @click="toggleSelectbox(group_items[0].name,group_items[1].name)">
                        <label class="form-check-label" :for="group_items[0].name">{{ group_items[0].name_jp }}</label>
                        <has-error :form="form" :field="group_items[0].name"></has-error>                
                      </div>


                      <div  class="form-group form-check-inline">
                      <select v-model="form[group_items[1].name]" type="text" :name="group_items[1].name" :disabled="(form[group_items[0].name] != 1)? true: false"
                        class="form-control" :class="{ 'is-invalid': form.errors.has(group_items[1].name)}" :ref="group_items[1].name">
                      <option value=""></option>
                      <option v-for="optionName in group_items[1].options.split(',')"  :key="optionName" :value="optionName">{{ optionName }}</option>
                      </select>
                      <has-error :form="form" :field="group_items[1].name"></has-error>          
                      </div>

                    
                  </template>
                  <!-- 採血項目 -->
                    <template  v-else-if="['blood'].includes(grouping)">
                    
                      <div class="form-group form-check-inline">
                        <input v-model="form[group_items[0].name]" type="checkbox"  :name="group_items[0].name" class="form-check-input" true-value="1" false-value="0"
                        :class="{ 'is-invalid': form.errors.has(group_items[0].name) }" @click="toggleSelectbox(group_items[0].name,group_items[1].name)">
                        <label class="form-check-label" :for="group_items[0].name">{{ group_items[0].name_jp }}</label>
                        <has-error :form="form" :field="group_items[0].name"></has-error>                
                      </div>


                      <div  class="form-group form-check-inline">
                        <v-select multiple v-model="vselected" :options="addParens(group_items[1].options.split(','))"  :disabled="(form[group_items[0].name] != 1)? true: false"
                         :ref="group_items[1].name" :class="{ 'is-invalid': form.errors.has(group_items[1].name)}" :closeOnSelect="false" />
                          <has-error :form="form" :field="group_items[1].name"></has-error>          
                      </div>

                    
                  </template>

                  <!-- 尿代謝 -->
                  <template  v-else>

                      <div class="form-group">
                        <input v-model="form[group_items[0].name]" type="checkbox" @click="toggleMetaboliteChecks(group_items[0].name,group_items)" :name="group_items[0].name" class="form-input" true-value="1" false-value="0"
                        :class="{ 'is-invalid': form.errors.has(group_items[0].name) }">
                        <label class="form-check-label" :for="group_items[0].name">{{ group_items[0].name_jp }}</label>
                        <has-error :form="form" :field="group_items[0].name"></has-error>                
                      </div>

                    <div class="card card-warning">
                      <div class="card-header" role="tab" id="heading1">
                        <h5 class="mb-0">
                          <a data-toggle="collapse" class="text-body stretched-link text-decoration-none" href="#collapse1" aria-expanded="true" aria-controls="collapse1"> 尿代謝項目 </a>
                        </h5>
                      </div>
                      <div id="collapse1" class="collapse" :class="{show: checkMetaboliteVal(form[group_items[0].name])}" role="tabpanel" aria-labelledby="heading1">
                        <div class="card-body">
                          <div class="container">
                          <div class="row">
                          <template  v-for="(group_item, idx) in group_items">
                            <div :key="idx" v-if="idx != 0  && form[group_item.name] != 2" class="form-group col-4">
                              <input v-model="form[group_item.name]" type="checkbox"  :name="group_item.name" class="form-check-input" true-value="1" false-value="0"
                              :class="{ 'is-invalid': form.errors.has(group_item.name) }">
                              <label class="form-check-label" :for="group_item.name">{{ group_item.name_jp }}</label>
                              <has-error :form="form" :field="group_item.name"></has-error>                
                            </div>
                          </template>
                          </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </template>
                  </div>

                  </template>


            </div>
            <!-- card-body -->
          </div>
          <!-- card -->



        </div>
        <!-- div.row -->
        </div>
        <!-- div.container -->






        <!-- <div class="form-group">
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
        </div> -->




      </div>
      <!-- modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        <button v-show="editmode" type="submit" class="btn btn-primary">確定</button>
        <button v-show="!editmode" type="submit" class="btn btn-success">登録</button>
      </div>

    </form>

    </div>
  </div>
</div>




    </div>
</template>
<style scoped>
.form-check-inline {
    /* display: inline-flex;
    align-items: center; */
    padding-left: 0;
    margin-right: 2.75rem;
}
.btn-app {
    min-width: 100px;
    height: 60px;
    text-align: center;
    font-size: 12px;
}
</style>
<script>
import { cloneDeep } from 'lodash';
import moment from 'moment';
import {ja} from 'vuejs-datepicker/dist/locale';
import vSelect from 'vue-select';
import { isUndefined, isNull, isNumber } from 'util';

    export default {
      components: {
        Datepicker,
        vSelect
      },         
      data:function(){
        return {
          vselected:[],
          ja: ja,
          highlighted: {
            days: [6, 0], // 曜日を設定
          },            
          search_data:{
            search_key: '',
            status:0,
            first_no:'',
            last_no:''
          } ,  
            loading:false,  
            load_mode:'init',  //init or update

          editmode:false,
          form:new Form({
            id:'',
            account_id:'',
            reserve_info_id:'',
            reception_list_id:'',
            id_number:'',
            kana:'',
            name:'',
            birthdate:'',
            sex:'',
            department:'',
            serial_number:'',
            checkup_date:'',
            schedule_date:'',
            course:'',
            kenpo:0,
            notes:'',
          }),

          reserve_infos:{},
          columns:{},
          select_item_list:{},
          serial_numbers:{},
          current_number:1,
          barcode_ready:false,
          barcode_no:'',
          barcode_column:'',
          alert:{
            'confirm_message':false,
            'no_match':false
          },
          currentUrl:''          
        }
      },
      watch: {
        vselected: function(select_array){
          // console.log(val);
          this.form.blood_test_type = select_array.map(pattern => {
            return pattern.replace(/(^\[)|(\]$)/g, "").trim();
          }).join('+');          
          // this.form.blood_test_type = select_array.join('+');
        },      
        
      },
      methods: {
        moveToNext:function(input_idx){
          if(!isUndefined(this.$refs[input_idx+1])){
            this.$refs[input_idx+1].focus();//[0]

          }
          // else{
          //   this.$refs.update.focus();

          // }
        },        
        setVselect:function(){
          
          if(this.checkBlank(this.form.blood_test_type)){
            this.vselected = [];
          }else{
            let blood_patterns = this.form.blood_test_type.split('+');
            this.vselected = this.addParens(blood_patterns);
   
            // this.vselected = blood_patterns;
          }
        },
        addParens:function(blood_patterns){
          return blood_patterns.map(pattern => {
            return "[ " + pattern + " ]";
          }); 
        },
        toggleMetaboliteChecks:function(check_name,group_items){
          let value = this.form[check_name];
          if(value == 1){
            for(const item of group_items){
              if(item.name != check_name){
                this.$set(this.form,item.name,0);

              }
            }
          }          

          this.mutateForm();
        },
        toggleSelectbox:function(check_name,select_name){
          let value = this.form[check_name];
          if(value == 1){
            this.$set(this.form,select_name,"");
          }
          this.mutateForm();

          if(check_name == 'blood_test'){
            this.vselected = [];
          }
        },
        resetAlert:function(){
          let cloned = cloneDeep(this.alert);
          cloned.confirm_message = false;
          cloned.no_match = false;
          this.alert = cloned;            
        },
        readBarcode:function(){
          this.barcodeSearch(1);                                

        },

        barcodeSearch:function(page = 1){
          this.resetAlert();
          this.loading = true;
          
          axios.get('api/reserve/'+this.barcode_no,{
            params: {
              // ここにクエリパラメータを指定する
              'page': page,
              // 'search_key':this.search_data.search_key,
              // 'status':this.search_data.status,
              // 'first_no':this.search_data.first_no,
              // 'last_no':this.search_data.last_no
            }
          })
          .then(({ data }) => {
            this.serial_numbers = data.serial_numbers;
            this.setCurrentNumber(); 

            if(data.reserveInfos == false || Object.keys(data.reserveInfos.data).length == 0){
              // this.alert = Object.assign({},this.alert, {'no_match':true });
              this.$set(this.alert,'no_match',true);
              this.newModal(false);
            } else{                  
              this.reserve_infos = data.reserveInfos;
              if(Object.keys(data.reserveInfos.data).length == 1){
                // this.alert = Object.assign({},this.alert, {'confirm_message':true });
                this.$set(this.alert,'confirm_message',true);                
                this.editModal(data.reserveInfos.data[0],false);
              }


            }
            
          })
          .finally(() => this.loading = false); 
        },
        setFormSerialNumber:function(){
          if(this.checkBlank(this.form.serial_number)){
            this.form.serial_number = this.current_number;
          }
        },
        checkBlank:function(value){
          if(value === '' || value == null){
            return true;
          }else{
            return false;
          }
        },        
        setCurrentNumber:function(){
          if(this.serial_numbers.last_serial_number == 0){
            this.current_number = this.serial_numbers.first_serial_number;
            this.serial_numbers.last_serial_number = this.current_number;
            this.serial_numbers.max_serial_number = this.current_number;
          }else{
            this.current_number = this.serial_numbers.max_serial_number + 1;
          }
        },
        formatDate:function(dateObj){
          if(this.checkBlank(dateObj)){
            return dateObj;

          }else{
              return moment(dateObj).format('YYYY-MM-DD HH:mm:ss');
          }
        },
        checkMetaboliteVal:function(value){
          if(value == 1){
            return true;
          }else{
            return false;
          }
        },
        mutateForm:function(){
          let clonedForm = cloneDeep(this.form);
          this.form = clonedForm;          
        },
        editModal:function(reserve,no_alert = true){
          if(no_alert == true){
            this.resetAlert();
          }
          this.form.reset();
          this.form.errors.clear();
          this.editmode = true;          
          $('#editReserve').modal('show');
          let fillData = Object.assign({},reserve.account,reserve.select_item,reserve);
          this.form.fill(fillData);
          this.setFormSerialNumber();
          this.setVselect();          
        },
        newModal:function(no_alert = true){
          if(no_alert == true){
            this.resetAlert();
          }

          this.form.reset();
          this.form.errors.clear();          
          this.editmode = false;          
          $('#editReserve').modal('show');
          this.setFormSerialNumber();
          this.setVselect();             
        },

        exportReceptionList:function(){
            const url = this.currentUrl+'medcheck/public/printer/exportReceptionList';

            let params =  {
              'search_key':this.search_data.search_key,
              'status':this.search_data.status,
              'first_no':this.search_data.first_no,
              'last_no':this.search_data.last_no
              };

              const searchParams = jQuery.param(params);
              window.location.href = url + "?" + searchParams;

        },
          search:function(page = 1){
            this.load_mode = "update";
            this.loadReserveTable(page);
          },
          initTable:function(){
            this.load_mode = "init";
            this.loadReserveTable();           
          },        
        loadReserveTable:function(page = 1){
          this.loading = true;
          axios.get('api/reserve',{
            params: {
              // ここにクエリパラメータを指定する
              'page': page,
              'search_key':this.search_data.search_key,
              'status':this.search_data.status,
              'first_no':this.search_data.first_no,
              'last_no':this.search_data.last_no
            }
          })
          .then(({ data }) => { 
                this.reserve_infos = data.reserveInfos;
                this.serial_numbers = data.serial_numbers;
                this.setCurrentNumber();

                if(this.load_mode == 'init'){
                    this.select_item_list = data.select_item_list;
                    this.createForm();                    
                }              
            
            })
            .finally(() => this.loading = false);             
        },

          createForm:function(){
            Object.keys(this.select_item_list.basic).forEach(key => {
              this.columns[key] = this.select_item_list.basic[key];
              this.form[key] = '';
            });
            var list = this.getAdvanceItemList();
            Object.keys(list).forEach(key => {
              this.form[key] = '';
              this.columns[key] = list[key];              
            });

          },
          getAdvanceItemList:function(){
            var list = {};
            Object.keys(this.select_item_list.advance).forEach(key => {
                Object.keys(this.select_item_list.advance[key]).forEach(subkey => {
                    let item_data = this.select_item_list.advance[key][subkey];
                    list[item_data.name] = item_data.name_jp;
                });               
            });              
            return list;
          },

        updateReserve:function(){
          this.$Progress.start();

          this.form.birthdate = this.formatDate(this.form.birthdate);
          this.form['select_list'] = Object.keys(this.columns) ;          
          // Submit the form via a PUT request
          this.form.put('api/reserve/'+this.form.id)
          .then(({ data })=>{
              console.log(data);

              this.serial_number = data.next_number;

              this.$Progress.finish(); 
              Toast.fire({
                  type: 'success',
                  title: data.message
                });
                  this.search(this.reserve_infos.current_page);                
                $('#editReserve').modal('hide');

          })
          .catch((error)=>{
            console.log(error);
            this.$Progress.fail();
              if(Object.keys(error.response).length > 0){
                var message = error.response.data.message;
              }else{
                var message = "異常エラー：システムでの保存処理に異常あり！";
              }
            Toast.fire({
            type: 'error',
            title: message
            });                         
          });
        },
        createReserve:function(){
          this.$Progress.start();

          this.form.birthdate = this.formatDate(this.form.birthdate);
          this.form['select_list'] = Object.keys(this.columns) ;          
          // Submit the form via a POST request
          this.form.post('api/reserve')
            .then(({ data }) => { 
              console.log(data);

              this.serial_number = data.next_number;

              this.$Progress.finish(); 
              Toast.fire({
                  type: 'success',
                  title: data.message
                });
                  this.search(this.reserve_infos.current_page);                
                $('#editReserve').modal('hide');

              })
            .catch(error => {
                console.log(error);
                this.$Progress.fail();
              if(Object.keys(error.response).length > 0){
                var message = error.response.data.message;
              }else{
                var message = "異常エラー：システムでの保存処理に異常あり！";
              }
                Toast.fire({
                type: 'error',
                title: message
                });             
            });        
        },
        deleteReserve(id){
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
                this.$Progress.start();
                this.form.delete("api/reserve/"+id)
                .then(({ data }) => { 
                  if(data.next_number != false){
                    this.serial_number = data.next_number;
                }
                this.$Progress.finish(); 
                Swal.fire(
                  '削除完了!',
                  data.message,
                  'success'
                );    
                this.search(this.reserve_infos.current_page);                      
              })
              .catch(error => {
                this.$Progress.fail();                
                console.log(error);
                var message = "予約レコードの削除処理に失敗しました。";

                Toast.fire({
                type: 'error',
                title: message
                });  
              });              


            }
          });

        },        
        connectChannel() {
            ///重要！！！ medchecker_database_のprefixをチャネル名に付ける事！！
            Echo.channel("medchecker_database_checkup-data-updated").listen("CheckupDataUpdated", e => {
              if(e.category == 'reserve'){
                  this.search(this.reserve_infos.current_page);
              }                
            });
        }
      },  
      mounted() {
        this.initTable();
        console.log('Component mounted.');

        this.currentUrl = window.location.protocol + "//" + window.location.host + "/";

        this.connectChannel(); 

        $("#editReserve").on("hidden.bs.modal", ()=>{
          this.$nextTick(() => {
                this.$refs.barcode.focus();
            })            
        });   
        this.$refs.barcode.focus();  
        
      }
    }
</script>