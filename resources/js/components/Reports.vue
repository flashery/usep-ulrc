<template>
    <div class="inner-cont">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">Reports</h1>
            <div class="btn-toolbar mb-2 mb-md-0"></div>
        </div>
        <h4 class="mb-3">Report 1</h4>
        <div class="col-md-12">
            <bar-chart
                v-show="reports.length > 0"
                v-if="!load_reports"
                :keys="deway_decimals"
                :values="volumes"
                :label="'Number of Volumes'"
            ></bar-chart>
            <h3
                v-show="reports.length === 0 && !load_reports"
                class="no-search-result"
            >No journals to be displayed. Please search something.....</h3>
            <div v-loading="load_reports" v-show="load_reports" style="height:200px;">
                <h3 class="no-search-result">Loading results..</h3>
            </div>
        </div>
    </div>
</template>

<script>
import BarChart from "./reports/BarChart.vue";
import { format, getDaysInMonth, isSameDay } from "date-fns";

const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";

export default {
    data() {
        return {
            load_reports: false,
            deway_decimals: [],
            volumes: [],
            reports: []
        };
    },
    components: {
        BarChart
    },
    created() {
        this.getReports();
    },

    methods: {
        getReports() {
            this.load_reports = true;
            axios
                .get("/reports")
                .then(response => {
                    this.load_reports = false;
                    this.reports = response.data;
                    this.formatReports();
                })
                .catch(err => {
                    this.loading = false;

                    this.$message({
                        message:
                            "Oops, there is an error updating user profile image.",
                        type: "error"
                    });
                });
        },

        formatReports() {
            this.reports.forEach(data => {
                let key = Object.keys(data);
                let value = data[key[0]];
                this.deway_decimals.push(key);
                this.volumes.push(value);
            });
        }
    }
};
</script>

<style>
</style>
