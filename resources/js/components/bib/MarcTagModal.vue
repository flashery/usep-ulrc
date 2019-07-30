<template>
    <el-dialog
        :title="title"
        :visible.sync="show_marc_tag_modal"
        :before-close="handleClose"
        width="35%"
        v-loading="modal_loading"
    >
        <el-form
            :model="form"
            :rules="rules"
            ref="marc_tag_form"
            label-position="left"
            label-width="150px"
        >
            <el-form-item :class="{'server-err': errors.marc_tag}" label="MARC Tag" prop="marc_tag">
                <el-input v-model="form.marc_tag" maxlength="3"></el-input>
                <div
                    class="el-form-server__error"
                    v-show="errors.marc_tag"
                >{{ errors.marc_tag ? errors.marc_tag[0] : '' }}</div>
            </el-form-item>
            <el-form-item
                :class="{'server-err': errors.non_marc_tag}"
                label="NON-MARC Tag"
                prop="non_marc_tag"
            >
                <el-input v-model="form.non_marc_tag"></el-input>
                <div
                    class="el-form-server__error"
                    v-show="errors.non_marc_tag"
                >{{ errors.non_marc_tag ? errors.non_marc_tag[0] : '' }}</div>
            </el-form-item>
            <el-form-item label="Show as default" prop="show_as_default">
                <el-checkbox v-model="form.show_as_default"></el-checkbox>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button type="default" :icon="icon" @click="submit()">{{action}}</el-button>
        </div>
    </el-dialog>
</template>

<script>
const URL_PATH = "/marc-tag";
const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";
export default {
    props: ["mode", "show_marc_tag_modal", "p_current_marc_tag"],
    data() {
        return {
            modal_loading: false,
            uploading: false,
            rules: {
                marc_tag: [
                    {
                        required: true,
                        message: "Please input a MARC tag",
                        trigger: "blur"
                    }
                ],
                non_marc_tag: [
                    {
                        required: true,
                        message: "Please input NON-MARC tag value",
                        trigger: "blur"
                    },
                    {
                        min: 3,
                        message: "NON-MARC tag value should be atleast 3",
                        trigger: "blur"
                    }
                ]
            },
            errors: []
        };
    },
    methods: {
        handleClose() {
            this.$emit("close");
        },
        submit() {
            this.$refs["marc_tag_form"].validate(valid => {
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
                        message: "MARC tag successfully added.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;
                    console.log(err.response);
                    this.errors = err.response.data.errors;
                    // this.handleErrors(err.response.data.errors);
                    this.$message({
                        message: "Sorry, there is an error adding the MARC tag.",
                        type: "error"
                    });
                });
        },
        updateToDatabase() {
            
            this.modal_loading = true;
            axios
                .patch(URL_PATH + "/" + this.form.id, this.form)
                .then(response => {
                    this.modal_loading = false;

                    this.$emit("updated");
                    this.$emit("close");

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
        handleErrors(errors) {
            console.log();
            Object.keys(errors).forEach(key => {
                console.log(errors[key][0]);
            });
        }
    },
    computed: {
        form() {
            return this.p_current_marc_tag;
        },
        title() {
            return this.mode === MODE_CREATE
                ? "Add MARC tag"
                : "Update MARC tag";
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
