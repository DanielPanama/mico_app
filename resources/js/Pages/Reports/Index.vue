<template>
  <div>

    <h1 class="mb-8 text-3xl font-bold">Reports</h1>
    <pre>{{ group }}</pre>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
  },
  layout: Layout,
  props: {
    group: Object,
    activities: Object,
  },
}
</script>
