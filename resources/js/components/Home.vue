<template>
    <div v-loading="loading" class="row justify-content-center">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to the</h1>
            <h1 class="display-4">University Learning Resource Center</h1>
            <hr class="my-4" />
            <p class="lead">Please click on one of the program that you are interested in below.</p>
        </div>
        <!-- <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h1>General</h1>
                    <h2>Education Subjects</h2>
                </div>
            </div>
        </div>-->
        <div v-for="(department, index) in departments" :key="index" class="col-md-3">
            <div class="card">
                <a href="/search">
                    <img
                        :src="department.image | noImage "
                        class="card-img-top"
                        alt="College of Technology"
                    />
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            departments: []
        };
    },
    mounted() {
        this.prepareData();
    },
    methods: {
        prepareData() {
            this.loading = true;

            axios
                .get("department")
                .then(response => {
                    this.loading = false;

                    this.departments = response.data;
                })
                .catch(err => {
                    this.loading = false;

                    this.$message({
                        message:
                            "Sorry, there is an error getting all departments data.",
                        type: "error"
                    });
                });
        }
    }
};
</script>

<style>
</style>
