<script>
import { Bar } from "vue-chartjs";

export default {
    props: {
        labels: {
            type: Array,
            default: null
        },
        datasets: {
            type: Array,
            default: null
        }
    },
    extends: Bar,
    data: () => ({
        datacollection: {},

        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [
                    {
                        barPercentage: 0.5,
                        barThickness: 15,
                        maxBarThickness: 15,
                        minBarLength: 2,
                        gridLines: {
                            offsetGridLines: true
                        }
                    }
                ]
            },
            tooltips: {
                intersect: false
            }
        }
    }),

    mounted() {
        this.datacollection = {
            labels: this.labels,
            datasets: this.datasets
        };

        this.$nextTick(() => {
            this.renderChart(this.datacollection, this.options);
        });
    }
};
</script>