<template>
    <div id="search-area" class="inner-cont">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">Search</h1>
        </div>
        <div class="row">
            <div class="col-md-10">
                <input
                    id="search"
                    type="text"
                    name="search"
                    placeholder="Please enter the name of journal"
                    required="required"
                    autocomplete="off"
                    autofocus="on"
                    @keydown="showHistories()"
                    @focus="showHistories()"
                    class="form-control"
                    @keyup.enter="handleKeyPress"
                    v-model="keyword"
                />
                <!-- <i
                    class="el-icon-search el-input__icon"
                    style="position: absolute;top: 0;right: 30px;"
                    slot="suffix"
                />-->
                <ul v-if="show_histories" id="search-histories">
                    <li v-for="(history, index) in search_histories" :key="index">
                        <a
                            href="javascript:void(0)"
                            @click="search(history.keywords)"
                        >{{history.keywords}}</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-2">
                <el-select v-model="search_type">
                    <el-option label="Title" value="title"></el-option>
                    <el-option label="Subject" value="subject"></el-option>
                    <el-option label="Author" value="author"></el-option>
                </el-select>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3">Bibs search results</h4>
                <table class="table">
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
                            <td>{{getSpecificTag(bib.marc_tags,'Call Number')}}</td>
                            <td>
                                <a
                                    href="javascript:void(0)"
                                    @click="setUpBibData(bib)"
                                >{{getSpecificTag(bib.marc_tags,'Title')}}</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <bib-modal
            @added="bibAdded"
            @updated="bibUpdated"
            @close="show_bib_modal = false"
            :mode="mode"
            :p_marc_tags="marc_tags"
            :p_bib="current_bib"
            :show_bib_modal="show_bib_modal"
        ></bib-modal>
        <view-bib-modal
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
            authenticated: window.Laravel.user.authenticated,
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
                if (marc_tag.non_marc_tag === col) return true;
            });

            if (marc_tag.length === 0) return "";

            return marc_tag[0].pivot.value;
        },
        storeSearchhistory() {
            this.hideHistories();
            // Search immidiately if user is not authenticated
            if (this.authenticated) {
                axios
                    .post("/search-history", { keywords: this.keyword })
                    .then(response => {
                        this.search_histories = response.data;
                        this.search();
                    });
            } else {
                this.search();
            }
        },

        search(q) {
            this.hideHistories();

            if (q) this.keyword = q;

            // this.$Progress.start();
            axios
                .get(this.search_link + this.keyword)
                .then(response => {
                    this.bibs = response.data;
                    // this.$Progress.finish();
                })
                .catch(errors => {
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
            axios
                .get("/marc-tag")
                .then(response => {
                    console.log(bib.id);
                    this.marc_tags = response.data;
                    this.current_bib["id"] = bib.id;

                    bib.marc_tags.forEach(marc_tag => {
                        let tag = this.marc_tags.find(temp => {
                            return marc_tag.id === temp.id;
                        });

                        if (tag) {
                            this.marc_tags[
                                this.marc_tags.indexOf(tag)
                            ] = marc_tag;

                            this.current_bib["marc_tags"][marc_tag.id] =
                                marc_tag.pivot.value;
                        } else {
                            this.current_bib["marc_tags"][marc_tag.id] = "";
                        }
                    });

                    if (this.authenticated) {
                        this.editBib();
                    } else {
                        this.show_view_bib_modal = true;
                    }
                })
                .catch(err => {
                    console.log(err);

                    this.$message({
                        message:
                            "Sorry, there is an error getting the MARC tags.",
                        type: "error"
                    });
                });
        },
        editBib() {
            this.mode = MODE_UPDATE;
            this.show_bib_modal = true;
        }
    },
    mounted() {
        this.getSearchHistories();
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