<template>
    <div class="container">
        <div class="row mt-4">
            <h2>DashBoard</h2>

    <div class="container  mt-3">
        <div class="row">
            <div class="col-6">

            <message-board></message-board>


            </div>
            <div class="col-6">
              <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-bullhorn"></i>&nbsp;&nbsp;
                  活動報告
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                </div>                
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="scroll_box">
                <dl>
                  <template v-for="(event,idx) in event_list">
                    <div :key="idx">
                      <dt>{{ event.created_at }}</dt>
                      <dd>[{{ event.name }}] {{ event.notes }}</dd>
                    </div>
                  </template>
                </dl>
                  <div class="overlay"  v-show="loading">
                      <i class="fa fa-refresh fa-spin text-secondary"></i>
                  </div>                  
                </div>               
              </div>
              <!-- /.card-body -->
            </div>
            </div>           

        </div>
    </div>

    <div class="container">
        <div class="row">

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title"><i class="fa fa-line-chart" aria-hidden="true"></i>&nbsp;&nbsp;受診状況</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-8">
                    <p class="text-center">
                      <strong>時間ごとの受診人数（{{ reserve_data.interval }}分単位）</strong>
                    </p>

                    <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <div class="small">
                          <bar-chart v-if="loaded" :chart-data="datacollection" :options="options" :styles="myStyles"></bar-chart>
                        </div>
                    </div>
                    <!-- /.chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-4">
                    <p class="text-center">
                      <strong>健診の進捗</strong>
                    </p>

                    <div class="progress-panel">

                    <div class="progress-group mb-5">
                      受診率
                      <span class="float-right"><b>{{ reserve_data.check_in_count}}</b>/{{ reserve_data.reserve_count }}</span>
                      <div class="progress" style="height: 30px;">
                        <div class="progress-bar bg-primary" :style="{width: check_in_rate + '%'}">{{check_in_rate}}%</div>
                      </div>
                    </div>
                    <!-- /.progress-group -->

                    <div class="progress-group">
                      健診完了率
                      <span class="float-right"><b>{{ reserve_data.complete_count}}</b>/{{ reserve_data.check_in_count}}</span>
                      <div class="progress" style="height: 30px;">
                        <div class="progress-bar bg-success" :style="{width: complete_rate + '%'}">{{complete_rate}}%</div>
                      </div>
                    </div>
                    <!-- /.progress-group -->

                    </div>

                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <h5 class="description-header">{{ reserve_data.reserve_count }}</h5>
                      <span class="description-text">受診予定人数</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <h5 class="description-header">{{ reserve_data.check_in_count }}</h5>
                      <span class="description-text">受付済み</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <h5 class="description-header">{{ reserve_data.check_in_count - reserve_data.complete_count }}</h5>
                      <span class="description-text">受診中</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block">
                      <h5 class="description-header">{{ reserve_data.complete_count }}</h5>
                      <span class="description-text">健診完了</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
                <div class="overlay"  v-show="loading2">
                    <i class="fa fa-refresh fa-spin text-secondary"></i>
                </div> 

            </div>
            <!-- /.card -->
          </div>            

        </div>
    </div>

    <div class="container">
        <div class="row">

<div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-pie-chart" aria-hidden="true"></i>
                  &nbsp;&nbsp;検査エリアの進捗状況
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <template v-for="(data,area_name) in donutdata_array">
                    <div  class="col-3 text-center area_info" :key="area_name">
                      <div style="display:inline;width:120px;height:120px;">
                        <doughnut-chart v-if="loaded" :chart-data="data" :options="donut_options"></doughnut-chart>
                      </div>
                      <div> 
                        <h3 class="text-success">{{ calcRate(data.datasets[0].data[0],data.datasets[0].data[1],true) }}%</h3> 
                        <div class="knob-label">{{ area_name }}</div>
                        </div>
                    </div>

                    <!-- ./col -->
                  </template>

                </div>
                <!-- /.row -->
                  <div class="overlay"  v-show="loading2">
                      <i class="fa fa-refresh fa-spin text-secondary"></i>
                  </div> 

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

            

        </div>
    </div> 
        </div>
    </div>
</template>
<style scoped>

  .small {
    /* max-width: 600px; */
    /* max-height: 300px; */
    margin: auto;
    overflow:scroll;    
  }

  /* .area_perc{
    position:absolute; 
    top: 80px;
    left: 100px;
  } */
  .area_info{
    position:relative;
    margin-bottom: 60px;
  }
  .scroll_box{
    overflow:scroll;
    height: 600px;
  }

</style>

<script>
import MessageBoard from './MessageBoard.vue'
import LineChart from './AreaChart.vue'
import BarChart from './BarChart.vue'
import DoughnutChart from './DoughnutChart.vue'
import { cloneDeep } from 'lodash';

    export default {
      components: { LineChart, DoughnutChart,BarChart ,MessageBoard},
      data: () => ({
         loaded: false,
        loading: false,        
        loading2: false,        
        datacollection:null,
        // style="height: 250px; min-height: 250px; display: block; width: 535px;" width="668" height="312"
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            yAxes: [
              {
                id: "yAxis_1",
                type: "linear",
                ticks: {
                  beginAtZero: true,
                  callback: function(value) {if (value % 1 === 0) {return value;}}
                },
              },
            ],
          }      
        },
        myStyles:{
          height: '312px',
          // 'min-height': '250px',
          width: '668px',
          position: 'relative'
        },
        donutdata_array:{},
        donut_options:{
          cutoutPercentage: 75,
        },
        area_data:{},
        reserve_data:{
          check_in_rate:0,
          complete_rate:0,
          check_in_count:0,
          complete_count:0,
          count_at_intervals:[],
          interval:30,
          last_updated_time:'',
          reserve_count:0,
          start_time:'',
        },
        area_names:[],
        event_list:{},

      }),
      computed: {
        check_in_rate(){
          return this.calcRate(this.reserve_data.check_in_count,this.reserve_data.reserve_count);
        },
        complete_rate(){
          return this.calcRate(this.reserve_data.complete_count,this.reserve_data.check_in_count);
        }
        },
      methods: {
        mutateObj:function(obj){
          let clonedObj = cloneDeep(obj);
          obj = clonedObj;
        },        
        calcRate:function(param1,param2,is_donut = false)
        {
          let numerator = parseInt(param1);

          if(is_donut){

            var denominator = parseInt(param2) + numerator;
          }else{
            var denominator = parseInt(param2);

          }

          if(denominator == 0){
            return 0;
          }
          return  Math.floor(100 * numerator/denominator);
        },
        setDonutData:function(){
          for(const area_name of this.area_names){
            if(this.area_data[area_name]['対象者数'] != 0){
              let temp_data = {
                  datasets: [{
                    backgroundColor:['#46B35F','#e8e8e8'],
                      data: [this.area_data[area_name]['完了数'],this.area_data[area_name]['対象者数'] - [this.area_data[area_name]['完了数']]]
                  }],
                  // These labels appear in the legend and in the tooltips when hovering different arcs
                  labels: [
                      '受診済',
                      '未受診',
                  ]
              };
              Vue.set(this.donutdata_array,area_name,temp_data);
            
            }
          }
          this.loaded = true;
        },
        sortStatData:function(stat_data){
            Object.keys(stat_data).forEach(key => {
                if(this.reserve_data[key] != undefined){
                  Vue.set(this.reserve_data,key,stat_data[key]);
                }else{
                  let area_name = key.replace("対象者数","").replace("完了数","");
                  if(this.area_data[area_name] != undefined){
                    this.area_data[area_name][key.replace(area_name,"")] = stat_data[key];                  
                  }else{
                    let tmp_array = {[key.replace(area_name,"")]:stat_data[key]};
                    this.area_data[area_name] = tmp_array;

                  }
                }
            });
        },
        loadStatData:function(){
          this.loading2 = true;
          axios.get('api/stat')
          .then(({ data }) => { 
                this.sortStatData(data.stat_data);         
                this.area_names = data.area_names;
                this.fillData();
                this.setDonutData();         
            
            })
            .finally(() => this.loading2 = false)
            ;             
        },

        formatTime:function(total_minutes){
          return Math.floor(total_minutes/60) + '時' + total_minutes % 60 + '分';
        },
        fillData:function() {

            let label = [];
            let time = parseInt(this.reserve_data.start_time) * parseInt(this.reserve_data.interval);
            for (let index = 0; index < this.reserve_data.count_at_intervals.length; index++) {
              label.push(this.formatTime(time));
              time += parseInt(this.reserve_data.interval);
              
            }

            this.datacollection = {
              labels: label,
              datasets: [
                {
                  label: '受診人数',
                  backgroundColor: '#f87979',
                  data: this.reserve_data.count_at_intervals
                }
              ]
            }
          },

        loadEventList:function(){
                    this.loading = true;
          axios.get('api/event_list')
          .then(({ data }) => {       
                this.event_list = data;             
            })
            .finally(() => this.loading = false)
            ;  

        },
        connectChannel() {
            ///重要！！！ medcheck_database_のprefixをチャネル名に付ける事！！
            Echo.channel("medcheck_database_checkup-data-updated").listen("CheckupDataUpdated", e => {
              if(e.category == 'event_list'){
                this.loadEventList();
              }else{

                this.loadData();
              }
            });
        },
        async loadData (){
          await this.loadStatData();
          await this.loadEventList();
        }, 

      },
       mounted () {
        console.log('Component mounted.');
        this.loadData();    

        this.connectChannel();         
      },  
         
    }
</script>
