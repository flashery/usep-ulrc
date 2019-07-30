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
            </div>
        </div>
        <h4 class="mb-3">Bibs</h4>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Call Number</th>
                    <th scope="col">Heading</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(bib, index) in bibs" :key="index">
                    <th>{{index}}</th>
                    <td>{{getSpecificTag(bib.marc_tags,'Call Number')}}</td>
                    <td>{{getSpecificTag(bib.marc_tags,'Title')}}</td>
                    <td>
                        <el-button-group>
                            <el-button
                                size="small"
                                type="primary"
                                icon="el-icon-edit"
                                @click="editBib(bib)"
                            >Edit</el-button>
                            <el-button
                                size="small"
                                type="danger"
                                icon="el-icon-delete"
                                @click="deleteBib(bib)"
                            >Delete</el-button>
                        </el-button-group>
                    </td>
                </tr>
            </tbody>
        </table>
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
            @added="bibAdded"
            @updated="bibUpdated"
            @close="show_bib_modal = false"
            :mode="mode"
            :p_marc_tags="marc_tags"
            :p_bib="current_bib"
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
import { log } from "util";
const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";
export default {
    components: { BibModal, MarcTagModal, ViewMarcTags },
    data() {
        return {
            show_bib_modal: false,
            show_marc_tag_modal: false,
            show_view_marc_tags_modal: false,
            mode: "CREATE",
            current_bib: { id: 0, marc_tags: [] },
            current_marc_tag: {
                marc_tag: "",
                non_marc_tag: "",
                show_as_default: false
            },
            bibs: [],
            marc_tags: []
        };
    },

    mounted() {
        this.getDatas();
    },
    methods: {
        getDatas() {
            this.getMarcTags();
            this.getBibs();
        },
        getSpecificTag(marc_tags, col) {
            let marc_tag = {};
            marc_tag = marc_tags.filter(marc_tag => {
                if (marc_tag.non_marc_tag === col) return true;
            });

            if (marc_tag.length === 0) return "";

            return marc_tag[0].pivot.value;
        },
        // Bib
        getBibs() {
            axios
                .get("/bib")
                .then(response => {
                    this.bibs = response.data;
                    console.log("bibs", this.bibs);
                })
                .catch(err => {
                    this.$message({
                        message:
                            "Sorry, there is an error getting the MARC tags.",
                        type: "error"
                    });
                });
        },
        addNewBib() {
            this.current_bib = { id: 0, marc_tags: [] };
            this.mode = MODE_CREATE;
            this.show_bib_modal = true;
        },
        editBib(bib) {
            axios
                .get("/marc-tag")
                .then(response => {
                    this.marc_tags = response.data;
                    this.current_bib["id"] = bib.id;

                    bib.marc_tags.forEach(marc_tag => {
                        let tag = this.marc_tags.find(temp => {
                            return marc_tag.id === temp.id;
                        });

                        if (tag) {
                            this.marc_tags[
                                this.marc_tags.indexOf(tag)
                            ] = marc_tag;

                            this.current_bib["marc_tags"][marc_tag.id] =
                                marc_tag.pivot.value;
                        } else {
                            this.current_bib["marc_tags"][marc_tag.id] = "";
                        }
                    });
                })
                .catch(err => {
                    console.log(err);

                    this.$message({
                        message:
                            "Sorry, there is an error getting the MARC tags.",
                        type: "error"
                    });
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
</style>
