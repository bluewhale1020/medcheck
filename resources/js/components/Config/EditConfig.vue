<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-md-12">
        <h2>システム設定</h2>

        <div class="mx-auto">
          <form @submit.prevent="updateConfig()" @keydown.enter.prevent method="put">
            <div class="col-12 mt-4">
              <legend class="text-primary">〇健診簿</legend>

              <div class="row">
                <div class="form-group col-12 row">
                  <label class="col-4 col-form-label">現在使用中の健診簿</label>
                  <div class="col-8">
                    <select
                      v-model="form.reception_list_id"
                      name="reception_list_id"
                      @keyup.enter="moveToNext(1)"
                      ref="1"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('reception_list_id') }"
                    >
                      <!-- <option value="">--選択--</option> -->
                      <option
                        v-for="(itemValue,itemName) in this.reception_lists"
                        :key="itemName"
                        :value="itemName"
                      >{{ itemValue }}</option>
                    </select>
                    <has-error :form="form" field="reception_list_id"></has-error>
                  </div>
                </div>
              </div>
              <!-- row -->
            </div>
            <hr />
            <div class="col-12 mt-4">
              <legend class="text-primary">〇予約受付</legend>

              <div class="row">
                <div class="col-12">
                  <div class="form-group row">
                    <label class="col-4 col-form-label">通番開始番号</label>
                    <div class="col-8">
                      <input
                        v-model="form.first_serial_number"
                        type="text"
                        name="first_serial_number"
                        @keyup.enter="moveToNext(2)"
                        ref="2"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('first_serial_number') }"
                      />
                      <has-error :form="form" field="first_serial_number"></has-error>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-4 col-form-label">バーコード受信方法</label>
                    <div class="col-8">
                      <select
                        v-model="form.barcode_protocol"
                        name="barcode_protocol"
                        @keyup.enter="moveToNext(3)"
                        ref="3"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('barcode_protocol') }"
                      >
                        <!-- <option value="">--選択--</option> -->
                        <option value="USB(HID)">USB(HID)</option>
                        <option value="その他">その他</option>
                      </select>
                      <has-error :form="form" field="barcode_protocol"></has-error>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-4 col-form-label">バーコードカラム名</label>
                    <div class="col-8">
                      <input
                        v-model="form.barcode_column_name"
                        type="text"
                        name="barcode_column_name"
                        @keyup.enter="moveToNext(4)"
                        ref="4"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('barcode_column_name') }"
                      />
                      <has-error :form="form" field="barcode_column_name"></has-error>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-4 col-form-label">バーコードカラム名2</label>
                    <div class="col-8">
                      <input
                        v-model="form.barcode_column_name2"
                        type="text"
                        name="barcode_column_name2"
                        @keyup.enter="moveToNext(5)"
                        ref="5"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('barcode_column_name2') }"
                      />
                      <has-error :form="form" field="barcode_column_name2"></has-error>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-4 col-form-label">白紙バーコードNo</label>
                    <div class="col-8">
                      <input
                        v-model="form.default_barcode_no"
                        type="text"
                        name="default_barcode_no"
                        @keyup.enter="moveToNext(6)"
                        ref="6"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('default_barcode_no') }"
                      />
                      <has-error :form="form" field="default_barcode_no"></has-error>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-4 col-form-label">出力ファイル名</label>
                    <div class="col-8">
                      <input
                        v-model="form.reception_list_filename"
                        type="text"
                        name="reception_list_filename"
                        @keyup.enter="moveToNext(7)"
                        ref="7"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('reception_list_filename') }"
                      />
                      <has-error :form="form" field="reception_list_filename"></has-error>
                    </div>
                  </div>
                </div>
              </div>
              <!-- row -->
            </div>
            <hr />
            <div class="clearfix"></div>
            <div class="col-12 text-right mt-4">
              <button
                type="submit"
                class="btn btn-primary"
                ref="update"
                :disabled="this.saving"
              >{{ (this.saving == false)? '変更の保存': '保存中・・・'}}</button>
            </div>
          </form>
          <div class="overlay text-center" v-show="loading" ><!---->
              <i class="fa fa-refresh fa-spin text-primary"></i>
          </div>            
        </div>
        <!-- div.row -->
      </div>
    </div>
  </div>
  <!-- div.container -->
</template>
<style scoped>
.fa-spin {
    position: relative;
    top:200px;
    font-size:50px;    
}

.overlay {
    opacity: 0.3;
    ;background-color: #dae5ea;
}

</style>

<script>
import { isUndefined, isNull, isNumber } from "util";

export default {
  data: function() {
    return {
      //   btn_label:'変更の保存',
      loading: false,
      saving: false,
      form: new Form({
        reception_list_id: "",
        first_serial_number: "",
        barcode_protocol: "",
        barcode_column_name: "",
        barcode_column_name2: "",
        default_barcode_no: "",
        reception_list_filename: ""
      }),
      configurations: {},
      reception_lists: {}
    };
  },
  methods: {
    moveToNext: function(input_idx) {
      if (!isUndefined(this.$refs[input_idx + 1])) {
        this.$refs[input_idx + 1].focus();
      } else {
        this.$refs.update.focus();
      }
    },
    loadConfigData: function() {
      this.loading = true;
      axios
        .get("api/configuration")
        .then(({ data }) => {
          this.configurations = data.configurations;
          this.reception_lists = data.reception_lists;
          this.form.fill(this.configurations);
        })
        .finally(() => (this.loading = false));
    },
    updateConfig: function() {
      this.saving = true;
      this.$Progress.start();
      // Submit the form via a PUT request
      this.form
        .put("api/configuration")
        .then(({ data }) => {
          console.log(data);
          this.$Progress.finish();
          Toast.fire({
            type: "success",
            title: data.message
          });

          this.$eventHub.$emit("configUpdated");
        })
        .catch(error => {
          console.log(error);
          this.$Progress.fail();
        })
        .finally(() => (this.saving = false));
    }
  },
  mounted() {
    this.loadConfigData();
    console.log("Component mounted.");
  }
};
</script>