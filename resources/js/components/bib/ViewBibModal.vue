
        <template>
    <el-dialog
        :title="title"
        :visible.sync="show_view_bib_modal"
        :before-close="handleClose"
        width="55%"
    >
        <h5>Bib details</h5>
        <!-- For view only -->

        <table>
            <tbody>
                <tr v-for="(marc_tag, index) in bib.marc_tags" :key="index">
                    <td v-if="marc_tag.value && marc_tag.value !== ''">
                        <strong style="margin-right: 20px;">{{marc_tag.non_marc_tag}}</strong>
                    </td>
                    <td v-if="marc_tag.value && marc_tag.value !== ''">{{marc_tag.value}}</td>
                </tr>
            </tbody>
        </table>
        <el-form>
            <el-form-item label="No. of  volumes:">
                <span>{{ bib.volumes.length}}</span>
            </el-form-item>
        </el-form>
        <h5>Subjects</h5>

        <el-form v-for="(subject, index) in bib.subjects" :key="index">
            <el-form-item label="Subject code:">
                <span>{{ subject.code}}</span>
            </el-form-item>
            <el-form-item label="Subject description:">
                <span>{{ subject.name}}</span>
            </el-form-item>
        </el-form>
    </el-dialog>
</template>

<script>
const URL_PATH = "/bib";
const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";

export default {
    props: ["mode", "show_view_bib_modal", "p_bib", "p_marc_tags"],
    methods: {
        handleClose() {
            this.$emit("close");
        }
    },
    computed: {
        bib() {
            return this.p_bib;
        },
        marc_tags() {
            return this.p_marc_tags;
        },

        title() {
            return "View Bib";
        }
    }
};
</script>

<style>
table {
    margin-bottom: 20px;
    padding: 10px;
}
form.el-form {
    padding: 10px;
}
label.el-form-item__label {
    font-weight: bold;
    margin: 0;
}
td {
    padding: 7px;
}
label.el-form-item__label,
.el-form-item__content {
    line-height: 35px;
}
</style>
