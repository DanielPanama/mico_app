<template>
  <div>

    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/activities">Activities</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.first_name }} {{ form.last_name }}
    </h1>
    <trashed-message v-if="activity.deleted_at" class="mb-6" @restore="restore"> This activity has been deleted.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!activity.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button"
            @click="destroy">Delete Activity</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update
            Activity</loading-button>
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'


export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    TextArea,
  },
  layout: Layout,
  props: {
    activity: Object,
    groups: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        subjects: this.activity.subjects,
        description: this.activity.description,
        start_date: this.activity.start_date,
        end_date: this.activity.end_date,
        group_id: this.activity.group_id,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/activities/${this.activity.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this activity?')) {
        this.$inertia.delete(`/activities/${this.activity.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this activity?')) {
        this.$inertia.put(`/activities/${this.activity.id}/restore`)
      }
    },
  },
}
</script>
