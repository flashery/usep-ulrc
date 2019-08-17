<template>
    <el-dialog
        :title="title"
        :visible.sync="show_bib_modal"
        :before-close="handleClose"
        width="60%"
    >
        <el-form
            v-loading="modal_loading"
            v-if="authenticated"
            :model="form"
            :rules="rules"
            ref="course_form"
            label-position="left"
            label-width="200px"
        >
            <el-row :gutter="20">
                <el-col v-if="mode === 'CREATE'" :span="24">
                    <h5>Marc Tags</h5>

                    <el-form-item
                        v-for="(marc_tag, index) in marc_tags"
                        :key="index"
                        :label="getMarcTag(marc_tag.id)"
                    >
                        <el-row :gutter="10">
                            <el-col :span="18">
                                <el-input v-model="marc_tag.value"></el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-button-group>
                                    <el-button
                                        type="danger"
                                        size="mini"
                                        icon="el-icon-delete"
                                        @click="deleteMarcTag(marc_tag)"
                                    ></el-button>
                                    <el-button
                                        size="mini"
                                        type="primary"
                                        @click="cloneMarcTag(index,marc_tag)"
                                        icon="el-icon-plus"
                                    ></el-button>
                                </el-button-group>
                            </el-col>
                        </el-row>
                    </el-form-item>
                </el-col>
                <!-- UPDATE -->
                <el-col v-if="mode === 'UPDATE'" :span="24">
                    <h5>Marc Tags</h5>
                    <el-form-item
                        v-for="(marc_tag, index) in form.marc_tags"
                        :key="index"
                        :label="getMarcTag(marc_tag.pivot.marc_tag_id)"
                    >
                        <el-row :gutter="20">
                            <el-col :span="18">
                                <el-input v-model="marc_tag.pivot.value"></el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-button-group>
                                    <el-button
                                        type="danger"
                                        size="mini"
                                        icon="el-icon-delete"
                                        @click="deleteMarcTag(marc_tag)"
                                    ></el-button>
                                    <el-button
                                        size="mini"
                                        type="primary"
                                        @click="cloneMarcTag(index,marc_tag)"
                                        icon="el-icon-plus"
                                    ></el-button>
                                </el-button-group>
                            </el-col>
                        </el-row>
                    </el-form-item>
                </el-col>
            </el-row>
            <hr />
            <el-row :gutter="20">
                <el-col :span="24">
                    <h5>Volumes</h5>
                    <el-form-item
                        v-for="(volume, index) in form.volumes"
                        :key="volume.created_at+'-'+index"
                    >
                        <el-row :gutter="20">
                            <el-col :span="20">
                                <el-input v-model="volume.label"></el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-button
                                    type="danger"
                                    size="mini"
                                    icon="el-icon-delete"
                                    @click="deleteVolume(volume)"
                                ></el-button>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item>
                        <el-button
                            size="mini"
                            type="primary"
                            @click="form.volumes.push({})"
                        >Add volume</el-button>
                    </el-form-item>
                    <h5>Subjects</h5>
                    <el-form-item>
                        <el-select
                            multiple
                            filterable
                            remote
                            reserve-keyword
                            placeholder="Please enter the name of a subject"
                            :remote-method="searchSubject"
                            :v-loading="loading"
                            v-model="form.subjects"
                        >
                            <el-option
                                v-for="(subject,i) in subjects"
                                :key="i"
                                :label="subject.name"
                                :value="subject.id"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
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
    props: ["mode", "show_bib_modal", "p_bib", "p_marc_tags", "p_subjects"],
    data() {
        return {
            authenticated: window.Laravel.user.authenticated,
            modal_loading: false,
            loading: false,
            uploading: false,
            bib_subjects: [],
            bib_volumes: [],
            subjects: [],
            bib_tags: [],
            parsed: "",
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
        setSubjects(subjects) {
            this.subjects = subjects;
        },
        handleClose() {
            this.$emit("close");
        },
        getMarcTag(id) {
            let tag = this.marc_tags.find(temp => {
                return id === temp.id;
            });

            return tag.non_marc_tag;
        },
        submit() {
            if (this.mode === MODE_CREATE) {
                this.saveToDatabase();
            } else {
                this.updateToDatabase();
            }
        },
        deleteVolume(volume) {
            this.form.volumes.splice(this.form.volumes.indexOf(volume), 1);
        },
        deleteMarcTag(marc_tag) {
            this.form.marc_tags.splice(
                this.form.marc_tags.indexOf(marc_tag),
                1
            );
        },
        cloneMarcTag(index, marc_tag) {
            let data = JSON.parse(JSON.stringify(marc_tag));
            this.form.marc_tags.splice(index, 0, data);
        },

        searchSubject(q) {
            setTimeout(() => {
                axios
                    .get("/subject?q=" + q)
                    .then(response => {
                        this.subjects = response.data;
                    })
                    .catch(err => {
                        this.$message({
                            message:
                                "Sorry, there is an error getting the MARC tags.",
                            type: "error"
                        });
                    });
            }, 500);
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
                .patch(URL_PATH + "/" + this.form.id, this.form)
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
                    console.log(err);
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
