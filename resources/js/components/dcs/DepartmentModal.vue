<template>
    <el-dialog
        :title="title"
        :visible.sync="department_dlg_show"
        :before-close="handleClose"
        width="35%"
        v-loading="modal_loading"
    >
        <el-row>
            <el-col :span="24">
                <el-form
                    :model="form"
                    :rules="rules"
                    ref="department_form"
                    label-position="left"
                    label-width="150px"
                >
                    <el-form-item prop="image">
                        <div class="avatar-uploader">
                            <div
                                v-loading="uploading"
                                tabindex="0"
                                class="el-upload el-upload--text"
                            >
                                <img :src="form.image | noImage" class="avatar" @click="openFile()">
                                <input
                                    type="file"
                                    ref="file"
                                    @change="uploadImage"
                                    class="el-upload__input"
                                >
                            </div>
                        </div>
                        <!-- <input type="file" ref="file" class="el-input__inner" @change="uploadImage"> -->
                    </el-form-item>
                    <el-form-item label="Department Name" prop="name">
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>

                    <el-form-item label="Description">
                        <el-input type="textarea" v-model="form.description"></el-input>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
        <div slot="footer" class="dialog-footer">
            <el-button type="default" :icon="icon" @click="submit()">{{action}}</el-button>
        </div>
    </el-dialog>
</template>
<script>
import * as filestack from "filestack-js";
const client = filestack.init("AGoFHpkdRdmvL7h9lFFcvz");
const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";
export default {
    props: ["department_dlg_show", "p_department", "mode"],
    data() {
        return {
            modal_loading: false,
            uploading: false,
            rules: {
                name: [
                    {
                        required: true,
                        message: "Please input a department name",
                        trigger: "blur"
                    },
                    {
                        min: 1,
                        max: 255,
                        message: "Name should be atleast to 1 to 255 letters",
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
            this.$refs["department_form"].validate(valid => {
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
        openFile() {
            this.$refs.file.click();
        },
        uploadImage(event) {
            this.uploading = true;
            const files = event.target.files;
            const file = files.item(0);
            this.form.image = "";

            client
                .upload(file)
                .then(res => {
                    this.uploading = false;
                    this.form.image = res.url;
                })
                .catch(err => {
                    this.uploading = false;

                    this.$message({
                        message:
                            "Sorry, there is an error uploading to third party API.",
                        type: "error"
                    });
                });
        },
        saveToDatabase() {
            this.modal_loading = true;
            axios
                .post("/department", this.form)
                .then(response => {
                    this.modal_loading = false;
                    this.$emit("added", response.data);
                    this.$emit("close");
                    this.$message({
                        message: "Department successfully added.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;

                    this.$message({
                        message:
                            "Sorry, there is an error adding the department.",
                        type: "error"
                    });
                });
        },
        updateToDatabase() {
            this.modal_loading = true;
            axios
                .patch("/department/" + this.form.id, this.form)
                .then(response => {
                    this.modal_loading = false;
                    this.$emit("updated");
                    this.$emit("close");
                    this.$message({
                        message: "Department successfully updated.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;

                    this.$message({
                        message:
                            "Sorry, there is an error updating the department.",
                        type: "error"
                    });
                });
        },
        deleteToDatabase() {
            this.modal_loading = true;
            axios
                .patch("/department/" + this.form.id)
                .then(response => {
                    this.modal_loading = false;
                    this.$emit("deleted");
                    this.$emit("close");
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
        }
    },
    computed: {
        form() {
            return this.p_department;
            // let department = {};

            // department.name = this.p_department.name;
            // department.description = this.p_department.description;

            // if(this.mode === MODE_CREATE) department.id = this.p_department.id;

            // if (this.p_department.image) {
            //     department.image = this.p_department.image;
            // } else {
            //     department.image = "/images/default.jpg";
            // }

            // return department;
        },
        title() {
            return this.mode === MODE_CREATE
                ? "Add new department"
                : "Update department";
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
.avatar-uploader .el-upload {
    cursor: pointer;
    position: relative;
    overflow: hidden;
    display: block;
    margin: 0 auto;
    box-shadow: 0 0 5px 0px #000000;
    border-radius: 10px;
    padding: 20px;
}
.avatar-uploader .el-upload:hover {
    border-color: #409eff;
}
.avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
}
.avatar {
    width: 178px;
    height: 178px;
    display: block;
    cursor: pointer;
    margin: 0 auto;
}
.avatar-uploader {
    width: auto;
}
</style>
