<script>
import { Doughnut, mixins } from 'vue-chartjs'
const { reactiveProp } = mixins
/**
 * Bar       : 棒グラフ
 * Line      : 折れ線グラフ
 * Doughnut  : ドーナツグラフ
 * Pie       : 円グラフ
 * Radar     : レーダーグラフ
 * Polararea : 極域グラフ
 * Bubble    : バブルグラフ
 * Scatter   : 散布図（mixinでは未対応とのこと）
 **/

export default { 
  extends: Doughnut,
  mixins: [reactiveProp],
  props: ['options'],
  mounted () {
    this.renderChart(this.chartData, this.options)
  }
}
</script>