<template>
  <div>

    <Head title="Create Activity" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/activities">Activities</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.subjects" :error="form.errors.subjects" class="pb-8 pr-6 w-full"
            label="Asignatura(s)" />
          <text-area v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full "
            label="Descripción" />
          <select-input v-model="form.group_id" :error="form.errors.group_id" class="pb-8 pr-6 w-full" label="Grupo">
            <option :value="null" />
            <option v-for="group in groups" :key="group.id" :value="group.id">{{ group.name }}</option>
          </select-input>
          <text-input v-model="form.start_date" type="date" :error="form.errors.start_date" class="pb-8 pr-6 w-full"
            label="Fecha de inicio" />
          <text-input v-model="form.end_date" type="date" :error="form.errors.end_date" class="pb-8 pr-6 w-full"
            label="Fecha de fin" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Activity</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import TextArea from '@/Shared/TextareaInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextArea,
  },
  layout: Layout,
  props: {
    groups: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        subjects: '',
        description: '',
        start_date: null,
        end_date: null,
        group_id: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/activities')
    },
  },
}
</script>
