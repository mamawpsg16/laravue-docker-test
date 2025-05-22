<template>
  <div>
    <router-link :to="{name: 'dashboard'}" class="btn  p-0">Dashboard</router-link> |
    <router-link :to="{name: 'async'}" class="btn  p-0">Async Feature</router-link> |
    <router-link :to="{name: 'slots'}" class="btn  p-0">Slots</router-link> |
    <router-link :to="{name: 'next-tick'}" class="btn  p-0">Next Tick</router-link> |
    <router-link :to="{name: 'global-api'}" class="btn  p-0">Global API</router-link> |
    <router-link :to="{name: 'composables'}" class="btn  p-0">Composables</router-link> |
    <router-link :to="{name: 'teleport'}" class="btn  p-0">Teleport</router-link> |
    <router-link :to="{name: 'reactivity'}" class="btn  p-0">Reactivity</router-link> |
    <router-link :to="{name: 'generic-and-proptyping'}" class="btn  p-0">Generic Component & Prop Typing</router-link>

    <div class="container mt-5">
      <div class="d-flex justify-content-center">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>