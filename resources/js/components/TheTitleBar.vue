<script setup>
import { ref } from 'vue';

const menuOpen = ref(false);

const props = defineProps({
    tabs: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const emit = defineEmits(['switch-tab']);

function switchTab (tab) {
    emit('switch-tab', tab);
    menuOpen.value = false;
}
</script>

<template>
  <div class="title-bar" :class="{ 'menu-opened': menuOpen }">
    <div class="title-bar-header">
      <div class="burger-container" @click="menuOpen = !menuOpen">
        <div id="burger">
          <div class="bar topBar"></div>
          <div class="bar btmBar"></div>
        </div>
      </div>
      
      <div class="title-bar-text">
        <slot name="text"></slot>
      </div>
      
      <div v-if="$slots.controls" class="title-bar-controls">
        <slot name="controls"></slot>
      </div>
    </div>
    
    <div class="menu-container" v-if="menuOpen">
      <ul class="menu">
        <li
            v-for="tab in tabs"
            :key="tab.id"
            class="menu-item"
            @click="() => switchTab(tab.id)"
        >
            <a href="#">{{ tab.label }}</a>
        </li>
      </ul>
    </div>
  </div>
</template>

<style>
.title-bar {
  font-size: 1rem;
}

@media (max-width: 765px) {
.title-bar {
    width: 100%;
    overflow: hidden;
    transition: all 0.5s ease-out, background 1s ease-out;
    transition-delay: 0.2s;
    z-index: 100;
    position: fixed;
    top: 0;
    left: 0;
    height: 2rem;
    display: flex;
    flex-direction: column;
    justify-content: start;
}
.title-bar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 2rem;
  width: 100%;
  z-index: 101;
  position: relative;
}

.title-bar .burger-container {
  position: relative;
  display: inline-block;
  height: 2rem;
  width: 3rem;
  cursor: pointer;
  transform: rotate(0deg);
  transition: all 0.3s cubic-bezier(0.4, 0.01, 0.165, 0.99);
  user-select: none;
  -webkit-tap-highlighcentert-color: transparent;
}

.title-bar .burger-container #burger {
  width: 18px;
  height: 8px;
  position: relative;
  display: block;
  margin: -4px auto 0;
  top: 50%;
}

.title-bar .burger-container #burger .bar {
  width: 100%;
  height: 1px;
  display: block;
  position: relative;
  background: #FFF;
  transition: all 0.3s cubic-bezier(0.4, 0.01, 0.165, 0.99);
  transition-delay: 0s;
}

.title-bar .burger-container #burger .bar.topBar {
  transform: translateY(0px) rotate(0deg);
}

.title-bar .burger-container #burger .bar.btmBar {
  transform: translateY(6px) rotate(0deg);
}

.title-bar .title-bar-text {
  flex-grow: 1;
  text-align: center;
}

.title-bar .title-bar-controls {
  margin-right: 10px;
}

.menu-container {
  width: 100%;
  overflow-y: auto;
}

.title-bar ul.menu {
  position: relative;
  display: block;
  padding: 20px 48px 0;
  list-style: none;
  margin-top: 0;
}

.title-bar ul.menu li.menu-item {
  border-bottom: 1px solid #333;
  margin-top: 5px;
  transform: scale(1.15) translateY(-30px);
  opacity: 0;
  transition: transform 0.5s cubic-bezier(0.4, 0.01, 0.165, 0.99), opacity 0.6s cubic-bezier(0.4, 0.01, 0.165, 0.99);
}

.title-bar ul.menu li.menu-item:nth-child(1) {
  transition-delay: 0.49s;
}

.title-bar ul.menu li.menu-item:nth-child(2) {
  transition-delay: 0.42s;
}

.title-bar ul.menu li.menu-item:nth-child(3) {
  transition-delay: 0.35s;
}

.title-bar.menu-opened {
  height: 100vh;
  transition: all 0.3s ease-in, background 0.5s ease-in;
  transition-delay: 0.25s;
  background-color: rgba(0, 0, 0, 0.9);
}

.title-bar.menu-opened .burger-container {
  transform: rotate(90deg);
}

.title-bar.menu-opened #burger .bar {
  transition: all 0.4s cubic-bezier(0.4, 0.01, 0.165, 0.99);
  transition-delay: 0.2s;
}

.title-bar.menu-opened #burger .bar.topBar {
  transform: translateY(4px) rotate(45deg);
}

.title-bar.menu-opened #burger .bar.btmBar {
  transform: translateY(3px) rotate(-45deg);
}

.title-bar.menu-opened ul.menu {
  display: block;
}

.title-bar.menu-opened ul.menu li.menu-item {
  transform: scale(1) translateY(0px);
  opacity: 1;
}

.title-bar.menu-opened ul.menu li.menu-item:nth-child(1) {
  transition-delay: 0.49s;
}

.title-bar.menu-opened ul.menu li.menu-item:nth-child(2) {
  transition-delay: 0.42s;
}

.title-bar.menu-opened ul.menu li.menu-item:nth-child(3) {
  transition-delay: 0.35s;
}

.title-bar ul.menu li.menu-item a {
  display: block;
  position: relative;
  color: #FFF;
  font-weight: 100;
  text-decoration: none;
  font-size: 22px;
  line-height: 2.35;
  font-weight: 200;
  width: 100%;
}
}
@media (min-width: 765px) {
  .title-bar {
    height: 2rem;
  }
  .title-bar-header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    height: 2rem;
    align-items: center;
  }
}
</style>