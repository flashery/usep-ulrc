<template>
    <div class="inner-cont">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">Reports</h1>
            <div class="btn-toolbar mb-2 mb-md-0"></div>
        </div>
        <!-- START ALL COLLECTION -->
        <h4 class="mb-3">{{all_collection.title}}</h4>
        <div class="col-md-12">
            <bar-chart
                v-show="all_collection.reports.length > 0"
                :key="all_collection.key"
                :labels="all_collection.labels"
                :datasets="all_collection.datasets"
            ></bar-chart>
            <h3
                v-show="all_collection.reports.length === 0 && !all_collection.load"
                class="no-search-result"
            >No reports to be displayed. Please search something.....</h3>
            <div v-loading="all_collection.load" v-show="all_collection.load" style="height:200px;">
                <h3 class="no-search-result">Loading results..</h3>
            </div>
        </div>
        <!-- END ALL COLLECTION -->
        <hr />
        <!-- START ALL COLLECTION PER COLLEGE -->
        <h4 class="mb-3">{{all_collection_per_college.title}}</h4>
        <div class="col-md-12">
            <el-select
                @change="getGeneralReportsByCollege()"
                v-loading="load_departments"
                v-model="all_collection_per_college.department"
                placeholder="Select"
                value-key="id"
            >
                <el-option
                    v-for="department in departments"
                    :key="department.id"
                    :label="department.name"
                    :value="department"
                ></el-option>
            </el-select>
            <bar-chart
                v-show="all_collection_per_college.reports.length > 0"
                :key="all_collection_per_college.key"
                :labels="all_collection_per_college.labels"
                :datasets="all_collection_per_college.datasets"
            ></bar-chart>
            <h3
                v-show="all_collection_per_college.reports.length === 0 && !all_collection_per_college.load"
                class="no-search-result"
            >No reports to be displayed. Please search something.....</h3>
            <div
                v-loading="all_collection_per_college.load"
                v-show="all_collection_per_college.load"
                style="height:200px;"
            >
                <h3 class="no-search-result">Loading results..</h3>
            </div>
        </div>
        <!-- END ALL COLLECTION PER COLLEGE -->
        <hr />
        <!-- START COLLECTION PER COLLEGE -->
        <h4 class="mb-3">{{collection_per_college.title}}</h4>
        <div class="col-md-12">
            <el-select
                @change="getCollectionPerCollege()"
                v-loading="load_departments"
                v-model="collection_per_college.department"
                placeholder="Select"
                value-key="id"
            >
                <el-option
                    v-for="department in departments"
                    :key="department.id"
                    :label="department.name"
                    :value="department"
                ></el-option>
            </el-select>
            <bar-chart
                v-show="collection_per_college.reports.length > 0"
                :key="collection_per_college.key"
                :labels="collection_per_college.labels"
                :datasets="collection_per_college.datasets"
            ></bar-chart>
            <h3
                v-show="collection_per_college.reports.length === 0 && !collection_per_college.load"
                class="no-search-result"
            >No reports to be displayed. Please search something.....</h3>
            <div
                v-loading="collection_per_college.load"
                v-show="collection_per_college.load"
                style="height:200px;"
            >
                <h3 class="no-search-result">Loading results..</h3>
            </div>
        </div>
        <!-- END COLLECTION PER COLLEGE -->
        <hr />
        <!-- START COLLECTION PER COLLEGE -->
        <h4 class="mb-3">{{collection_each_year.title}}</h4>
        <div class="col-md-12">
            <el-select
                @change="getCollectionEachYear()"
                v-loading="load_departments"
                v-model="collection_each_year.department"
                placeholder="Select"
                value-key="id"
            >
                <el-option
                    v-for="department in departments"
                    :key="department.id"
                    :label="department.name"
                    :value="department"
                ></el-option>
            </el-select>
            <bar-chart
                v-show="collection_each_year.reports"
                :key="collection_each_year.key"
                :labels="collection_each_year.labels"
                :datasets="collection_each_year.datasets"
            ></bar-chart>
            <h3
                v-show="!collection_each_year.reports && !collection_each_year.load"
                class="no-search-result"
            >No reports to be displayed. Please search something.....</h3>
            <div
                v-loading="collection_each_year.load"
                v-show="collection_each_year.load"
                style="height:200px;"
            >
                <h3 class="no-search-result">Loading results..</h3>
            </div>
        </div>
        <!-- END COLLECTION PER COLLEGE -->
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
            load_departments: false,
            departments: [],
            all_collection: {
                title: "All Collection",
                load: false,
                reports: [],
                labels: [],
                datasets: [],
                key: 0,
                data: { volumes: [], no_of_titles: [] }
            },
            all_collection_per_college: {
                title: "All Collection Per College",
                load: false,
                department: {
                    id: 0
                },
                reports: [],
                labels: [],
                key: 0,
                datasets: [],
                data: { volumes: [], no_of_titles: [] }
            },
            collection_per_college: {
                title: "Collection Per College",
                load: false,
                department: {
                    id: 0
                },
                reports: [],
                labels: [],
                key: 0,
                datasets: [],
                data: { volumes: [] }
            },
            collection_each_year: {
                title: "Collection Each Year",
                load: false,
                department: {
                    id: 0
                },
                reports: [],
                labels: [],
                key: 0,
                datasets: [],
                data: { volumes: [] }
            }
        };
    },
    components: {
        BarChart
    },
    created() {
        this.getDepartments();
    },

    methods: {
        getDepartments() {
            this.load_departments = true;
            axios
                .get("/department")
                .then(response => {
                    this.load_departments = false;
                    this.departments = response.data;
                    this.all_collection_per_college.department.id = this.departments[0].id;
                    this.collection_per_college.department.id = this.departments[0].id;
                    this.collection_each_year.department.id = this.departments[0].id;
                    this.getGeneralReports();
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
        getGeneralReports() {
            this.all_collection.load = true;
            axios
                .get("/reports")
                .then(response => {
                    this.all_collection.reports = response.data;
                    this.formatReports(this.all_collection);

                    this.all_collection.load = false;
                    this.all_collection.key = this.generateKey();
                    this.getGeneralReportsByCollege();
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
        getGeneralReportsByCollege() {
            this.all_collection_per_college.reports = [];
            this.all_collection_per_college.labels = [];
            this.all_collection_per_college.redatasetsports = [];
            this.all_collection_per_college.data = {
                volumes: [],
                no_of_titles: []
            };

            this.all_collection_per_college.load = true;
            axios
                .get(
                    "/reports?department_id=" +
                        this.all_collection_per_college.department.id
                )
                .then(response => {
                    this.all_collection_per_college.reports = response.data;
                    this.formatReports(this.all_collection_per_college);

                    this.all_collection_per_college.load = false;
                    this.all_collection_per_college.key = this.generateKey();
                    this.getCollectionPerCollege();
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

        getCollectionPerCollege() {
            this.collection_per_college.reports = [];
            this.collection_per_college.labels = [];
            this.collection_per_college.redatasetsports = [];
            this.collection_per_college.data = {
                volumes: []
            };

            this.collection_per_college.load = true;
            axios
                .get(
                    "/reports?department_id=" +
                        this.collection_per_college.department.id +
                        "&type=collection_per_college"
                )
                .then(response => {
                    this.collection_per_college.reports = response.data;
                    this.formatReports(this.collection_per_college);

                    this.collection_per_college.load = false;
                    this.collection_per_college.key = this.generateKey();
                    this.getCollectionEachYear();
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

        getCollectionEachYear() {
            this.collection_each_year.reports = [];
            this.collection_each_year.labels = [];
            this.collection_each_year.redatasetsports = [];
            this.collection_each_year.data = {
                volumes: []
            };

            this.collection_each_year.load = true;

            axios
                .get(
                    "/reports?department_id=" +
                        this.collection_each_year.department.id +
                        "&type=collection_each_year"
                )
                .then(response => {
                    this.collection_each_year.reports = response.data;
                    this.formatCollectionEachYear(this.collection_each_year);

                    this.collection_each_year.load = false;
                    this.collection_each_year.key = this.generateKey();
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);

                    this.$message({
                        message:
                            "Oops, there is an error updating user profile image.",
                        type: "error"
                    });
                });
        },

        formatReports(object) {
            object.reports.forEach(data => {
                let key = Object.keys(data);
                object.labels.push(key);
                object.data.volumes.push(data[key].volumes);

                // If we need the number of titles
                if (object.data.no_of_titles)
                    object.data.no_of_titles.push(data[key].no_of_titles);
            });

            // If we need the number of titles
            if (object.data.no_of_titles) {
                object.datasets = [
                    {
                        label: "No. Of Titles",
                        backgroundColor: "#e17646",
                        data: object.data.no_of_titles
                    },
                    {
                        label: "Volumes",
                        backgroundColor: "maroon",
                        data: object.data.volumes
                    }
                ];
            } else {
                object.datasets = [
                    {
                        label: "Volumes",
                        backgroundColor: "maroon",
                        data: object.data.volumes
                    }
                ];
            }
        },
        formatCollectionEachYear(object) {

            object.labels = Object.keys(object.reports);
            object.labels.forEach(label => {
                object.data.volumes.push(object.reports[label].volumes);
            });

            object.datasets = [
                {
                    label: "Volumes",
                    backgroundColor: "maroon",
                    data: object.data.volumes
                }
            ];
        },
        generateKey() {
            return Date.now();
        }
    }
};
</script>

<style>
.el-select {
    margin: 20px 0;
}
</style>
