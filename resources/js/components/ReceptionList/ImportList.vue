<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
            
            <h2>健診簿のインポート</h2>

<div class="card card-info  mt-5">
              <div class="card-header">
                <h3 class="card-title">インポートフォーム</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form">
                <div class="card-body">

<div class="drop mx-auto text-center col-8" @dragleave.prevent @dragover.prevent @drop.prevent="selectedFile">
      <p>ファイルをここにドラッグ・ドロップ</p>
</div>
                    
      <div class="form-group row mt-3">
        <div class="col-2 col-form-label"><label class="control-label">健診簿を選択</label></div>
          
          <div class="col-8">
 <input id="receptionListFile" @change="selectedFile" type="file" style="display:none" ref="input_file">
<div class="input-group">
  <input type="text" v-model="fileName" id="display_input" class="form-control" @click="onChangeFile()" readonly>
  <span class="input-group-btn"><button type="button" class="btn btn-secondary" @click="onChangeFile()">選択</button></span>
</div>  

          </div>
      </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <div class="btn-group float-left mr-5">
                        <button type="submit" @click.prevent="importList()" class="btn btn-primary">ファイルをインポートする</button>

                    </div>
                    <div class="mr-3" v-show="loading">
                        <div class="progress" style="height: 30px;"><div class="progress-bar bg-success" :style="{width: progress_rate + '%'}">{{progress_rate}}%</div></div>

                    </div>
               
                </div>
              </form>
            </div>
          
            </div>


<div class="col-12">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title">インポートデータ</h3>
<ul class="nav nav-pills ml-auto p-2">
                  <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">一覧</a></li>
                  <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">結果</a></li>
                </ul>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
<div class="tab-content">
                  <div class="tab-pane active" id="tab_1">

                    <section class="section" v-if="showTable">
                    <table class="table table-hover table-bordered import-table text-center">
                    <thead>
                        <tr class="bg-success">
                            <th scope="col"  style="min-width: 80px;">No</th>
                            <th scope="col"  style="min-width: 80px;">結果</th>
                            <th scope="col"  style="min-width: 80px;">削除</th>
                    <th v-for="(colName,idx) in headers" :key="idx" scope="col"  style="min-width: 150px;">
                        {{ colName }}
                    </th> 
                        </tr>                 
                    </thead>
                    <tbody>
                        <tr v-for="(record,idx) in records" :key="idx">
                            <td>{{ No = idx+1 }}</td>
                            <td v-if="import_result[idx] === true" class="text-success">✔</td>
                            <td v-else-if="import_result[idx] === false" class="text-danger">×</td>
                            <td v-else></td>
                            <td><button class="btn btn-danger" @click="deleteRow(idx)"><i class="fa fa-trash"></i></button></td>
                            <template v-for="(colName,col_no) in headers">
                                <td  :key="col_no"  v-if="!checkActive(idx,col_no)" @click="clickCell(idx,col_no, $event)">{{ displayItem(record,colName) }}</td>
                                <td  :key="col_no"  v-else  class="editable"><input type="text" class="form-control" v-model="records[idx][colName]" @blur="isActive = null" @keyup.enter="isActive = null"></td>                                
                            </template>

                        </tr>
                        <div class="overlay"  v-show="loading">
                        <i class="fa fa-refresh fa-spin text-secondary"></i>
                        </div>                  
                    </tbody></table>
                    </section>

                  </div>            
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                      <div class="row">
                      <div class="col-11 mx-auto mt-3">
                        <legend>エラーリスト</legend>
                        <div class="text-danger">
                            <dl v-html="error_dl" class="dl-horizontal">
                            </dl>
                        </div>

                      </div>
                      </div>

                  </div>
                  <!-- /.tab-pane -->
                </div>

              </div>
              <!-- /.card-body -->
                <div class="card-footer">
                  
                </div>


            </div>
            <!-- /.card -->
          </div>


        </div>
    </div>
</template>
<style>


.drop{
  /* position: absolute; */
  /* top: 50%;
  left: 50%; */
  /* margin-top: -100px; */
  /* margin-left: -250px; */
  /* width: 500px; */
  height: 100px;
  border: 4px dashed grey;
}
.drop p{
  width: 100%;
  height: 100%;
  /* text-align: center; */
  line-height: 85px;
  color: grey;
  font-family: Arial;
}
.overlay {
    background: #5f5f5f; 
    position: absolute; 
    top: 0;                
    right: 0;               
    bottom: 0;
    left: 0;
    opacity: 0.7;
}
</style>

<script>
import moment from 'moment';
import XLSX from 'xlsx';
import { isUndefined, isNull, isDate } from 'util';
import { stringify } from 'querystring';


    export default {
        data() {
            return {
                isActive:null,
                fileName:'',
                uploadFile:null,
                showTable:false,
                headers:[],
                records:[],
                import_result:[],
                list_columns:[],
                error_dl:'',
                list:{
                    id:null,
                    name:'',
                    import_date:'',
                    main_course:'',
                    main_kenpo:'',
                    first_serial_number:'',
                    last_serial_number:'',
                    max_serial_number:'',
                },
                loading:false, 
                progress:0,
            }
        },
        computed: {
            progress_rate(){
                if(this.records.length>0){
                    return parseInt(this.progress * 100 / this.records.length);

                }else{
                    return 0;
                }
            },
        },        
        methods: {
            clickCell(idx,col_no, event){
                this.isActive = [idx,col_no];
                this.$nextTick(() => event.target.firstChild.select());
            },
            checkActive(idx,col_no){
                if(this.isActive != null &&  this.isActive[0] == idx && this.isActive[1] == col_no){
                    return true;
                }else{
                    return false;
                }
            },
            deleteRow(idx){
                if(confirm("列を削除しますか？")){
                    this.records.splice(idx, 1);
                    this.import_result.splice(idx, 1);
                }
            },
            getListData:function(list){
                var first_no =0;var last_no = 0;var max_no = 0;
                var course_name = '';var point=0;
                var kenpo = [0,0];

                for (const record of this.records) {
                    let serial_no = record['整理番号'];

                    if(serial_no != ''){

                        if(first_no === 0 || serial_no < first_no){
                            first_no = serial_no;
                        }
                        if(max_no < serial_no){
                            max_no = serial_no;
                        }
                        last_no = serial_no;

                    }


                    if(typeof record['コース'] !== 'undefined') {
                        // does exist
                        if(point == 0){
                            course_name = record['コース'];
                            point += 1;

                        }else if(course_name == record['コース']){
                           point += 1;

                       }else{
                           point = point -1;
                       }
                    }

                    if(typeof record['協会けんぽ'] !== 'undefined') {
                        // does exist
                        kenpo[1] += 1;
                    }else{
                        kenpo[0] += 1;

                    }

                }

                if(first_no !== 0){
                    list.first_serial_number = first_no;
                    list.last_serial_number = last_no;
                    list.max_serial_number = max_no;
                }else{
                    list.first_serial_number = '';
                    list.last_serial_number = '';
                    list.max_serial_number = '';                    
                }

                list.main_course = course_name;

                list.main_kenpo = (kenpo[0] > kenpo[1])? 0: 1;

                return list;
            },
            createList:function(){

                this.list.name = this.fileName;
                this.list.import_date = moment().format('YYYY-MM-DD');
                let list = this.getListData(this.list);                
                return axios.post('api/reception_list',list);
            },
            displayItem:function(record,colName){
                if(isUndefined(record[colName])){
                    return "";
                }else{
                    return record[colName];
                }
            },
            importList:async function(){
                
                if(this.records.length == 0){
                    return false;
                }
                this.error_dl = '';
                this.progress = 0;
                try {
                    this.loading = true;            

                    let res = await this.createList();
                    this.list.id = res.data.list.id;

                    for (const [index, record] of this.records.entries()) {
                        record['reception_list_id'] = this.list.id;
                        if (record.hasOwnProperty('予約時間') == false) {
                            record['予約時間'] = '00:00:00';
                        }
                        let postData = {data:record};
                        let res = await axios.post('api/import',postData);                        ;

                        if(this.checkResult(res.data.result,record) == false){
                            Vue.set(this.import_result, index, false);
 
                        }else{
                            Vue.set(this.import_result, index, true);
                        }

                        console.log(res.data);
                        this.progress += 1;
                                              
                    }
                    Swal.fire(
                    'インポート完了',
                    '健診簿データをインポートしました',
                    'success'
                    );                  
                } catch (error) {
                    console.log(error);                    
                    // const {
                    // status,
                    // statusText
                    // } = error.response;
                    // console.log(`Error! HTTP Status: ${status} ${statusText}`);
                }finally{
                    this.loading = false;
                }

            },
            displayErrors:function(error,record){
                let strHtml = '<dt>' + record['通称名'] + 'のインポートエラー</dt>';
                if(!isNull(error)){
                    strHtml += '<dd>';
                    for(const error_line of error){
                        strHtml += error_line + '   ';
                    }
                    strHtml += '</dd>';
                }

                this.error_dl += strHtml;
            },
            checkResult:function(result,record){
                if(result.exception == true){
                    this.displayErrors(result.errors,record);
                    return false;
                }else{
                    if(result.account == false){
                        this.displayErrors(result.errors.account,record);
                        return false;
                    }else if(result.reserve == false){
                        this.displayErrors(result.errors.reserve,record);
                        return false;
                    }else if(result.select_item == false){
                        this.displayErrors(result.errors.select_item,record);
                        return false;
                    }
                }
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
              

                this.uploadFile = files[0];
                this.handleDrop(e);


            },            
            onChangeFile:function(){
                this.$refs.input_file.click();
            },

            handleDrop:function (e) {/** PARSING and DRAGDROP **/
                e.stopPropagation();
                //   e.preventDefault();
                console.log("DROPPED");
                //   var files = e.dataTransfer.files, i, f;
                //   for (i = 0, f = files[i]; i != files.length; ++i) {
                    let f = this.uploadFile;
                    var reader = new FileReader(),
                        name = f.name;
                    reader.onload = (e)=> {
                        let result;
                        let data = e.target.result; 
                        // データが多いとString.fromCharCode()でMaximum call stack size exceededエラーとなるので、
                        // 別途関数で処理をする。
                        //var arr = String.fromCharCode.apply(null, new Uint8Array(data));
                        let arr = this.handleCodePoints(new Uint8Array(data));                        
                        let workbook=XLSX.read(btoa(arr), {type: 'base64', cellDates:true});

                        let firstSheetName = workbook.SheetNames[0]; 
                        let worksheet = workbook.Sheets[firstSheetName];
                        let headers=this.get_header_row(worksheet);
                        if(this.checkHeaders(headers) == false){
                            Swal.fire(
                            'データ書式エラー',
                            '健診簿データが既定の書式でありません！',
                            'error'
                            );                             
                            return false;
                        }
                        this.headers = headers;
                        result=this.toJson(worksheet);

                        this.init_import_result(result.length);

                        this.records=result;
                        this.showTable = true;
                    };
                    reader.readAsArrayBuffer(f);
                //   }
                },
                checkHeaders:function(headers){
                    let result = this.list_columns.every(column=>{
                        return headers.includes(column);
                    });
                    return result;
                },
                loadRequiredColumns:function(){
                    axios.get("api/list_columns")
                    .then(({ data }) => { 
                        this.list_columns = data;
                        
                    });                      
                },
                init_import_result:function(count){
                    this.import_result = [];
                    for (let index = 0; index < count; index++) {
                        this.import_result.push('');
                       
                    }
                    
                },
                /** HELPERS **/
                get_header_row:function (sheet) {
                    var headers = [], range = XLSX.utils.decode_range(sheet['!ref']);
                    var C, R = range.s.r; /* start in the first row */
                    for(C = range.s.c; C <= range.e.c; ++C) { /* walk every column in the range */
                        var cell = sheet[XLSX.utils.encode_cell({c:C, r:R})] /* find the cell in the first row */
                        var hdr = "UNKNOWN " + C; // <-- replace with your desired default 
                        if(cell && cell.t) hdr = XLSX.utils.format_cell(cell);
                        headers.push(hdr);
                    }
                    return headers;
                },
                // see: https://github.com/mathiasbynens/String.fromCodePoint/issues/1
                handleCodePoints:function(array) {
                    var CHUNK_SIZE = 0x8000; // arbitrary number here, not too small, not too big
                    var index = 0;
                    var length = array.length;
                    var result = '';
                    var slice;
                    while (index < length) {
                        slice = array.slice(index, Math.min(index + CHUNK_SIZE, length)); // `Math.min` is not really necessary here I think
                        result += String.fromCharCode.apply(null, slice);
                        index += CHUNK_SIZE;
                    }
                    return result;
                },              
                toJson: function (worksheet) {
                    var result = [];

                    var roa = XLSX.utils.sheet_to_row_object_array(worksheet);
                    if(roa.length > 0){
                        roa.slice(1);
                        roa.forEach(row => {
                            Object.keys(row).forEach(key => {
                                if( isDate(row[key])){
                                    row[key] = moment(row[key]).format('YYYY-MM-DD');
                                }
                            });
                        });
                        result = roa;
                    }

                    return result;
                },                
                // workbook_to_json:function (workbook) {
                //     var result = {};
                //     workbook.SheetNames.forEach(function(sheetName) {
                //         var roa = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                //         if(roa.length > 0){
                //             result[sheetName] = roa;
                //         }
                //     });
                //     return result;
                // }                
        },
        async mounted() {
            await this.loadRequiredColumns();
            console.log('Component mounted.')
        }
    }
</script>
