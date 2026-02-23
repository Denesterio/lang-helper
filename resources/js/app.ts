import "xp.css/dist/XP.css";
import "../css/app.css";

import { createApp } from 'vue';
import UserHome from './components/UserHome.vue';
import UserAuth from "./components/UserAuth.vue";
 
createApp({})
  .component('UserHome', UserHome)
  .component('UserAuth', UserAuth)
  .mount('#app')
