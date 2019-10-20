<template>
    <el-table :data="deparments" style="width: 100%" :default-expand-all="expand_all">
        <el-table-column type="expand">
            <template slot-scope="props">
                <h3>Courses</h3>
                <hr />
                <ul>
                    <li v-for="(course, index) in props.row.courses" :key="index">
                        <el-row :gutter="20">
                            <el-col :span="6">{{course.name}}</el-col>
                            <el-col :span="8">
                                <el-button-group>
                                    <el-button
                                        size="small"
                                        type="default"
                                        icon="el-icon-view"
                                        @click="viewSubjects(course)"
                                    >View Subjects</el-button>
                                    <el-button
                                        :disabled="!authenticated_user.can_edit"
                                        size="small"
                                        type="primary"
                                        icon="el-icon-edit"
                                        @click="editCourse(course)"
                                    >Edit</el-button>

                                    <el-button
                                        :disabled="!authenticated_user.can_edit"
                                        size="small"
                                        type="danger"
                                        icon="el-icon-delete"
                                        @click="deleteCourse(course)"
                                    >Delete</el-button>
                                </el-button-group>
                            </el-col>
                        </el-row>
                    </li>
                </ul>
            </template>
        </el-table-column>
        <el-table-column width="120">
            <template slot-scope="scope">
                <img :src="scope.row.image | noImage" class="avatar avatar-medium" />
            </template>
        </el-table-column>
        <el-table-column label="Name" prop="name"></el-table-column>
        <el-table-column label="Description" prop="description"></el-table-column>
        <el-table-column>
            <template slot-scope="scope">
                <el-button-group>
                    <el-button
                        :disabled="!authenticated_user.can_edit"
                        size="small"
                        type="primary"
                        icon="el-icon-edit"
                        @click="editDepartment(scope.row)"
                    >Edit</el-button>
                    <el-button
                        :disabled="!authenticated_user.can_edit"
                        size="small"
                        type="danger"
                        icon="el-icon-delete"
                        @click="deleteDepartment(scope.row)"
                    >Delete</el-button>
                </el-button-group>
            </template>
        </el-table-column>
    </el-table>
</template>

<script>
export default {
    props: ["authenticated_user", "p_deparments"],
    data() {
        return {
            expand_all: true
        };
    },
    methods: {
        editDepartment(department) {
            this.$emit("edit-department", department);
        },
        deleteDepartment(department) {
            this.$confirm(
                "This will permanently delete the department. Continue?",
                "Warning",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning"
                }
            )
                .then(() => {
                    axios
                        .delete("/department/" + department.id)
                        .then(response => {
                            this.modal_loading = false;
                            this.$emit("deleted");
                            this.$message({
                                message: "Department successfully deleted.",
                                type: "success"
                            });
                        })
                        .catch(err => {
                            this.modal_loading = false;

                            this.$message({
                                message:
                                    "Sorry, there is an error deleting the department.",
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
        editCourse(course) {
            this.$emit("edit-course", course);
        },
        deleteCourse(course) {
            this.$confirm(
                "This will permanently delete the course. Continue?",
                "Warning",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning"
                }
            )
                .then(() => {
                    axios
                        .delete("/course/" + course.id)
                        .then(response => {
                            this.modal_loading = false;
                            this.$emit("deleted");
                            this.$message({
                                message: "Course successfully deleted.",
                                type: "success"
                            });
                        })
                        .catch(err => {
                            this.modal_loading = false;

                            this.$message({
                                message:
                                    "Sorry, there is an error deleting the course.",
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
        viewSubjects(course) {
            this.$emit("view-subjects", course);
        }
    },
    computed: {
        deparments() {
            return this.p_deparments;
        }
    }
};
</script>
<style>
.el-table__expanded-cell ul {
    padding: 0;
}
.el-table__expanded-cell ul li {
    list-style: none;
    margin: 15px;
}
</style>
