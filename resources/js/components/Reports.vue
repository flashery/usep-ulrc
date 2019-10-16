<template>
    <div class="inner-cont">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">Reports</h1>
            <div class="btn-toolbar mb-2 mb-md-0"></div>
        </div>

        <!-- START ALL COLLECTION -->
        <h4 class="report-title mb-3">{{all_collection.title}}</h4>
        <a href="javascript:void(0)" @click="exportReport('all_collection')">Download Report</a>
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
        <h4 class="report-title mb-3">{{all_collection_per_college.title}}</h4>
        <a
            href="javascript:void(0)"
            @click="exportReport('all_collection_per_college')"
        >Download Report</a>
        <div class="col-md-12">
            <el-select
                @change="getGeneralReportsByCollege()"
                v-loading="load_departments"
                v-model="all_collection_per_college.course"
                placeholder="Select"
                value-key="id"
            >
                <div
                    v-for="department in departments"
                    :key="'department_id-'+department.id"
                    :label="department.name"
                    :value="department"
                >
                    <el-option
                        v-for="course in department.courses"
                        :key="'course_id-'+course.id"
                        :label="course.name"
                        :value="course"
                    ></el-option>
                </div>
            </el-select>
            <bar-chart
                v-show="all_collection_per_college.reports"
                :key="all_collection_per_college.key"
                :labels="all_collection_per_college.labels"
                :datasets="all_collection_per_college.datasets"
            ></bar-chart>
            <h3
                v-show="!all_collection_per_college.reports && !all_collection_per_college.load"
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
        <h4 class="report-title mb-3">{{collection_per_college.title}}</h4>
        <a href="javascript:void(0)" @click="exportReport('collection_per_college')">Download Report</a>
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
                v-show="collection_per_college.reports"
                :key="collection_per_college.key"
                :labels="collection_per_college.labels"
                :datasets="collection_per_college.datasets"
            ></bar-chart>
            <h3
                v-show="!collection_per_college.reports && !collection_per_college.load"
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
        <h4 class="report-title mb-3">{{collection_per_year.title}}</h4>
        <a href="javascript:void(0)" @click="exportReport('by_date_of_pub')">Download Report</a>
        <div class="col-md-12">
            <bar-chart
                v-show="collection_per_year.reports"
                :key="collection_per_year.key"
                :labels="collection_per_year.labels"
                :datasets="collection_per_year.datasets"
            ></bar-chart>
            <h3
                v-show="!collection_per_year.reports && !collection_per_year.load"
                class="no-search-result"
            >No reports to be displayed. Please search something.....</h3>
            <div
                v-loading="collection_per_year.load"
                v-show="collection_per_year.load"
                style="height:200px;"
            >
                <h3 class="no-search-result">Loading results..</h3>
            </div>
        </div>
        <!-- END COLLECTION PER COLLEGE -->
        <!-- START ALL COLLECTION NOT USED -->
        <h4 class="report-title mb-3">{{all_collection_not_used.title}}</h4>
        <a
            href="javascript:void(0)"
            @click="exportReport('all_collection_not_used')"
        >Download Report</a>
        <div class="col-md-12">
            <bar-chart
                v-show="all_collection_not_used.reports"
                :key="all_collection_not_used.key"
                :labels="all_collection_not_used.labels"
                :datasets="all_collection_not_used.datasets"
            ></bar-chart>
            <h3
                v-show="all_collection_not_used.reports.length === 0 && !all_collection_not_used.load"
                class="no-search-result"
            >No reports to be displayed. Please search something.....</h3>
            <div
                v-loading="all_collection_not_used.load"
                v-show="all_collection_not_used.load"
                style="height:200px;"
            >
                <h3 class="no-search-result">Loading results..</h3>
            </div>
        </div>
        <!-- END ALL COLLECTION NOT USED -->
    </div>
</template>

<script>
import BarChart from "./reports/BarChart.vue";
import { format, getDaysInMonth, isSameDay } from "date-fns";
import { log } from "util";

const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";

export default {
    data() {
        return {
            load_departments: false,
            departments: [],
            download_url: "",
            all_collection: {
                title: "All Collection",
                load: false,
                reports: [],
                labels: [],
                datasets: [],
                key: 0,
                data: { volumes: [], no_of_titles: [] }
            },
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
                title: "Graphical Representation Of All Collection PER Program",
                load: false,
                course: {
                    id: 0
                },
                reports: [],
                labels: [],
                key: 0,
                datasets: [],
                data: { volumes: [], no_of_titles: [] }
            },
            collection_per_college: {
                title: "Collection Per College by Date Of Publication",
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
            collection_per_year: {
                title: "Collection by Date Of Publication",
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
            all_collection_not_used: {
                title: "All Collection Not Used",
                load: false,
                reports: [],
                labels: [],
                datasets: [],
                key: 0,
                data: [],
                datas: []
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
        exportReport(type) {
            switch (type) {
                case "all_collection":
                    axios
                        .get("/reports/export")
                        .then(res => {
                            this.download_url = res.data;
                            this.download();
                        })
                        .catch(err => {
                            console.log(err);
                        });
                    break;
                case "all_collection_per_college":
                    axios
                        .get(
                            "/reports/export?type=all_collection_per_college&course_id=" +
                                this.all_collection_per_college.course.id
                        )
                        .then(res => {
                            this.download_url = res.data;
                            this.download();
                        })
                        .catch(err => {
                            console.log(err);
                        });
                    break;
                case "collection_per_college":
                    axios
                        .get(
                            "/reports/export?department_id=" +
                                this.collection_per_college.department.id +
                                "&type=collection_per_college"
                        )
                        .then(res => {
                            this.download_url = res.data;
                            this.download();
                        })
                        .catch(err => {
                            console.log(err);
                        });
                    break;
                case "by_date_of_pub":
                    axios
                        .get("/reports/export?type=by_date_of_pub")
                        .then(res => {
                            this.download_url = res.data;
                            this.download();
                        })
                        .catch(err => {
                            console.log(err);
                        });
                    break;
                case "all_collection_not_used":
                    axios
                        .get("/reports/export?type=all_collection_not_used")
                        .then(res => {
                            this.download_url = res.data;
                            this.download();
                        })
                        .catch(err => {
                            console.log(err);
                        });
                    break;
                default:
            }
        },
        download() {
            var downloadLink = document.createElement("a");
            downloadLink.href = this.download_url;
            downloadLink.click();
            document.body.removeChild(downloadLink);
        },
        getDepartments() {
            this.load_departments = true;
            axios
                .get("/department")
                .then(response => {
                    this.load_departments = false;
                    this.departments = response.data;
                    this.all_collection_per_college.course.id = this.departments[0].courses[0].id;
                    this.collection_per_college.department.id = this.departments[0].id;
                    this.collection_per_year.department.id = this.departments[0].id;

                    this.getGeneralReports();
                    this.getGeneralReportsByCollege();
                    this.getCollectionPerCollege();
                    this.getCollectionPerYear();
                    this.getCollectionNotUsed();
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;

                    this.$message({
                        message:
                            "Sorry, there is an error getting all departments.",
                        type: "error"
                    });
                });
        },
        getGeneralReports() {
            this.all_collection.load = true;
            axios
                .get("/reports")
                .then(response => {
                    this.all_collection.reports = response.data.reports;
                    this.formatReports(this.all_collection);

                    this.all_collection.load = false;
                    this.all_collection.key = this.generateKey();
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;

                    this.$message({
                        message: "Sorry, there is an error getting reports",
                        type: "error"
                    });
                });
        },
        getGeneralReportsByCollege() {
            this.all_collection_per_college.reports = [];
            this.all_collection_per_college.labels = [];
            this.all_collection_per_college.data = {
                volumes: [],
                no_of_titles: []
            };

            this.all_collection_per_college.load = true;
            axios
                .get(
                    "/reports?type=all_collection_per_college&course_id=" +
                        this.all_collection_per_college.course.id
                )
                .then(response => {
                    this.all_collection_per_college.reports =
                        response.data.reports;
                    this.formatAllCollectionPerCollege(
                        this.all_collection_per_college
                    );

                    this.all_collection_per_college.load = false;
                    this.all_collection_per_college.key = this.generateKey();
                })
                .catch(err => {
                    console.log(err);
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
            this.collection_per_college.data = {
                volumes: [],
                no_of_titles: []
            };
            this.collection_per_college.load = true;
            axios
                .get(
                    "/reports?department_id=" +
                        this.collection_per_college.department.id +
                        "&type=collection_per_college"
                )
                .then(response => {
                    this.collection_per_college.reports = response.data.reports;
                    this.formatCollectionPerYear(this.collection_per_college);
                    this.collection_per_college.load = false;
                    this.collection_per_college.key = this.generateKey();
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                    this.$message({
                        message:
                            "Oops, there is an error updating user profile image.",
                        type: "error"
                    });
                });
        },

        getCollectionPerYear() {
            this.collection_per_year.labels = [];
            this.collection_per_year.reports = [];
            this.collection_per_year.data = {
                volumes: [],
                no_of_titles: []
            };
            this.collection_per_year.load = true;
            axios
                .get("/reports?type=by_date_of_pub")
                .then(response => {
                    this.collection_per_year.reports = response.data.reports;
                    this.formatCollectionPerYear(this.collection_per_year);
                    this.collection_per_year.load = false;
                    this.collection_per_year.key = this.generateKey();
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

        getCollectionNotUsed() {
            this.all_collection_not_used.labels = [];
            this.all_collection_not_used.reports = [];
            this.all_collection_not_used.datas = [];
            this.all_collection_not_used.load = true;
            axios
                .get("/reports?type=all_collection_not_used")
                .then(response => {
                    this.all_collection_not_used.reports =
                        response.data.reports;
                    this.formatCollectionNotUsed(this.all_collection_not_used);
                    this.all_collection_not_used.load = false;
                    this.all_collection_not_used.key = this.generateKey();
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

        /*
         * =========================================================
         *  Format reports for the graph display
         * =========================================================
         */
        formatReports(object) {
            let index = 0;
            object.reports.forEach(report => {
                if (index !== 0) {
                    object.labels.push(report[0]);
                    object.data.volumes.push(report[1]);

                    if (object.data.no_of_titles)
                        object.data.no_of_titles.push(report[2]);
                }
                index++;
            });

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
        },

        formatAllCollectionPerCollege(object) {
            // object.labels = Object.keys(object.reports);
            // object.labels.forEach(label => {
            //     object.data.volumes.push(object.reports[label].volumes);
            //     // If we need the number of titles
            //     if (object.data.no_of_titles)
            //         object.data.no_of_titles.push(
            //             object.reports[label].no_of_titles
            //         );
            // });

            let index = 0;
            object.reports.forEach(report => {
                if (index !== 0) {
                    object.labels.push(report[0]);
                    object.data.volumes.push(report[1]);

                    if (object.data.no_of_titles)
                        object.data.no_of_titles.push(report[2]);
                }
                index++;
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

        formatCollectionPerYear(object) {
            let index = 0;
            object.reports.forEach(report => {
                if (index !== 0) {
                    object.labels.push(report[0]);
                    object.data.volumes.push(report[1]);

                    if (object.data.no_of_titles)
                        object.data.no_of_titles.push(report[2]);
                }
                index++;
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

        formatCollectionNotUsed(object) {
            let index = 0;
            object.reports[0].forEach(header_label => {
                if (index !== 0) {
                    object.data.push({
                        header_label: header_label,
                        values: [],
                        color:
                            "#" + ((Math.random() * 0xffffff) << 0).toString(16)
                    });
                }
                index++;
            });

            index = 0;
            let object_data_ctr = 0;
            object.reports.forEach(report => {
                if (index !== 0) {
                    if (object_data_ctr < object.data.length) {
                        object.labels = [];

                        let inner_index = 0;
                        Object.keys(report).forEach(key => {
                            object.labels.push(key);

                            // if (inner_index !== 0) {
                            console.log(inner_index, report[key], key);
                            object.data[object_data_ctr].values.push(
                                report[key]
                            );
                            // }
                            inner_index++;
                        });
                        // report.forEach(rep => {
                        //     if (inner_index !== 0)
                        //         object.data[object_data_ctr].values.push(rep);
                        //     inner_index++;
                        // });
                        object_data_ctr++;
                    }
                }
                index++;
            });

            object.data.forEach(dataset => {
                object.datasets.push({
                    label: dataset.header_label,
                    backgroundColor: dataset.color,
                    data: dataset.values
                });
            });
        },

        getRandomColor() {
            var letters = "0123456789ABCDEF".split("");
            var color = "#";
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
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
.report-title {
    text-transform: uppercase;
}
</style>
