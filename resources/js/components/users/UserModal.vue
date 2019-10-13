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
            <el-form-item label="Email" prop="name">
                <el-input v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="Password" prop="password">
                <el-input v-model="form.password"></el-input>
            </el-form-item>
            <el-form-item label="Confirm Password" prop="password_confirmation">
                <el-input v-model="form.password_confirmation"></el-input>
            </el-form-item>
        </el-form>
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
                if (this.form.password1 !== "") {
                    this.$refs.form.validateField("password1");
                }
                callback();
            }
        };
        var validatePass2 = (rule, value, callback) => {
            if (value === "") {
                callback(new Error("Please input the password again"));
            } else if (value !== this.form.password2) {
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
                        type: "array",
                        required: true,
                        message: "Please select at least one department",
                        trigger: "change"
                    }
                ],
                email: [
                    {
                        required: true,
                        message: "Please input a course name",
                        trigger: "blur"
                    },
                    {
                        min: 1,
                        max: 255,
                        message: "Name should be atleast to 1 to 255 letters",
                        trigger: "blur"
                    }
                ],
                password: [
                    {
                        required: true,
                        message: "Please select an image",
                        trigger: "change"
                    }
                ],
                 password_confirmation: [
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
        createUser() {
            axios.post('/register', this.form).then()
        }
    },
    computed: {
        title() {
            return this.mode === "CREATE" ? "New" : "UPDATE";
        },
        form() {
            return this.user;
        }
    }
};
</script>