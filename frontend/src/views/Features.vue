<template>
  <div>
    <h3>User Info</h3>

    <!-- Using toRef: bind directly to a single reactive property -->
    <label>
      Name (toRef): 
      <input v-model="nameRef" />
    </label>

    <!-- Using toRefs: destructure multiple reactive props -->
    <label>
      Age (toRefs): 
      <input type="number" v-model.number="ageRef" />
    </label>

    <p>Original state object: {{ state }}</p>

    <!-- shallowRef example -->
    <button @click="updateShallowDetails">
      Update shallowRef details (won't trigger re-render)
    </button>
    <p>Shallow Details: {{ shallowDetails.value?.details?.age ?? 'N/A' }}</p>

    <p>Shallow Details (name): {{ shallowDetails.name ?? 'N/A' }}</p>
    <input type="text" v-model="shallowDetails.name" />
    <br>

    <!-- defineModel example -->
    <label>
      <FeatureInput v-model="modelValue" />
    </label>
  </div>
</template>

<script setup lang="ts">
import FeatureInput from '@/components/Form/FeatureInput.vue';
import { ref, reactive, shallowRef, toRef, toRefs, defineModel } from 'vue'

// Reactive object example
const state = reactive({
  name: 'Alice',
  age: 30
})

// Using toRef to get a reactive ref for the name property
const nameRef = toRef(state, 'name')

// Using toRefs to destructure reactive properties while keeping reactivity
const { age: ageRef } = toRefs(state)

// shallowRef example - shallow reactive wrapper
const shallowDetails = shallowRef({
  name: 'Bob',
  details: {
    age: 25
  }
})

// This will not trigger reactivity because it's a deep change inside shallowRef
function updateShallowDetails() {
  shallowDetails.value.details.age += 1
  console.log('Updated shallowRef details:', shallowDetails.value.details.age)
}

// defineModel example for v-model binding
const modelValue = ref<string>('')
</script>
