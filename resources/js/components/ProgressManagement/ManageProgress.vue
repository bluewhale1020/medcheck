<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-md-12">
        <h2>受診者の進捗状況 <small class="badge badge-secondary">{{ reserve_infos.total }}名</small></h2>

        <div class="card mt-5 card-outline card-info">
          <div class="card-header">
            <h3 class="card-title"></h3>
            <div class="card-tools">
              <button class="btn btn-warning" @click="exportProgress()">
                <i class="fa fa-print"></i>
                進捗状況データ出力
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
              <div class="card-body bg-lightblue">
                <h5 class="card-title">受診者の絞込</h5>
                <div class="col-12">
                  <div class="form-group row">
                    <div class="col-2 col-form-label">
                      <label class="control-label">受診状況</label>
                    </div>

                    <div class="col-4">
                      <select v-model="search_data.status" name="status" class="form-control">
                        <!-- <option value="">--受診状況を選択--</option> -->
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
                          <input
                            v-model="search_data.first_no"
                            type="text"
                            class="form-control"
                            id="first_no"
                            placeholder="通番はじめ"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group row">
                        <label for="last_no" class="col-2 col-form-label">　～</label>
                        <div class="col-sm-6">
                          <input
                            v-model="search_data.last_no"
                            type="text"
                            class="form-control"
                            id="last_no"
                            placeholder="通番おわり"
                          />
                        </div>
                      </div>
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
                <tr class="bg-cyan">
                  <th scope="col" style="min-width: 150px;">進捗度</th>
                  <th scope="col" style="min-width: 90px;">通番</th>
                  <th scope="col" style="min-width: 150px;">フリガナ</th>
                  <th scope="col" style="min-width: 150px;">生年月日</th>
                  <th scope="col" style="min-width: 90px;">性別</th>
                  <th
                    v-for="(name_jp,column) in columns"
                    :key="column"
                    scope="col"
                    style="min-width: 150px;"
                  >
                    {{ name_jp }}
                    <!-- <div class="header-height">{{ column }}</div>  -->
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="reserve in reserve_infos.data" :key="reserve.id">
                  <td>
                    <div class="progress progress-xs">
                      <div
                        class="progress-bar"
                        :class="reserve.progress == 100 ?  'bg-success': 'bg-info'"
                        :style="{width:reserve.progress + '%'}"
                      >{{ reserve.progress }}%</div>
                    </div>
                  </td>
                  <td>{{ reserve.serial_number }}</td>
                  <td>{{ reserve.account.kana }}</td>
                  <td>{{ reserve.account.birthdate }}</td>
                  <td>{{ reserve.account.sex }}</td>

                  <!-- <td
                    v-for="(itemData,itemName) in reserve.select_item"
                    :key="itemName.id"
                    :name="itemName"
                  > -->
                  <td
                    v-for="(name_jp,column) in columns"
                    :key="column"
                    :name="column"
                  >
                    <span v-if="reserve.select_item[column]  == 1" class="text-danger">●</span>
                    <span v-if="reserve.select_item[column]  == 2" class="text-success">✔</span>
                    <span v-if="reserve.select_item[column]  == 3" class="text-warning">△</span>
                  </td>
                </tr>
                <div class="overlay" v-show="loading">
                  <i class="fa fa-refresh fa-spin text-secondary"></i>
                </div>
              </tbody>
            </table>
            <!-- page control -->
            <pagination :data="reserve_infos" @pagination-change-page="loadProgressTable"></pagination>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      search_data: {
        search_key: "",
        status: 0,
        first_no: "",
        last_no: ""
      },
      loading: false,
      reserve_infos: {},
      columns: {},
      currentUrl: ""
    };
  },
  methods: {
    exportProgress: function() {
      const url =
        this.currentUrl + "medcheck/public/printer/exportProgressChecklist";

      let params = {
        search_key: this.search_data.search_key,
        status: this.search_data.status,
        first_no: this.search_data.first_no,
        last_no: this.search_data.last_no
      };

      const searchParams = jQuery.param(params);
      window.location.href = url + "?" + searchParams;
    },
    search: function() {
      this.loadProgressTable();
    },
    loadProgressTable: function(page = 1) {
      this.loading = true;
      axios
        .get("api/progress", {
          params: {
            // ここにクエリパラメータを指定する
            page: page,
            search_key: this.search_data.search_key,
            status: this.search_data.status,
            first_no: this.search_data.first_no,
            last_no: this.search_data.last_no
          }
        })
        .then(({ data }) => {
          this.reserve_infos = data;
        })
        .finally(() => (this.loading = false));
    },
    loadTableColumns: function() {
      axios.get("api/progress/columns").then(({ data }) => {
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
    this.loadProgressTable();
    console.log("Component mounted.");

    this.currentUrl = window.location.protocol + "//" + window.location.host + "/";
    this.connectChannel();      
  }
};
</script>
<style scoped>
/* progress page */

.header-height {
  height: 100px !important;
  word-wrap: break-word;
}
</style>

