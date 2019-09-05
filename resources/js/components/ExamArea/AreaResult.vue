<template>
    <div class="container">

<div class="row mt-5">
          <div class="col-md-12">
            
            <h2>検査対象者リスト</h2>
<div class="row col-4 mt-3">
<div class="info-box">
  <!-- Apply any bg-* class to to the icon to color it -->
  <span class="info-box-icon bg-info">
    <img :src="area_image_path" alt="" sizes="" srcset="">
  </span>
  <div class="info-box-content" v-show="!initLoading">
    <h4 class="info-box-text" >{{ area.name }}エリア</h4>
    <span class="info-box-number">{{ area.count }} 名</span>
  </div>
  <!-- /.info-box-content -->
</div>
<!-- /.info-box -->
</div>
            <div class="card mt-1 card-outline card-success">
              <div class="card-header">
                 <h3 class="card-title"></h3>
                <div class="card-tools">
                </div>

                <div class="float-left">
                  <div class="row">
                    <div class="col-4">
                      <div class="input-group input-group-sm"> 
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon2">No </span>
                        </div>                                     
                        <input type="text" @keyup.enter="search(1)" v-model="search_data.serial_number" name="serial_number" class="form-control float-right" placeholder="通番">
                      </div> 
                  </div>
                  <div class="col-6">
                      <div class="input-group input-group-sm">                  
                        <input type="text" @keyup.enter="search(1)" v-model="search_data.search_key" name="search_key" class="form-control float-right" placeholder="Search">

                        <div class="input-group-append">
                          <button type="submit" @click="search(1)" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="input-group-append">
                            <a href="#filter" data-toggle="collapse">&nbsp;<i class="fa fa-bars text-secondary h3" aria-hidden="true"></i>
                            </a>                      
                        </div>  
                      </div>
                    </div>                  
                  </div>           


                </div>

              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">

<!-- .filter -->
<div class="card collapse" id="filter">
  <div class="card-body bg-lightgreen">
    <h5 class="card-title">受診者の絞込</h5>
    <div class="col-12">

      <div class="form-group row">
        <div class="col-2 col-form-label"><label class="control-label">受診状況</label></div>
          
          <div class="col-4">
            <select v-model="search_data.status" name="status" class="form-control">
            <option value="">--受診状況を選択--</option>
            <option value="0">未実施・一部実施</option>
            <option value="1">実施済み</option>
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
      <a href.prevent="#" class="btn btn-outline-secondary" @click="search(1)">絞り込む</a>
    </div>
</div><!-- /.filter -->


                <table class="table table-hover table-bordered progress-table text-center">
                  <thead>
                    <tr class="bg-success">
                    <th scope="col"  style="min-width: 80px;">結果入力</th>
                    <th scope="col"  style="min-width: 80px;">状況</th>
                    <th scope="col"  style="min-width: 60px;">通番</th>
                    <th scope="col"  style="min-width: 150px;">フリガナ</th>
                    <th scope="col"  style="min-width: 150px;">生年月日</th>
                    <th scope="col"  style="min-width: 60px;">性別</th>
                  <th v-for="(value, key, index) in columns" :key="index" scope="col"  style="min-width: 150px;">
                    {{ value }}
                  </th> 
                    </tr>                 
                  </thead>
                  <tbody>
                    <template v-for="reserve in reserve_infos.data">

                  <tr  :key="reserve.id">
                    <td>
                        <a href="javascript:" @click.prevent="editModal(reserve)" class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i></a>                      
                    </td>
                    <td>
                      <span :class="reserve.progress == '実施済み' ?  'bg-success': 'bg-danger'">{{ reserve.progress }}</span></td>
                    <td>{{ reserve.serial_number }}</td>
                    <td>{{ reserve.account.kana }}</td>
                    <td>{{ reserve.account.birthdate }}</td>
                    <td>{{ reserve.account.sex }}</td>


                    <template v-for="(value, colName) in columns">
                        <td  :key="colName" v-if="whichmodel(colName) == 'exam_result'">{{ displayResult(reserve.exam_result[colName],colName) }}</td>
                        <td  :key="colName" v-else-if="whichmodel(colName) == 'select_item'">
                      <span v-if="reserve.select_item[colName] == 1" class="text-danger">●</span>
                      <span v-else-if="reserve.select_item[colName] == 2" class="text-success">✔</span>
                      <span v-else></span></td>
                    </template>

                  </tr>
                  </template>
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
<div class="modal fade" id="editResult" tabindex="-1" role="dialog" aria-labelledby="editResultLabel" aria-hidden="true" ref="vuemodal">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editResultLabel">検査結果の入力&nbsp;&nbsp;&nbsp;&nbsp;
          <button @click.prevent="checkAll()" v-show="has_checkbox" class="btn btn-success btn-sm">全て ✔</button>
          <button @click.prevent="selectAll()" v-show="has_selectbox" class="btn btn-success btn-sm">全て 正常</button>

        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="updateResult()" @keydown.enter.prevent>
      <div class="modal-body">
        <fieldset class="col offset-1 col-10">
      <input type="hidden" :value="form['reserve_info_id']">
      <template v-for="(name_jp, colName,idx) in form_items">
        <div :key="colName" class="mt-4">
          <div  class="form-group" v-if="whichmodel(colName,'form') == 'exam_result_input'">
          <label>{{ name_jp }}  </label><button @click.prevent="setDefault(colName)" v-show="checkDefault(colName)" class="float-right btn btn-sm btn-info">規定値を入力</button>

          <div class="input-group mb-3">
            <div class="input-group-prepend" v-show="getResultInfo(colName,'unit')">
              <span class="input-group-text" id="basic-addon2">{{ getResultInfo(colName,'unit') }}</span>
            </div>
              <input  v-model="form[colName]" type="text" :name="colName" @blur="onInputChange(colName,'text')" @keyup.enter="moveToNext(idx)" :ref="idx"
                class="form-control" :class="getInputClass(colName) " autocomplete="off" :list="(colName == 'findings_chestabdomen')? 'finding_list': ''"> 
                <datalist id="finding_list" v-show="colName == 'findings_chestabdomen'">
                <option value="所見なし" />
                <option value="所見あり（　　B）" />
                <option value="所見あり（　　C）" />
                <option value="所見あり（　　D2）" />
                <option value="所見あり（　　D1）" />
                <option value="所見あり（　　E）" />
                </datalist> 
              <has-error :form="form" :field="colName"></has-error>            
          </div>

          </div>
          <div  class="form-group" v-else-if="whichmodel(colName,'form') == 'exam_result_select'">
          <label>{{ name_jp }}</label>
          <select v-model="form[colName]" type="text" :name="colName" @blur="onInputChange(colName,'select')"
            class="form-control" :class="getInputClass(colName) " >
          <option value=""></option>
          <option v-for="optionName in getResultInfo(colName,'options').split(',')"  :key="optionName" :value="optionName">{{ optionName }}</option>
          </select>
          <has-error :form="form" :field="colName"></has-error>          
          </div>

          <div class="form-group form-check-inline"  v-else-if="whichmodel(colName) == 'select_item'">
            <input v-model="form[colName]" type="checkbox"  :name="colName" class="form-check-input" true-value="2" false-value="1"
            :class="{ 'is-invalid': form.errors.has(colName) }" :id="colName" :ref="colName">
            <label class="form-check-label" for="colName">{{ name_jp }}
            </label>
          <has-error :form="form" :field="colName"></has-error>
          </div>
 <!-- v-show="colName == 'urinary_metabolites'" -->
          <!-- <div  class="form-group" :key="colName" v-else-if="whichmodel(colName) == 'select_item'">
          <label>{{ name_jp }}</label>
          <select v-model="form[colName]" type="text" :name="colName"
            class="form-control" :class="{ 'is-invalid': form.errors.has(colName) }">
          <option value="">--実施したかチェック--</option>
          <option value="1">未実施</option>
          <option value="2">実施済み</option>
          </select>
          <has-error :form="form" :field="colName"></has-error> -->
        <!-- </div> -->

              <div class="callout callout-warning"  v-show="colName == 'urinary_metabolites'"> 
                <ul>
                  <li v-for="(meta_item,idx) in metabolite_list[form['reserve_info_id']]" :key="idx">{{ meta_item }}</li>
                </ul>
              </div> 
        </div>                      
      </template>
        </fieldset>
             
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        <button type="submit" class="btn btn-primary" ref="update" @keyup.enter="updateResult()">更新</button>
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
.reserved-item{
    background-color: #fdf2db ;
    border-color:#e27c13;
}

 .item-filled{
    background-color: #e3fddb ;
    border-color:#1fa319;
}
</style>

<script>
import { isUndefined, isNull, isNumber } from 'util';
import { cloneDeep } from 'lodash';

    export default {
        data() {
            return {
                exam_area_id:this.$route.params.id,
                search_data:{
                    serial_number:'',
                    search_key: '',
                    status:'',
                    first_no:'',
                    last_no:''
                } , 
                form:new Form({
                  reserve_info_id:'',
                  serial_number:'',
                  result_list: [],
                  select_list:[]                   
                }),
                range:{
                  sex:'',
                  lowerlimit:'',
                  upperlimit:'', 
                  show_alert:false,               
                }, 
                has_checkbox:false,
                has_selectbox:false,
                loading:false,  
                load_mode:'init',  //init or update
                initLoading:true,    
                reserve_infos:{},
                columns:{}, 
                result_list:[],
                select_list:[],
                form_items:{},               
                item_list:{},                
                result_infos:{},
                this_result_info:{},
                metabolites:{},
                metabolite_list:{},
                area:{},
                area_image_path:'',
                currentUrl:'',               
            }
        },

        methods: {
            displayResult:function(data,colName){
              if(this.isNumeric(data)){
                return this.numberFormat(data,this.getResultInfo(colName,'num_decimal_places'));
              }else{
                return data;
              }
            },
            setDefault:function(colName,reserve){
              let result_info = this.getResultInfo(colName,'default_val');
                let clonedForm = cloneDeep(this.form);
                
             
              if(this.isNumeric(result_info)){
                clonedForm[colName] = result_info;
              }else{
                clonedForm[colName] = this.form[result_info];              
              }
              this.form = clonedForm;               
            },
            checkDefault:function(colName){
              let result_info = this.getResultInfo(colName,'default_val');
              if(this.checkBlank(result_info)){
                return false;
              }else{
                return true;
              }
            },
            getImagePath:function(area_id){
              axios.get("/medcheck/public/api/exam_area/image_path/" + area_id).then(({ data }) => {
                this.area_image_path = this.currentUrl+'medcheck/public/img/' + data;
              });
            },
            setImagePath:function(area_id){
              this.area_image_path = '';

                this.getImagePath(area_id);

            },

          getInputClass:function(colName){
            var is_blank = this.checkBlank(this.form[colName]);
            return {
              'is-invalid': this.form.errors.has(colName),
              'reserved-item':is_blank,
              'item-filled': !is_blank

            };
          },
          checkBlank:function(value){
            if(value === '' || value == null){
              return true;
            }else{
              return false;
            }
          },
            moveToNext:function(input_idx){
              if(!isUndefined(this.$refs[input_idx+1]) && !isUndefined(this.$refs[input_idx+1][0])){
                this.$refs[input_idx+1][0].focus();

              }else{
                this.$refs.update.focus();

              }
            },
            checkNormalRange:function(colName,value){
              if(this.range.sex == '男'){
                var lowerlimitname = 'm_lower_limit';
                var upperlimitname = 'm_upper_limit';
              }else{
                var lowerlimitname = 'fm_lower_limit';
                var upperlimitname = 'fm_upper_limit';
              }


              this.range.lowerlimit =  this.getResultInfo(colName,lowerlimitname);
              this.range.upperlimit = this.getResultInfo(colName,upperlimitname);
              if(this.range.lowerlimit || this.range.upperlimit){
                let is_out_of_range = this.compareLimit(Number(value));
  
                if(is_out_of_range){
                  this.range.show_alert = true;
                  Toast.fire({
                      position: 'top',
                      animation: false,
                      timer: 5000,                      
                      // showConfirmButton: true,                    
                      type: 'warning',
                      title: this.form_items[colName] + 'の入力値が正常範囲外です (正常範囲: ' + this.range.lowerlimit + ' ～ ' + this.range.upperlimit + ')'
                    });

                }

              }


            },
            compareLimit:function(value){
              if(this.isNumeric(this.range.upperlimit)){
                if(value > Number(this.range.upperlimit)){
                  return true;
                }
              }
              if(this.isNumeric(this.range.lowerlimit)){
                if(value < Number(this.range.lowerlimit)){
                  return true;
                }
              }
              return false;
            },
            setSex:function(reserve){
              this.range.sex = reserve.account.sex;
            },
            selectAll:function(){
              var clonedForm = cloneDeep(this.form);
              var changed = false;
              var defaultVal = null;

            

              Object.keys(this.form_items).forEach(item_name => {
                if(defaultVal == null){
                  let category = this.getResultInfo(item_name,'options');
                  if(!this.checkBlank(category)){
                    defaultVal = category.split(',')[0];
                  }else{
                    defaultVal = 1;
                  }
                }
                if(this.whichmodel(item_name,'form') == 'exam_result_select'){
                  clonedForm[item_name] = defaultVal;
                  changed = true;
                }
              });

              if(changed){
                this.form = clonedForm;
              }
            },
            checkAll:function(){
              var clonedForm = cloneDeep(this.form);
              var changed = false;
              Object.keys(this.form_items).some(select_name => {
                if(this.select_list.includes(select_name)){
                  clonedForm[select_name] = 2
                  changed = true;
                }
              });

              if(changed){
                this.form = clonedForm;
              }
            },
            onInputChange:function(colName,type){
              if(type == 'select'){
                let clonedForm = cloneDeep(this.form);
                this.form = clonedForm;
              }else if(type == 'text'){
                this.formatDecimal(colName);
              }
            },
            numberFormat:function(number,decimals){
              return Number(Math.round(number +'e'+ decimals) +'e-'+ decimals).toFixed(decimals);
            },
            formatDecimal:function(colName){
              var decimals = this.getResultInfo(colName,'num_decimal_places');
              if(this.isNumeric(decimals) && this.isNumeric(this.form[colName])){
                let clonedForm = cloneDeep(this.form);
                clonedForm[colName] = this.numberFormat(this.form[colName],decimals);//Number(Math.round(this.form[colName] +'e'+ decimals) +'e-'+ decimals).toFixed(decimals);
                this.form = clonedForm;
                this.checkNormalRange(colName,this.form[colName]);

              }else if(this.checkBlank(this.form[colName])){
                let clonedForm = cloneDeep(this.form);
                this.form = clonedForm;                
              }

            },
            isNumeric:function (num){
              return !isNaN(parseFloat(num));
            },                      
            getResultInfo(itemname,propname){
              var result_info = {};
              Object.keys(this.result_infos).some(select_itemname => {

                  Object.keys(this.result_infos[select_itemname]).some(result_itemname => {
                    if(itemname == result_itemname){
                      result_info = this.result_infos[select_itemname][result_itemname];
                      return true;
                    }

                  });     
         
              });  

              let propdata =result_info[propname];
              if(propdata != null){
                return propdata;
              }else{
                return '';
              }
            },
            setMetabolitesList:function(){
              this.metabolite_list = {};
              for(const reserve of this.reserve_infos.data){
              var list_item = [];
                if(reserve.select_item.hasOwnProperty('urinary_metabolites')){
                  Object.keys(reserve.select_item).forEach(key => {
                    if(this.metabolites.hasOwnProperty(key) && reserve.select_item[key] > 0){
                      list_item.push(this.metabolites[key]);
                    }             
                  });               
                  this.metabolite_list[reserve.id] = list_item;
                }

              }
            },
            selectFormItems(reserve){
              var required_items = [];this.form_items = {};
              Object.keys(reserve.select_item).forEach(key => {
                if(key != 'id' && key != 'reserve_info_id' && reserve.select_item[key] > 0){
                  required_items.push(key);
                }             
              }); 

              Object.keys(this.result_infos).forEach(select_itemname => {
                if(required_items.includes(select_itemname)){

                  //尿検の場合はurinary_test_typeの項目のみ選択
                  if(select_itemname == 'urinary_test'){
                    var urinary_items = reserve.select_item['urinary_test_type'];
                    for(const urinary_item of urinary_items){
                      Vue.set(this.form_items,urinary_item,this.result_infos[select_itemname][urinary_item].name_jp);
                    }

                  }else{
                    Object.keys(this.result_infos[select_itemname]).forEach(result_itemname => {
                      Vue.set(this.form_items,result_itemname,this.result_infos[select_itemname][result_itemname].name_jp);
                    });     

                    //結果入力欄のみ選択
                    required_items = required_items.filter(item => item !== select_itemname);             
                  }
                }             
              });


              Object.keys(this.columns).forEach(key => {
                if(required_items.includes(key)){
                  Vue.set(this.form_items,key,this.columns[key]);
                }             
              });              
              
            },
            // getAreaImage:function(area_id){
            //   this.setImagePath();
            // },
            whichmodel:function(colName,mode){
              if(this.result_list.includes(colName)){
                if(mode == 'form'){
                  let result_info = this.getResultInfo(colName,'options');
                  if(result_info == '' || result_info == null){
                    return 'exam_result_input';
                  }else{
                    this.has_selectbox = true;
                    return 'exam_result_select';
                  }

                }else{
                  return 'exam_result';
                }
              }else if(this.select_list.includes(colName)){
                    this.has_checkbox = true;
                    return 'select_item';
              }else{
                  return "";
              }

            },  
            formatCheck:function(value){  
              switch (value) {
                case 1:
                  return "●";
                  break;
                case 2:
                  return "✔";
                  break;
              
                default:
                  return "";
                  break;
              }

            } ,       
          search:function(page = 1){
            this.load_mode = "update";
            this.loadAreaResultTable(page);
          },
          initTable:function(){
            this.initLoading = true;
            this.load_mode = "init";
            this.loadAreaResultTable();
            
          },
          loadAreaResultTable:function(page = 1){
            this.loading = true;

            let url = '/medcheck/public/api/exam_area/'+this.exam_area_id + '/' + this.load_mode;


            axios.get(url,{
              params: {
                // ここにクエリパラメータを指定する
                'page': page,
                'serial_number':this.search_data.serial_number,
                'search_key':this.search_data.search_key,
                'status':this.search_data.status,
                'first_no':this.search_data.first_no,
                'last_no':this.search_data.last_no
              }
            })
            .then(({ data }) => { 
              this.reserve_infos = data.reserveInfos;
              this.area = data.area;
              if(this.load_mode == 'init'){
                this.item_list = data.item_list;
                this.result_infos = data.result_infos;
                this.metabolites = data.metabolites;

                this.columns = {};//
                this.result_list=[];
                this.select_list=[];

                Object.keys(data.result_infos).forEach(select_itemname => {
                  Object.keys(data.result_infos[select_itemname]).forEach(result_itemname => {
                    this.columns[result_itemname] = data.result_infos[select_itemname][result_itemname].name_jp;
                    this.result_list.push(result_itemname);                  
                  
                  });
                });                
                if(_.isEmpty(data.item_list) == false){
                  this.columns = Object.assign(this.columns, data.item_list);
                  this.select_list = Object.keys(data.item_list);
                }
 
                this.createForm();
              }
                this.setMetabolitesList();
              })
              .finally(() => {this.loading = false;this.initLoading = false;});             
          },

          // resetRefs:function(){
          //   Object.keys(this.$refs).forEach((ref_key)=>{
          //     if(this.$refs[ref_key].length == 0){
          //       delete this.$refs[ref_key];
          //     }
          //   });
          // },
          editModal:function(reserve){            
            this.form.reset();
            this.form.errors.clear();
            this.has_checkbox = false;
            this.has_selectbox = false;
            this.selectFormItems(reserve);
            this.updateForm();
            let fillData = Object.assign({}, reserve.select_item,reserve.exam_result);
            fillData['serial_number'] = reserve.serial_number;
            this.form.fill(fillData);
            this.setSex(reserve);
            this.formatFormData();
            // $(this.$refs.vuemodal).on("show.bs.modal", this.setInitialFocus());

            $('#editResult').modal('show');
          },  
          formatFormData:function(){
            Object.keys(this.form_items).forEach(key => {
              this.formatDecimal(key);
            });            
          },     
          updateForm:function(){
          
            Object.keys(this.columns).forEach(key => {
              if(this.form.hasOwnProperty(key)){
                delete this.form[key];
              }
            });
            Object.keys(this.form_items).forEach(key => {
              this.form[key] = '';
            });

          },
          createForm:function(){
            this.form = new Form({
                  reserve_info_id:'',
                  serial_number:'',
                  result_list: [],
                  select_list:[]                   
            });
          },
          updateResult:function(){
            this.$Progress.start();
            let url = '/medcheck/public/api/exam_area/'+this.form.reserve_info_id;          

            this.form['result_list'] = this.result_list;
            this.form['select_list'] = this.select_list;

            // Submit the form via a PUT request
            this.form.put(url)
            .then(({ data })=>{
                console.log(data);                
                this.$Progress.finish(); 
                Toast.fire({
                    type: 'success',
                    title: data.message
                  });
                  // alert(this.reserve_infos.current_page);
                  this.search(this.reserve_infos.current_page);
                  $('#editResult').modal('hide');
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
          connectChannel() {
              ///重要！！！ medcheck_database_のprefixをチャネル名に付ける事！！
              Echo.channel("medcheck_database_checkup-data-updated").listen("CheckupDataUpdated", e => {
                if(e.category == 'reserve' || e.category == 'result'){

                  this.search(this.reserve_infos.current_page);
                }                
              });
          }                     
        },

        beforeRouteUpdate (to, from, next) {
            next();
            // react to route changes...
            this.exam_area_id = this.$route.params.id
            this.initTable();   
            this.setImagePath(this.exam_area_id);         
            // don't forget to call next()
        },        
        mounted() {
          this.initTable();
          this.currentUrl = window.location.protocol + "//" + window.location.host + "/";          
            this.setImagePath(this.exam_area_id); 
            console.log('Component mounted.');
            // this.connectChannel();                      
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