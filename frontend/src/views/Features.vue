<template>
  <div>
    <!-- Navigation Buttons -->
    <div class="container mt-4">
      <div class="btn-group flex-wrap w-100" role="group" aria-label="Feature Navigation">
        <router-link :to="{ name: 'async' }" class="btn btn-outline-primary">Async Feature</router-link>
        <router-link :to="{ name: 'slots' }" class="btn btn-outline-primary">Slots</router-link>
        <router-link :to="{ name: 'next-tick' }" class="btn btn-outline-primary">Next Tick</router-link>
        <router-link :to="{ name: 'global-api' }" class="btn btn-outline-primary">Global API</router-link>
        <router-link :to="{ name: 'composables' }" class="btn btn-outline-primary">Composables</router-link>
        <router-link :to="{ name: 'teleport' }" class="btn btn-outline-primary">Teleport</router-link>
        <router-link :to="{ name: 'reactivity' }" class="btn btn-outline-primary">Reactivity</router-link>
        <router-link :to="{ name: 'generic-and-proptyping' }" class="btn btn-outline-primary">Generic Component & Prop Typing</router-link>
      </div>
    </div>

    <!-- Content Area -->
    <div class="container mt-5">
      <div class="card p-4 shadow-sm">
        <router-view />
      </div>
    </div>
  </div>
</template>
