<template>
    <el-dialog
        :title="title"
        :visible.sync="course_dlg_show"
        :before-close="handleClose"
        width="35%"
        v-loading="modal_loading"
    >
        <el-form
            :model="form"
            :rules="rules"
            ref="course_form"
            label-position="left"
            label-width="150px"
        >
            <el-form-item v-if="mode === 'CREATE'" label="Departments" prop="departments">
                <el-select
                    v-model="form.departments"
                    multiple
                    placeholder="Select departments"
                    value-key="id"
                >
                    <el-option
                        v-for="(department, index) in departments"
                        :key="index"
                        :label="department.name"
                        :value="department"
                    ></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Course Name" prop="name">
                <el-input v-model="form.name"></el-input>
            </el-form-item>

            <el-form-item label="Description">
                <el-input type="textarea" v-model="form.description"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button type="default" :icon="icon" @click="submit()">{{action}}</el-button>
        </div>
    </el-dialog>
</template>

<script>
const URL_PATH = "/course";
const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";
export default {
    props: ["mode", "course_dlg_show", "p_departments", "p_course"],
    data() {
        return {
            modal_loading: false,
            uploading: false,
            rules: {
                departments: [
                    {
                        type: "array",
                        required: true,
                        message: "Please select at least one department",
                        trigger: "change"
                    }
                ],
                name: [
                    {
                        required: true,
                        message: "Please input a course name",
                        trigger: "blur"
                    },
                    {
                        min: 3,
                        max: 30,
                        message: "Name should be atleast to 3 to 30 letters",
                        trigger: "blur"
                    }
                ],
                image: [
                    {
                        required: true,
                        message: "Please select an image",
                        trigger: "change"
                    }
                ]
            }
        };
    },
    methods: {
        handleClose() {
            this.$emit("close");
        },
        submit() {
            this.$refs["course_form"].validate(valid => {
                if (valid) {
                    if (this.mode === MODE_CREATE) {
                        this.saveToDatabase();
                    } else {
                        this.updateToDatabase();
                    }
                } else {
                    return false;
                }
            });
        },
        saveToDatabase() {
            this.modal_loading = true;
            axios
                .post(URL_PATH, this.form)
                .then(response => {
                    this.modal_loading = false;
                    this.$emit("added", response.data);
                    this.$emit("close");
                    this.$message({
                        message: "Course successfully added.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;

                    this.$message({
                        message: "Sorry, there is an error adding the course.",
                        type: "error"
                    });
                });
        },
        updateToDatabase() {
            console.log('updateToDatabase')
            this.modal_loading = true;
            axios
                .patch(URL_PATH + "/" + this.form.id, this.form)
                .then(response => {
                    this.modal_loading = false;

                    this.$emit("updated");
                    this.$emit("close");

                    this.$message({
                        message: "Course successfully updated.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;

                    this.$message({
                        message: "Sorry, there is an error updating the course.",
                        type: "error"
                    });
                });
        }
    },
    computed: {
        form() {
            return this.p_course;
        },
        departments() {
            return this.p_departments;
        },
        title() {
            return this.mode === MODE_CREATE
                ? "Add New Course"
                : "Update Course";
        },
        action() {
            return this.mode === MODE_CREATE ? "Add" : "Update";
        },
        icon() {
            return this.mode === MODE_CREATE
                ? "el-icon-plus"
                : "el-icon-refresh";
        }
    }
};
</script>

<style>
</style>
