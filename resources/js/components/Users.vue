<template>
    <div class="inner-cont">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">List of Users</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <el-button-group>
                    <el-button
                        type="primary"
                        icon="el-icon-plus"
                        @click="addUser"
                        :disabled="!authenticated_user.can_edit"
                    >New User</el-button>
                </el-button-group>
            </div>
        </div>
        <h4 class="mb-3">Users</h4>
        <table class="table" v-loading="loading">
            <thead>
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in users" :key="index">
                    <td>
                        <img :src="user.image | noImage" class="avatar" />
                    </td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td style="width: 111px;">
                        <el-button-group>
                            <el-button
                                :disabled="!authenticated_user.can_edit"
                                size="mini"
                                type="primary"
                                icon="el-icon-edit"
                                @click="editUser(user)"
                            ></el-button>
                            <el-button
                                :disabled="!authenticated_user.can_edit"
                                size="mini"
                                type="danger"
                                icon="el-icon-delete"
                                @click="deleteUser(user)"
                            ></el-button>
                        </el-button-group>
                    </td>
                </tr>
            </tbody>
        </table>
        <user-modal
            v-if="authenticated_user.can_edit"
            :mode="mode"
            :user="user"
            :show_user_modal="show_user_modal"
            @added="added"
            @close="show_user_modal =false"
        ></user-modal>
    </div>
</template>
<script>
import UserModal from "./users/UserModal";
export default {
    props: ["datas"],
    components: { UserModal },
    data() {
        return {
            authenticated_user: window.Laravel.user,
            mode: "CREATE",
            loading: false,
            users: this.datas,
            show_user_modal: false,
            user: {
                name: "",
                email: "",
                can_edit: false,
                password1: "",
                password2: ""
            }
        };
    },
    methods: {
        addUser() {
            this.mode = "CREATE";
            this.user = {
                name: "",
                email: "",
                can_edit: false,
                password1: "",
                password2: ""
            };
            this.show_user_modal = true;
        },
        editUser(user) {
            this.mode = "UPDATE";
            this.user = user;
            this.show_user_modal = true;
        },
        added(user) {
            this.users.push(user);
            this.show_user_modal = false;
        },
        deleteUser(user) {
            this.users.splice(this.users.indexOf(user), 1);
        }
    }
};
</script>