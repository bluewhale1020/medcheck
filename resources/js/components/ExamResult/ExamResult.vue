<template>
    <div class="container">

<div class="row mt-5">
          <div class="col-md-12">
            
            <h2>受診者検査結果 <small class="badge badge-secondary">{{ reserve_infos.total }}名</small></h2>

            <div class="card mt-5  card-outline card-info">
              <div class="card-header">
                 <h3 class="card-title"></h3>
                <div class="card-tools">
                      <button class="btn btn-warning"  @click="exportResult()">
                          <i class="fa fa-print"></i>
                          結果データ出力
                      </button>
                </div>

                <div class="float-left">
                  <div class="input-group input-group-sm">                  
                    <input type="text" @keyup.enter="search" v-model="search_data.search_key" name="search_key" class="form-control float-right" placeholder="Search">

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
  <div class="card-body  bg-lightblue">
    <h5 class="card-title">受診者の絞込</h5>
    <div class="col-12">
      <div class="form-group row">
        <div class="col-2 col-form-label"><label class="control-label">受診日</label></div>
    <div class="col-4">
      <datepicker v-model="search_data.checkupdate"  :highlighted="highlighted" input-class="form-control"
       :clear-button="true" :bootstrap-styling="true" :typeable="true" :format="'yyyy-MM-dd'" id="checkupdate" :language="ja"></datepicker>
    </div>
      </div>

      <div class="form-group row">
        <div class="col-2 col-form-label"><label class="control-label">受診状況</label></div>
          
          <div class="col-4">
            <select v-model="search_data.status" name="status" class="form-control">
            <option value="">--受診状況を選択--</option>
            <option value="0">検査中</option>
            <option value="1">検査終了</option>
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
                    <tr class="bg-cyan">
                    <th scope="col"  style="min-width: 90px;">通番</th>
                    <th scope="col"  style="min-width: 150px;">在籍番号</th>
                    <th scope="col"  style="min-width: 150px;">受診日</th>
                    <th scope="col"  style="min-width: 150px;">フリガナ</th>
                    <th scope="col"  style="min-width: 150px;">生年月日</th>
                  <th v-for="(column,idx) in columns" :key="idx" scope="col"  style="min-width: 150px;">
                    {{ column }}
                  </th> 
                    </tr>                 
                  </thead>
                  <tbody>
                  <tr v-for="reserve in reserve_infos.data" :key="reserve.id">
                    <td>{{ reserve.serial_number }}</td>
                    <td>{{ reserve.account.id_number }}</td>
                    <td>{{ reserve.checkup_date | myDate }}</td>
                    <td>{{ reserve.account.kana }}</td>
                    <td>{{ reserve.account.birthdate }}</td>


                    <template v-for="(itemData,itemName) in reserve.exam_result">
                      <td v-if="not_skip_column(itemName)" :key="itemName" :name="itemName">
                        {{ displayResult(itemData,itemName) }}                        
                      </td>
                    </template>
                  </tr>
                    <div class="overlay"  v-show="loading">
                      <i class="fa fa-refresh fa-spin text-secondary"></i>
                    </div>                  
                </tbody></table>
                <!-- page control -->
                <pagination :data="reserve_infos" @pagination-change-page="loadResultTable"></pagination>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>



    </div>
</template>

<script>
import { isUndefined, isNull, isNumber } from 'util';
import moment from 'moment';
import {ja} from 'vuejs-datepicker/dist/locale';

    export default {
      components: {
        Datepicker
      },      
      data:function(){
        return {
          ja: ja,
          highlighted: {
            days: [6, 0], // 曜日を設定
          },         
          search_data:{
            search_key: '',
            checkupdate:'',
            status:'',
            first_no:'',
            last_no:''
          } ,  
          loading:false,
          load_mode:'init',        
          reserve_infos:{},
          decimal_places:{},          
          columns:{},
          currentUrl:''
        }
      },
      methods: {
        displayResult:function(data,colName){
          if(this.isNumeric(data) && this.decimal_places.hasOwnProperty(colName)){
            return this.numberFormat(data,this.decimal_places[colName]);
          }else{
            return data;
          }
        },  
        numberFormat:function(number,decimals){
          return Number(Math.round(number +'e'+ decimals) +'e-'+ decimals).toFixed(decimals);
        },    
        isNumeric:function (num){
          return !isNaN(parseFloat(num));
        },                                          
        exportResult:function(){
            const url = this.currentUrl+'medcheck/public/printer/exportExamResult';

            let params =  {
              'search_key':this.search_data.search_key,
              'checkupdate':this.formatDate(this.search_data.checkupdate),
              'status':this.search_data.status,
              'first_no':this.search_data.first_no,
              'last_no':this.search_data.last_no
              };

            // const searchParams = querystring.stringify(search);
            // if(this.search.dept.length > 0 || this.search.pref.length > 0){
                const searchParams = jQuery.param(params);
                window.location.href = url + "?" + searchParams;
            // }else{
            //     window.location.href = url;
            // }
        },
        search:function(){
          this.loadResultTable();
        },
        not_skip_column:function(itemName){
          if(['id','reserve_info_id','is_hungry','hours_after_meals'].includes(itemName)){
            return false;
          }else{
            return true;
          }
        },
        formatDate:function(dateObj){
          if(moment.isDate(dateObj)){

            return moment(dateObj).format('YYYY-MM-DD');
          }else{
            return '';
          }
        },
        loadResultTable:function(page = 1){
          this.loading = true;
          axios.get('api/exam_result',{
            params: {
              // ここにクエリパラメータを指定する
              'page': page,
              'search_key':this.search_data.search_key,
              'checkupdate':this.formatDate(this.search_data.checkupdate)                      ,
              'status':this.search_data.status,
              'first_no':this.search_data.first_no,
              'last_no':this.search_data.last_no
            }
          })
          .then(({ data }) => { 
              this.reserve_infos = data.reserveInfos;
              if(this.load_mode == 'init'){
                this.decimal_places = data.decimal_places; 
                this.load_mode = "update";          
              }              
            })
            .finally(() => this.loading = false);             
        },
        loadTableColumns:function(){
          axios.get("api/exam_result/columns")
          .then(({ data }) => { 
            this.columns = data;
            
            });          
        },
        connectChannel() {
            ///重要！！！ medcheck_database_のprefixをチャネル名に付ける事！！
            Echo.channel("medcheck_database_checkup-data-updated").listen("CheckupDataUpdated", e => {
              if(e.category == 'reserve' || e.category == 'result'){
                  this.search();
              }                
            });
        }
      },  
      async mounted() {
        await this.loadTableColumns();
        this.loadResultTable();
        console.log('Component mounted.');

        this.currentUrl = window.location.protocol + "//" + window.location.host + "/";
        this.connectChannel();  
      }
    }
</script>
<style scoped>
/* progress page */

.header-height{
    height: 100px !important;
    word-wrap: break-word;
}
</style>

