<template>
    <div id="search-area" class="inner-cont">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">Search</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input
                    id="search"
                    type="text"
                    name="search"
                    placeholder="Please enter the name of LIBRARY RESOURCES "
                    required="required"
                    autocomplete="off"
                    autofocus="on"
                    @keydown="showHistories()"
                    @focus="showHistories()"
                    class="form-control"
                    @keyup.enter="handleKeyPress"
                    v-model="keyword"
                />
                <ul v-if="show_histories" id="search-histories">
                    <li v-for="(history, index) in search_histories" :key="index">
                        <a
                            href="javascript:void(0)"
                            @click="search(history.keywords)"
                        >{{history.keywords}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3">Bibs search results</h4>
                <!-- <table v-loading="loading" class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Call Number</th>
                            <th scope="col">Heading</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(bib, index) in bibs" :key="index">
                            <th>{{index}}</th>
                            <td>{{getSpecificTag(bib.marc_tags,'082')}}</td>
                            <td>
                                <a
                                    href="javascript:void(0)"
                                    @click="setUpBibData(bib)"
                                >{{getSpecificTag(bib.marc_tags,'245')}}</a>
                            </td>
                        </tr>
                    </tbody>
                </table>-->
                <table class="table" v-loading="loading">
                    <thead>
                        <tr>
                            <th scope="col">Call Number</th>
                            <th scope="col">Book Titles</th>
                            <th scope="col">Author</th>
                            <th scope="col">Accession Number</th>
                            <th scope="col">Copyright</th>
                            <th scope="col">No. of Titles</th>
                            <th scope="col">No. of Volumes</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(bib, index) in  bibs" :key="index">
                            <td>{{getSpecificTag(bib.marc_tags,'082')}}</td>
                            <td>{{getSpecificTag(bib.marc_tags,'245')}}</td>
                            <td>{{getSpecificTag(bib.marc_tags,'100')}}</td>
                            <td>{{getSpecificTag(bib.marc_tags,'016')}}</td>
                            <td>{{getCopyrightDate(getSpecificTag(bib.marc_tags,'082'))}}</td>
                            <td>1</td>
                            <td>{{ bib.volumes.length}}</td>
                            <td style="width: 111px;">
                                <el-button-group>
                                    <el-button
                                        size="mini"
                                        type="primary"
                                        @click="updateBibViews(bib)"
                                    >{{ action }}</el-button>
                                </el-button-group>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <bib-modal
            ref="bib_modal"
            @added="bibAdded"
            @updated="bibUpdated"
            @close="show_bib_modal = false"
            :mode="mode"
            :p_marc_tags="marc_tags"
            :p_bib="current_bib"
            :show_bib_modal="show_bib_modal"
        ></bib-modal>
        <view-bib-modal
            ref="view_bib_modal"
            @close="show_view_bib_modal = false"
            :p_bib="current_bib"
            :p_marc_tags="marc_tags"
            :show_view_bib_modal="show_view_bib_modal"
        ></view-bib-modal>
    </div>
</template>

<script>
import BibModal from "./bib/BibModal";
import ViewBibModal from "./bib/ViewBibModal";
const MODE_UPDATE = "UPDATE";
export default {
    components: { BibModal, ViewBibModal },
    data() {
        return {
            authenticated_user: window.Laravel.user,
            loading: false,
            show_view_bib_modal: false,
            show_bib_modal: false,
            mode: MODE_UPDATE,
            show_histories: false,
            search_histories: [],
            search_link: "/search?keyword=",
            search_type: "title",
            bibs: [],
            marc_tags: [],
            current_bib: { id: 0, marc_tags: [] },
            links: [],
            keyword: "",
            test: "",
            timeout: null
        };
    },
    methods: {
        handleKeyPress(event) {
            if (event.key == "Enter") {
                this.storeSearchhistory();
            }
        },
        showHistories() {
            if (this.search_histories.length > 0) this.show_histories = true;
        },
        hideHistories() {
            this.show_histories = false;
        },
        handleSelect(item) {
            this.search(item);
        },
        getSpecificTag(marc_tags, col) {
            let marc_tag = {};
            marc_tag = marc_tags.filter(marc_tag => {
                if (marc_tag.marc_tag === col) return true;
            });

            if (marc_tag.length === 0) return "";

            return marc_tag[0].pivot.value;
        },
        storeSearchhistory() {
            this.loading = true;
            this.hideHistories();
            // Search immidiately if user is not authenticated
            if (
                this.authenticated_user.authenticated &&
                this.authenticated_user.can_edit
            ) {
                axios
                    .post("/search-history", { keywords: this.keyword })
                    .then(response => {
                        this.search_histories = response.data;
                        this.search();
                        this.loading = false;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            } else {
                this.search();
            }
        },

        search(q) {
            this.loading = true;
            this.hideHistories();

            if (q) this.keyword = q;

            // this.$Progress.start();
            axios
                .get(this.search_link + this.keyword)
                .then(response => {
                    this.bibs = response.data;
                    // this.$Progress.finish();
                    this.loading = false;
                })
                .catch(errors => {
                    console.log(errors);

                    // this.$Progress.fail();
                });
        },
        getSearchHistories() {
            if (!this.authenticated) return;
            axios
                .get("/search-history")
                .then(response => {
                    this.search_histories = response.data;
                })
                .catch();
        },
        // Bib Modal
        bibAdded() {
            this.search(this.keyword);
        },
        bibUpdated() {
            this.search(this.keyword);
        },
        setUpBibData(bib) {
            this.$refs["bib_modal"].setSubjects(bib.subjects);

            this.current_bib.id = bib.id;
            this.current_bib.volumes = bib.volumes;
            this.current_bib.views = bib.views;
            
            this.current_bib.marc_tags = bib.marc_tags.map(marc_tag => {
                let data = {
                    id: marc_tag.id,
                    marc_tag: marc_tag.marc_tag,
                    non_marc_tag: marc_tag.non_marc_tag,
                    show_as_default: marc_tag.show_as_default,
                    marc_tag: marc_tag.marc_tag,
                    marc_tag: marc_tag.marc_tag,
                    value: marc_tag.pivot.value
                };
                return data;
            });

            if (
                this.authenticated_user.authenticated &&
                this.authenticated_user.can_edit
            ) {
                this.current_bib.subjects = bib.subjects.map(subject => {
                    return subject.id;
                });

                this.editBib(bib);
            } else {
                this.current_bib.subjects = bib.subjects;
                this.show_view_bib_modal = true;
            }
        },
        updateBibViews(bib) {
            axios
                .patch("/bib/update-bib-view/" + bib.id)
                .then(res => {
                    bib.views += 1;
                    this.setUpBibData(bib);
                })
                .catch(err => {
                    console.log(err);
                });
        },
        editBib(bib) {
            this.mode = MODE_UPDATE;
            this.show_bib_modal = true;
        },
        getCopyrightDate(call_number) {
            return call_number.substr(call_number.length - 4, 4);
        },
        // MARC TAG
        getMarcTags() {
            axios
                .get("/marc-tag")
                .then(response => {
                    this.marc_tags = response.data;
                })
                .catch(err => {
                    this.$message({
                        message:
                            "Sorry, there is an error getting the MARC tags.",
                        type: "error"
                    });
                });
        }
    },
    mounted() {
        this.getSearchHistories();
        this.getMarcTags();
    },
    computed: {
        action() {
            if (
                this.authenticated_user.authenticated &&
                this.authenticated_user.can_edit
            ) {
                return "View / Edit bib";
            } else {
                return "More detail..";
            }
        }
    }
};
</script>
<style>
#search-histories {
    list-style: none;
    background-color: #ffffff;
    box-shadow: 0px 1px 2px #ccc;
    border-radius: 0px 0px 5px 5px;
    padding: 5px;
    width: 96.8%;
    margin: 0 auto;
    position: absolute;
    z-index: 2;
    top: 35px;
    border: 1px solid #ced4da;
}

#search-histories li a {
    display: block;
    color: #000000;
    background-color: white;
    padding: 10px;
    cursor: pointer;
}

#search-histories li {
    padding: 0;
    margin: 5px 0;
}
</style>