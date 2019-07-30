
        <template>
    <el-dialog
        :title="title"
        :visible.sync="show_view_bib_modal"
        :before-close="handleClose"
        width="35%"
    >
        <!-- For view only -->
        <table class="table">
            <tbody>
                <tr v-for="(marc_tag, index) in marc_tags" :key="index">
                    <td>{{marc_tag.non_marc_tag}}</td>
                    <td>{{bib['marc_tags'][marc_tag.id]}}</td>
                </tr>
            </tbody>
        </table>
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
</style>
