<script>
import { Bar } from "vue-chartjs";

export default {
    props: {
        keys: {
            type: Array,
            default: null
        },
        values: {
            type: Array,
            default: null
        },
        label: {
            type: String,
            default: ''
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
                        barThickness: 6,
                        maxBarThickness: 8,
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

    created() {
        this.datacollection = {
            labels: this.keys,
            datasets: [
                {
                    label: this.label,
                    backgroundColor: "maroon",
                    data: this.values
                }
            ]
        };

        this.$nextTick(() => {
            this.renderChart(this.datacollection, this.options);
        });
    }
};
</script>