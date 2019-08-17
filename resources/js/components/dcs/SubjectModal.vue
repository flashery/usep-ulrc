<template>
    <el-dialog
        :title="title"
        :visible.sync="subject_dlg_show"
        :before-close="handleClose"
        width="35%"
        v-loading="modal_loading"
    >
        <el-form
            :model="form"
            :rules="rules"
            ref="subject_form"
            label-position="left"
            label-width="150px"
        >
            <el-form-item v-if="mode === 'CREATE'" label="Department" prop="department">
                <el-select
                    v-model="form.department"
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
            <el-form-item v-if="mode === 'CREATE'" label="Course" prop="course">
                <el-select v-model="form.course" placeholder="Select courses">
                    <el-option
                        v-for="(course, index) in form.department.courses"
                        :key="index"
                        :label="course.name"
                        :value="course"
                    ></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Subject Code" prop="code">
                <el-input v-model="form.code" :disabled="mode === 'UPDATE'"></el-input>
            </el-form-item>
            <el-form-item label="Subject Name" prop="name">
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
const URL_PATH = "/subject";
const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";
export default {
    props: ["mode", "subject_dlg_show", "p_departments", "p_subject"],
    data() {
        return {
            modal_loading: false,
            uploading: false,
            rules: {
                department: [
                    {
                        required: true,
                        message: "Please select a department",
                        trigger: "change"
                    }
                ],
                course: [
                    {
                        required: true,
                        message: "Please select a course",
                        trigger: "change"
                    }
                ],
                code: [
                    {
                        required: true,
                        message: "Please input a subject code",
                        trigger: "blur"
                    },
                    {
                        min: 3,
                        max: 30,
                        message: "Code should be atleast to 3 to 6 letters",
                        trigger: "blur"
                    }
                ],
                name: [
                    {
                        required: true,
                        message: "Please input a subject name",
                        trigger: "blur"
                    },
                    {
                        min: 1,
                        max: 255,
                        message: "Name should be atleast to 1 to 255 letters",
                        trigger: "blur"
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
            this.$refs["subject_form"].validate(valid => {
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
                        message: "Subject successfully added.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;

                    this.$message({
                        message: "Sorry, there is an error adding the subject.",
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
                        message: "Subject successfully updated.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;

                    this.$message({
                        message: "Sorry, there is an error updating the subject.",
                        type: "error"
                    });
                });
        }
    },
    computed: {
        form() {
            return this.p_subject;
        },
        departments() {
            return this.p_departments;
        },
       
        title() {
            return this.mode === MODE_CREATE
                ? "Add New Subject"
                : "Update Subject";
        },
        action() {
            return this.mode === MODE_CREATE ? "Add" : "Update";
        },
        icon() {
            return this.mode === MODE_CREATE
                ? "el-icon-plus"
                : "el-icon-refresh";
        }
    },
    watch: {
        courses() {
            return this.form.department.courses;
        },
    }
};
</script>

<style>
</style>
