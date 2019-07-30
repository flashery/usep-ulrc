<template>
    <el-dialog
        :title="title"
        :visible.sync="show_bib_modal"
        :before-close="handleClose"
        width="35%"
        v-loading="modal_loading"
    >
        <el-form
            v-if="authenticated"
            :model="form"
            :rules="rules"
            ref="course_form"
            label-position="left"
            label-width="100px"
        >
            <div v-for="(marc_tag, index) in marc_tags" :key="index">
                <el-form-item v-if="marc_tag.show_as_default" :label="marc_tag.non_marc_tag">
                    <el-input v-model="form['marc_tags'][marc_tag.id]"></el-input>
                </el-form-item>
            </div>
        </el-form>
        <div v-if="authenticated" slot="footer" class="dialog-footer">
            <el-button type="default" :icon="icon" @click="submit()">{{action}}</el-button>
        </div>
    </el-dialog>
</template>

<script>
const URL_PATH = "/bib";
const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";
export default {
    props: ["mode", "show_bib_modal", "p_bib", "p_marc_tags"],
    data() {
        return {
            authenticated: window.Laravel.user.authenticated,
            modal_loading: false,
            uploading: false,
            bib_tags: [],
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
            this.formatData();

            if (this.mode === MODE_CREATE) {
                this.saveToDatabase();
            } else {
                this.updateToDatabase();
            }
        },
        // formatData() {
        //     // Format data to be save on the database
        //     Object.entries(this.form).forEach(entry => {
        //         let new_key = entry[0].split("-");
        //         let marc_tag_id = parseInt([new_key[1]]);

        //         // let str_val = new_key[0].toString();
        //         // let obj = {};
        //         // obj[str_val] = entry[1];
        //         this.bib_tags[new_key[1]] = { value: entry[1] };
        //         // this.bib_tags.push([marc_tag_id][{ value: entry[1] }]);
        //     });
        // },1
        formatData() {
            // Format data to be save on the database
            // this.form.marc_tags.forEach(
            Object.entries(this.form.marc_tags).forEach(entry => {
                this.bib_tags[entry[0]] = { value: entry[1] };
            });
            console.log(this.bib_tags);
        },

        saveToDatabase() {
            this.modal_loading = true;
            axios
                .post(URL_PATH, { bib_tags: this.bib_tags })
                .then(response => {
                    this.modal_loading = false;
                    this.$emit("added", response.data);
                    this.$emit("close");
                    this.$message({
                        message: "Bib successfully added.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;

                    this.$message({
                        message: "Sorry, there is an error adding the bib.",
                        type: "error"
                    });
                });
        },
        updateToDatabase() {
            console.log("updateToDatabase");
            this.modal_loading = true;
            axios
                .patch(URL_PATH + "/" + this.form.id, {
                    bib_tags: this.bib_tags
                })
                .then(response => {
                    this.modal_loading = false;

                    this.$emit("updated");
                    this.$emit("close");

                    this.$message({
                        message: "Bib successfully updated.",
                        type: "success"
                    });
                })
                .catch(err => {
                    this.modal_loading = false;

                    this.$message({
                        message: "Sorry, there is an error updating the bib.",
                        type: "error"
                    });
                });
        }
    },
    computed: {
        form() {
            return this.p_bib;
        },
        marc_tags() {
            return this.p_marc_tags;
        },
        title() {
            return this.mode === MODE_CREATE ? "Add New Bib" : "Update Bib";
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
