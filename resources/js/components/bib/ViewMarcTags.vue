<template>
    <el-dialog
        title="List of Marc Tags"
        :visible.sync="show_view_marc_tags_modal"
        :before-close="handleClose"
        width="45%"
        v-loading="modal_loading"
    >
        <el-table :data="marc_tags">
            <el-table-column label="MARC Tag" prop="marc_tag"></el-table-column>
            <el-table-column label="NON-MARC Tag" prop="non_marc_tag"></el-table-column>
            <el-table-column label="Show as Default" align="center">
                <template slot-scope="scope">
                    <el-checkbox
                        v-model="scope.row.show_as_default"
                        @change=" updateToDatabase(scope.row)"
                        style="display:block;"
                    ></el-checkbox>
                </template>
            </el-table-column>
            <el-table-column>
                <template slot-scope="scope">
                    <el-button-group>
                        <el-button
                            size="small"
                            type="primary"
                            icon="el-icon-edit"
                            @click="editMarcTag(scope.row)"
                        >Edit</el-button>
                        <el-button
                            size="small"
                            type="danger"
                            icon="el-icon-delete"
                            @click="deletMarcTag(scope.row)"
                        >Delete</el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>

        <div slot="footer" class="dialog-footer">
            <el-button type="default" icon="el-icon-circle-close" @click="handleClose">Close</el-button>
        </div>
    </el-dialog>
</template>

<script>
const URL_PATH = "/marc-tag";
export default {
    props: ["show_view_marc_tags_modal", "p_marc_tags"],
    data() {
        return {
            modal_loading: false
        };
    },
    methods: {
        handleClose() {
            this.$emit("close");
        },
        editMarcTag(marc_tag) {
            this.$emit("edit-marc-tag", marc_tag);
        },
        updateToDatabase(marc_tag) {
            this.modal_loading = true;
            axios
                .put(URL_PATH + "/" + marc_tag.id, marc_tag)
                .then(response => {
                    this.modal_loading = false;
                    this.$emit("updated");
                    this.$message({
                        message: "MARC tag successfully updated.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;
                    this.errors = err.response.data.errors;
                    this.$message({
                        message:
                            "Sorry, there is an error updating the MARC tag.",
                        type: "error"
                    });
                });
        },
        deletMarcTag(marc_tag) {
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
                        .delete(URL_PATH + "/" + marc_tag.id)
                        .then(response => {
                            this.$emit("deleted");
                            this.$message({
                                message: "MARC Tag successfully deleted.",
                                type: "success"
                            });
                        })
                        .catch(err => {
                            this.modal_loading = false;

                            this.$message({
                                message:
                                    "Sorry, there is an error deleting the MARC Tag.",
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
        }
    },
    computed: {
        marc_tags() {
            return this.p_marc_tags;
        }
    }
};
</script>

<style>
</style>
