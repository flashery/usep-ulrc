<template>
    <div class="inner-cont">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">List of Bibs</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <el-button-group>
                    <el-button type="primary" icon="el-icon-collection" @click="addNewBib">New Bib</el-button>
                    <el-button
                        type="primary"
                        icon="el-icon-collection-tag"
                        @click="addMarcTag"
                    >New MARC</el-button>
                    <el-button
                        type="primary"
                        icon="el-icon-s-order"
                        @click="viewMarcTags"
                    >View MARCs</el-button>
                </el-button-group>
                <el-popover
                    placement="bottom"
                    title="Select a course"
                    width="200"
                    trigger="click"
                    style="margin-left:10px"
                >
                    <el-select
                        v-model="selected_course"
                        @change="getBibByCourse()"
                        placeholder="Select a course"
                        value-key="id"
                    >
                        <el-option
                            v-for="course in courses"
                            :key="course.value"
                            :label="course.name"
                            :value="course"
                        ></el-option>
                    </el-select>
                    <el-button
                        type="primary"
                        icon="el-icon-download"
                        @click="getCourses()"
                        slot="reference"
                    >Export</el-button>
                </el-popover>
            </div>
        </div>
        <h4 class="mb-3">Bibs</h4>
        <table class="table" v-loading="loading">
            <thead>
                <tr>
                    <th scope="col">Call Number</th>
                    <th scope="col">Book Titles</th>
                    <th scope="col">Author</th>
                    <th scope="col">Accession Number</th>
                    <th scope="col">Copyright</th>
                    <th scope="col">No. of Titles</th>
                    <th scope="col">No. of Volumes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(bib, index) in bibs" :key="index">
                    <td>{{getSpecificTag(bib.marc_tags,'082')}}</td>
                    <td>{{getSpecificTag(bib.marc_tags,'245')}}</td>
                    <td>{{getSpecificTag(bib.marc_tags,'100')}}</td>
                    <td>{{getSpecificTag(bib.marc_tags,'016')}}</td>
                    <td>{{getCopyrightDate(getSpecificTag(bib.marc_tags,'082'))}}</td>
                    <td>1</td>
                    <td>{{ bib.volumes.length}}</td>
                    <td style="width: 111px;">
                        <el-button-group>
                            <el-button
                                size="mini"
                                type="primary"
                                icon="el-icon-edit"
                                @click="editBib(bib)"
                            ></el-button>
                            <el-button
                                size="mini"
                                type="danger"
                                icon="el-icon-delete"
                                @click="deleteBib(bib)"
                            ></el-button>
                        </el-button-group>
                    </td>
                </tr>
            </tbody>
        </table>
        <div ref="word-doc" id="word-doc">
            <div class="word-doc-area">
                <table style="width:100%">
                    <tr>
                        <td style="border:none;">
                            <img
                                width="100"
                                height="100"
                                align="center"
                                src="http://usep-urlc.herokuapp.com/images/usep-logo.png"
                                class="logo"
                            />
                        </td>
                    </tr>
                </table>
                <h1 align="center">University of Southeastern Philippines</h1>
                <h3 align="center">University Learning Resource Center</h3>
                <p
                    align="center"
                >{{ selected_course_name }} IN __________________ As of {{ current_month_year }}</p>

                <table class="table" style="width:100%;" v-loading="loading">
                    <tr>
                        <th rowspan="2" width="20" scope="col">Course Code/Title</th>
                        <th
                            rowspan="2"
                            width="20"
                            scope="col"
                        >Title Author. (year). Title. Place. Publishing</th>
                        <th colspan="2" width="220" scope="col">Overall Collection</th>
                        <th rowspan="2" width="220" scope="col">Call Number</th>
                    </tr>
                    <tr>
                        <th>No. of Titles</th>
                        <th>No. of Volumes</th>
                    </tr>
                    <tr v-for="(bib, index) in formatted_export_data" :key="index">
                        <td width="20">{{bib.code}}</td>
                        <td width="220">{{ bib.info }}</td>
                        <td width="220">1</td>
                        <td width="220">{{bib.volumes}}</td>
                        <td width="220">{{bib.call_number}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div>
            <el-pagination
                @size-change="handleSizeChange"
                @current-change="updateBibLists"
                layout="prev,pager,next"
                :pager-count="5"
                background
                :current-page.sync="pagination.current_page"
                :total="pagination.total"
            ></el-pagination>
        </div>
        <!-- <el-table :data="bibs">
            <el-table-column type="expand">
                <template slot-scope="props">
                    <ul>
                        <li v-for="(marc_tag, index) in props.row.marc_tags" :key="index">{{marc_tag.non_marc_tag}}</li>
                    </ul>
                </template>
            </el-table-column>
        </el-table>-->
        <bib-modal
            ref="bib_modal"
            @added="bibAdded"
            @updated="bibUpdated"
            @close="show_bib_modal = false"
            :mode="mode"
            :p_marc_tags="marc_tags"
            :p_bib="current_bib"
            :p_subjects="subjects"
            :show_bib_modal="show_bib_modal"
        ></bib-modal>
        <marc-tag-modal
            @added="marcTagAdded"
            @updated="marcTagUpdated"
            @close="show_marc_tag_modal = false"
            :mode="mode"
            :p_current_marc_tag="current_marc_tag"
            :show_marc_tag_modal="show_marc_tag_modal"
        ></marc-tag-modal>
        <view-marc-tags
            @deleted="marcTagDeleted"
            @edit-marc-tag="editMarcTag"
            @updated="marcTagUpdated"
            @close="show_view_marc_tags_modal = false"
            :p_marc_tags="marc_tags"
            :show_view_marc_tags_modal="show_view_marc_tags_modal"
        ></view-marc-tags>
    </div>
</template>

<script>
import BibModal from "./bib/BibModal";
import MarcTagModal from "./bib/MarcTagModal";
import ViewMarcTags from "./bib/ViewMarcTags";
import { format } from "date-fns";
import { log } from "util";

const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";
export default {
    components: { BibModal, MarcTagModal, ViewMarcTags },
    data() {
        return {
            loading: false,
            show_bib_modal: false,
            show_marc_tag_modal: false,
            show_view_marc_tags_modal: false,
            mode: "CREATE",
            current_bib: { id: 0, marc_tags: [], volumes: [], subjects: [] },
            current_marc_tag: {
                marc_tag: "",
                non_marc_tag: "",
                show_as_default: false
            },
            formatted_export_data: [],
            bibs: [],
            courses: [],
            selected_course: { name: "" },
            selected_course_name: "",
            current_month_year: format(new Date(), "MMMM YYYY"),
            marc_tags: [],
            subjects: [],
            pagination: {},
            bibs_by_course: []
        };
    },

    mounted() {
        this.getDatas();
        // this.sortBibs();
    },
    methods: {
        getDatas() {
            this.getMarcTags();
            this.getBibs();
        },
        sortBibs() {
            this.bibs.sort((a, b) => {
                console.log(a);
            });
        },
        getCopyrightDate(call_number) {
            return call_number.substr(call_number.length - 4, 4);
        },
        getSpecificTag(marc_tags, col) {
            let marc_tag = {};

            marc_tag = marc_tags.filter(marc_tag => {
                if (marc_tag.marc_tag === col) return true;
            });
            if (marc_tag.length === 0) return "";

            return marc_tag[0].pivot.value ? marc_tag[0].pivot.value : "";
        },
        getBibByCourse() {
            axios
                .get("/subject/by-course?course_id=" + this.selected_course.id)
                .then(response => {
                    this.bibs_by_course = response.data;
                    this.formatExportData();
                    this.export();
                })
                .catch(err => {
                    console.log(err);
                });
        },
        export() {
            if (this.formatted_export_data.length > 0)
                this.selected_course_name = this.selected_course.name.toUpperCase();
            this.$nextTick(() => {
                this.export2Doc(
                    "word-doc",
                    "Reports for " + this.selected_course.name
                );
            });
        },
        formatExportData() {
            console.log("asdasd");
            this.bibs_by_course.forEach(data => {
                data.bibs.forEach(bib => {
                    this.formatted_export_data.push({
                        code: data.code,
                        info:
                            this.getSpecificTag(bib.marc_tags, "245") +
                            " " +
                            this.getSpecificTag(bib.marc_tags, "100") +
                            " " +
                            this.getSpecificTag(bib.marc_tags, "260") +
                            " " +
                            this.getSpecificTag(bib.marc_tags, "245"),
                        volumes: bib.volumes.length,
                        call_number: this.getSpecificTag(bib.marc_tags, "082")
                    });
                });
            });
        },
        getCourses() {
            axios
                .get("/course")
                .then(res => {
                    this.courses = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        // Bib
        getBibs() {
            this.loading = true;
            axios
                .get("/bib?page=" + 1)
                .then(response => {
                    this.bibs = response.data.data;
                    this.makePagination(response.data);
                    this.loading = false;
                })
                .catch(err => {
                    console.log(err);
                    this.$message({
                        message:
                            "Sorry, there is an error getting the MARC tags.",
                        type: "error"
                    });
                });
        },
        addNewBib() {
            this.current_bib = {
                id: 0,
                marc_tags: this.marc_tags.filter(marc_tag => {
                    return marc_tag.show_as_default === true;
                }),
                volumes: [],
                subjects: []
            };
            this.mode = MODE_CREATE;
            this.show_bib_modal = true;
        },
        editBib(bib) {
            this.$refs["bib_modal"].setSubjects(bib.subjects);

            this.current_bib.id = bib.id;
            this.current_bib.volumes = bib.volumes;
            this.current_bib.subjects = bib.subjects.map(subject => {
                return subject.id;
            });

            this.current_bib.marc_tags = bib.marc_tags.map(marc_tag => {
                let data = {
                    id: marc_tag.id,
                    marc_tag: marc_tag.marc_tag,
                    non_marc_tag: marc_tag.non_marc_tag,
                    show_as_default: marc_tag.show_as_default,
                    marc_tag: marc_tag.marc_tag,
                    marc_tag: marc_tag.marc_tag,
                    value: marc_tag.pivot.value
                };
                return data;
            });

            this.mode = MODE_UPDATE;
            this.show_bib_modal = true;
        },
        deleteBib(bib) {
            this.$confirm(
                "This will permanently delete the MARC Tag. Continue?",
                "Warning",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning"
                }
            )
                .then(() => {
                    axios
                        .delete("/bib/" + bib.id)
                        .then(response => {
                            this.getDatas();
                            this.$message({
                                message: "Bib successfully deleted.",
                                type: "success"
                            });
                        })
                        .catch(err => {
                            this.modal_loading = false;

                            this.$message({
                                message:
                                    "Sorry, there is an error deleting the Bib.",
                                type: "error"
                            });
                        });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: "Delete canceled"
                    });
                });
        },
        bibAdded() {
            this.getDatas();
        },
        bibUpdated() {
            this.getDatas();
        },
        // MARC TAG
        getMarcTags() {
            axios
                .get("/marc-tag")
                .then(response => {
                    this.marc_tags = response.data;
                })
                .catch(err => {
                    this.$message({
                        message:
                            "Sorry, there is an error getting the MARC tags.",
                        type: "error"
                    });
                });
        },
        addMarcTag() {
            this.current_marc_tag = {
                marc_tag: "",
                non_marc_tag: "",
                show_as_default: false
            };
            this.mode = MODE_CREATE;
            this.show_marc_tag_modal = true;
        },
        editMarcTag(marc_tag) {
            this.current_marc_tag = marc_tag;
            this.mode = MODE_UPDATE;
            this.show_marc_tag_modal = true;
        },
        marcTagAdded(marc_tag) {
            this.getDatas();
        },
        marcTagUpdated() {
            this.getDatas();
        },
        marcTagDeleted() {
            this.getDatas();
        },
        viewMarcTags() {
            this.show_view_marc_tags_modal = true;
        },
        /* ======================================================== *
         * Pagination
         * ======================================================== */
        // Triggers whenever there are changes in the current page
        updateBibLists(val) {
            this.loading = true;
            axios
                .get("/bib?page=" + val)
                .then(response => {
                    this.bibs = response.data.data;
                    this.makePagination(response.data);
                    this.loading = false;
                })
                .catch(err => {
                    console.log(err);
                    this.$message({
                        message:
                            "Sorry, there is an error getting the MARC tags.",
                        type: "error"
                    });
                });
        },
        // Creates pagination
        makePagination(data) {
            this.pagination = {
                current_page: data.current_page,
                last_page: data.last_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url,
                per_page: data.per_page,
                total: data.total,
                to: data.to,
                from: data.from
            };
        },
        // Triggers whenever there is a change in page size
        handleSizeChange(val) {
            //
        },
        export2Doc(element, filename = "") {
            const type = "text/html;charset=utf-8";
            // const sub_url = "data:application/vnd.ms-word;charset=utf-8,"
            const sub_url = "data:text/html;charset=utf-8;charset=utf-8,";

            var html = `
            <html>
            <head><meta charset='utf-8'>
            <style> 
                @page word-doc-area{size: 595.35pt 841.95pt;mso-page-orientation: portrait;} 
                div.word-doc-area {page: word-doc-area;} 
               
               
                .word-doc-area {width: 100%;} 
                .word-doc-area img.logo {display: block; margin: 100pt auto;} 
                 table{
                    border-collapse:collapse;
                }
                td{
                    border:1pt solid gray;width:100%;padding:2pt;
                } 
                .word-doc-area table th, .word-doc-area table td { 
                vertical-align: middle; 
                text-align: center; 
                    border: 1pt solid #000000; 
                } 
                .word-doc-area th.multi-cell { 
                    width:40%;
                    padding: 0; 
                } 
                .word-doc-area th.multi-cell .top { 
                    border-bottom: 1pt solid #000000; 
                    padding: 10pt; 
                    width:300pt;
                } 
                .word-doc-area th.multi-cell .left { 
                    // float: left; 
                    padding: 10pt; 
                    width: 50%; 
                    border-left: 1pt solid; 
                    border-right: 1pt solid; 
                } 
                .word-doc-area th.multi-cell div.left:first-child { 
                    border: none; 
                } 
                </style>
                </head>
                <body>
                ${this.$refs[element].innerHTML}
                </body>
            </html>
            `;

            var blob = new Blob(["\ufeff", html], {
                type: type
                // type: "application/msword"
            });

            // Specify link url
            var url = sub_url + encodeURIComponent(html);

            // Specify file name
            filename = filename ? filename + ".doc" : "document.doc";

            // Create download link element
            var downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                // Create a link to the file
                downloadLink.href = url;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }

            document.body.removeChild(downloadLink);
        }
    }
};
</script>

<style>
.server-err .el-input__inner,
.server-err .el-input__inner:focus,
.server-err .el-textarea__inner,
.server-err .el-textarea__inner:focus {
    border-color: #f56c6c !important;
}
.el-form-server__error {
    color: #f56c6c;
    font-size: 12px;
    line-height: 1;
    padding-top: 4px;
    position: relative;
}
#word-doc {
    display: none;
}
.word-doc-area {
    width: 100%;
}
.word-doc-area table th,
.word-doc-area table td {
    vertical-align: middle;
    text-align: center;
    border: 1px solid #000000;
}
.word-doc-area img.logo {
    display: block;
    margin: 0 auto;
    float: none;
}
.word-doc-area table th,
.word-doc-area table td {
    vertical-align: middle;
    text-align: center;
    border: 1px solid #000000;
    width: 105px;
}
.word-doc-area th.multi-cell {
    padding: 0;
}
.word-doc-area th.multi-cell .top {
    border-bottom: 1px solid #993030;
    padding: 10px;
}
.word-doc-area th.multi-cell .left {
    float: left;
    padding: 10px;
    width: 50%;
    border-left: 1px solid;
    border-right: 1px solid;
}
.word-doc-area th.multi-cell div.left:first-child {
    border: none;
}
</style>
