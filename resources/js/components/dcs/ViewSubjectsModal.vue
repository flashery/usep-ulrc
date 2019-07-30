<template>
    <el-dialog
        title="List of Subjects"
        :visible.sync="view_subject_dlg_show"
        :before-close="handleClose"
        width="45%"
        v-loading="modal_loading"
    >
        <h2 style="text-align: center;">{{ department.name }}</h2>
        <h3 style="text-align: center;">{{ course.name }}</h3>
        <hr>
        <el-table :data="subjects">
            <el-table-column label="Code" prop="code"></el-table-column>
            <el-table-column label="Name" prop="name"></el-table-column>
            <el-table-column label="Description" prop="description"></el-table-column>
            <el-table-column>
                <template slot-scope="scope">
                    <el-button-group>
                        <el-button
                            size="small"
                            type="primary"
                            icon="el-icon-edit"
                            @click="editSubject(scope.row)"
                        >Edit</el-button>
                        <el-button
                            size="small"
                            type="danger"
                            icon="el-icon-delete"
                            @click="deleteSubject(scope.row)"
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
const URL_PATH = "/course";
const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";
export default {
    props: [
        "mode",
        "view_subject_dlg_show",
        "p_department",
        "p_course",
        "p_subjects"
    ],
    data() {
        return {
            modal_loading: false
        };
    },
    methods: {
        handleClose() {
            this.$emit("close");
        },
        editSubject(subject) {
            this.$emit('edit-subject',subject);
        },
        deleteSubject(subject) {
            this.$confirm(
                "This will permanently delete the subject. Continue?",
                "Warning",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning"
                }
            )
                .then(() => {
                    axios
                        .delete("/subject/" + subject.id)
                        .then(response => {
                            this.$emit('deleted');

                            // this.subjects.splice(
                            //     this.subjects.indexOf(subject),
                            //     1
                            // );

                            this.$message({
                                message: "Subject successfully deleted.",
                                type: "success"
                            });
                        })
                        .catch(err => {
                            this.modal_loading = false;

                            this.$message({
                                message:
                                    "Sorry, there is an error deleting the subject.",
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
    },
    computed: {
        subjects() {
            return this.p_subjects;
        },
        department() {
            return this.p_department;
        },
        course() {
            return this.p_course;
        }
    }
};
</script>

<style>
</style>
