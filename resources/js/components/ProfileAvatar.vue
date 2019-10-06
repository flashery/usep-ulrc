<template>
  <li :key="user.id" class="nav-item dropdown">
    <a
      id="navbarDropdown"
      class="nav-link"
      href="#"
      role="button"
      data-toggle="dropdown"
      :title="user.name"
      aria-haspopup="true"
      aria-expanded="false"
    >
      <img :src="user.image | noImage" class="avatar">
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
      <a class="dropdown-item" href="/search">Search</a>
      <a class="dropdown-item" href="/bib">Bibs</a>
      <a class="dropdown-item" href="/dcs">DCS</a>
      <a class="dropdown-item" href="/user">Users</a>
      <a class="dropdown-item" href="/reports">Reports</a>
      <a class="dropdown-item" href="/profile/">Profile</a>
      <a href="javascript:void(0)" class="dropdown-item text-warning" @click="logout()">Logout</a>
    </div>
  </li>
</template>

<script>
import Event from "../event.js";

export default {
  data() {
    return {
      csrf: window.Laravel.csrfToken,
      user: {},
      notifications: [],
      private_messages: 0,
      public_messages: 0
    };
  },
  created() {
    this.prepareData();
  },
  methods: {
    prepareData() {
      this.user = window.Laravel.user;
    },
    logout() {
      axios.post("/logout").then(() => {
        window.location = "/login";
      });
    }
  },
  computed: {
    // private_messages() {
    //     this.notifications.find(notification => {
    //         return notification.type === "App\\Notifications\\NewMessage";
    //     });
    // }
    // messages() {
    //     this.notifications.find(notification => {
    //         return (
    //             this.notification.type === "App\\Notifications\\NewMessage"
    //         );
    //     });
    // }
  }
};
</script>

<style>
</style>
