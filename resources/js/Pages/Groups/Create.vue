<template>
  <div>

    <Head title="Create Group" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/groups">Grupos</Link>
      <span class="text-indigo-400 font-medium">/</span> Crear
    </h1>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden mx-auto">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 w-full" label="Name"
            placeholder="Indique Grupo, Grado y Carrera" />
          <text-input v-model="form.period" :error="form.errors.period" class="pb-8 w-full" label="Ciclo/Perido Escolar"
            placeholder="Ejemplo 2023-2024/1" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Crear Grupo</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        period: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/groups')
    },
  },
}
</script>
