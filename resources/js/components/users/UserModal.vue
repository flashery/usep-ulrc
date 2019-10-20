<template>
    <el-dialog
        :title="title + ' User'"
        :visible.sync="show_user_modal"
        :before-close="handleClose"
        width="60%"
    >
        <el-form
            v-loading="modal_loading"
            :model="form"
            :rules="rules"
            ref="user_form"
            label-position="left"
            label-width="200px"
        >
            <el-form-item label="Name" prop="name">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="Email" prop="email">
                <el-input v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="Password" prop="password">
                <el-input type="password" v-model="form.password"></el-input>
            </el-form-item>
            <el-form-item label="Can Edit">
                <el-checkbox v-model="form.can_edit"></el-checkbox>
            </el-form-item>
            <el-form-item label="Confirm Password" prop="password_confirmation">
                <el-input type="password" v-model="form.password_confirmation"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button type="default" :icon="icon" @click="submit()">{{action}}</el-button>
        </div>
    </el-dialog>
</template>
<script>
export default {
    props: ["user", "show_user_modal", "mode"],
    data() {
        var validatePass = (rule, value, callback) => {
            if (value === "") {
                callback(new Error("Please input the password"));
            } else {
                if (this.form.password_confirmation !== "") {
                    this.$refs["user_form"].validateField(
                        "password_confirmation"
                    );
                }
                callback();
            }
        };
        var validatePass2 = (rule, value, callback) => {
            if (value === "") {
                callback(new Error("Please input the password again"));
            } else if (value !== this.form.password) {
                callback(new Error("Two inputs don't match!"));
            } else {
                callback();
            }
        };
        return {
            modal_loading: false,
            rules: {
                name: [
                    {
                        required: true,
                        message: "Please input name",
                        trigger: "blur"
                    }
                ],
                email: [
                    {
                        required: true,
                        message: "Please input a email",
                        trigger: "blur"
                    }
                ],
                password: [
                    { required: true, validator: validatePass, trigger: "blur" }
                ],
                password_confirmation: [
                    {
                        required: true,
                        validator: validatePass2,
                        trigger: "blur"
                    }
                ]
            }
        };
    },
    methods: {
        submit() {
            this.$refs["user_form"].validate(valid => {
                if (valid) {
                    if (this.mode === "CREATE") {
                        this.createUser();
                    } else {
                        this.updateUser();
                    }
                } else {
                    return false;
                }
            });
        },
        handleClose() {
            this.$emit("close");
        },
        createUser() {
            axios
                .post("/user", this.form)
                .then(res => {
                    this.$emit("added", res.data);
                })
                .catch(err => {
                    console.log(err);
                });
        },
        updateUser() {
            axios
                .patch("/user/" + this.form.id, this.form)
                .then(res => {
                    this.$emit('close');                    
                })
                .catch(err => {
                    console.log(err);
                });
        }
    },
    computed: {
        title() {
            return this.mode === "CREATE" ? "New" : "UPDATE";
        },
        action() {
            return this.mode === "CREATE" ? "Add" : "Update";
        },
        icon() {
            return this.mode === "CREATE" ? "el-icon-plus" : "el-icon-refresh";
        },
        form() {
            return this.user;
        }
    }
};
</script>