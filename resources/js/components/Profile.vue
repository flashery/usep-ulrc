<template>
    <div class="inner-cont">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <el-form
                :model="ruleForm"
                :rules="rules"
                ref="ruleForm"
                label-width="120px"
                class="demo-ruleForm"
                v-loading="updating"
            >
                <h1 class="h2">User Profile Informations</h1>
                <hr>
                <el-row>
                    <el-col :span="12">
                        <el-form-item label="Name" prop="name">
                            <el-input v-model="ruleForm.name"></el-input>
                        </el-form-item>

                        <el-form-item label="Email" prop="email">
                            <el-input v-model="ruleForm.email"></el-input>
                        </el-form-item>

                        <el-form-item label="Gender" prop="gender">
                            <el-select v-model="ruleForm.gender" placeholder="Gender">
                                <el-option
                                    v-for="(gender, index) in genders"
                                    :key="index"
                                    :label="gender.label"
                                    :value="gender.value"
                                ></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="Position" prop="position_id">
                            <el-select v-model="ruleForm.position_id" placeholder="Position">
                                <el-option
                                    v-for="(position, index) in positions"
                                    :key="index"
                                    :label="position.label"
                                    :value="position.value"
                                ></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="Address" prop="address">
                            <el-input v-model="ruleForm.address"></el-input>
                        </el-form-item>

                        <el-form-item>
                            <el-button type="primary" @click="submitForm('ruleForm')">Update</el-button>
                        </el-form-item>
                    </el-col>

                    <el-col :span="12">
                        <el-form-item prop="image">
                            <div class="avatar-uploader">
                                <div
                                    v-loading="uploading"
                                    tabindex="0"
                                    class="el-upload el-upload--text"
                                >
                                    <img
                                        :src="ruleForm.image | noImage"
                                        class="avatar"
                                        @click="openFile()"
                                    >
                                    <input
                                        type="file"
                                        ref="file"
                                        @change="uploadImage"
                                        class="el-upload__input"
                                    >
                                </div>
                            </div>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </div>
    </div>
</template>

<script>
import * as filestack from "filestack-js";
const client = filestack.init("AGoFHpkdRdmvL7h9lFFcvz");
export default {
    props: ["p_user"],
    data() {
        return {
            updating: false,
            uploading: false,
            genders: [
                { label: "Male", value: "Male" },
                { label: "Female", value: "Female" },
                { label: "Others", value: "Others" }
            ],
            positions: [
                { label: "Super Admin", value: 1 },
                { label: "Admin", value: 2 }
            ],
            ruleForm: {
                name: "",
                email: "",
                position: "",
                image: ""
            },
            rules: {
                name: [
                    {
                        required: true,
                        message: "Please input a name",
                        trigger: "blur"
                    },
                    {
                        min: 3,
                        max: 191,
                        message: "Length should be atleast 3 characters",
                        trigger: "blur"
                    }
                ],
                email: [
                    {
                        required: true,
                        message: "Please input an email",
                        trigger: "change"
                    },
                    {
                        min: 3,
                        max: 191,
                        message: "Length should be atleast 3 characters",
                        trigger: "blur"
                    }
                ],
                position_id: [
                    {
                        required: true,
                        message: "Please select a position",
                        trigger: "change"
                    }
                ],
                image: [
                    {
                        required: true,
                        message: "Please add your profile picture",
                        trigger: "change"
                    }
                ]
            }
        };
    },
    mounted() {
        this.ruleForm = this.p_user;
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate(valid => {
                if (valid) {
                    this.updateToDatabase();
                } else {
                    console.log("error submit!!");
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
            this.ruleForm.image = "";

            client
                .upload(file)
                .then(res => {
                    this.uploading = false;
                    this.ruleForm.image = res.url;
                })
                .catch(err => {
                    console.log(err);
                    this.uploading = false;

                    this.$message({
                        message:
                            "Sorry, there is an error uploading to third party API.",
                        type: "error"
                    });
                });
        },
        updateToDatabase() {
            this.updating = true;
            axios
                .patch("/user/" + this.ruleForm.id, this.ruleForm)
                .then(response => {
                    this.updating = false;
                    this.$message({
                        message: "Profile successfully updated.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.updating = false;

                    this.$message({
                        message:
                            "Sorry, there is an error updating your profile.",
                        type: "error"
                    });
                });
        }
    }
};
</script>

<style>
form.el-form.demo-ruleForm {
    width: 100%;
}
</style>
