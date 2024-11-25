<template>
  <div>

    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/groups">Groups</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="group.deleted_at" class="mb-6" @restore="restore"> This group has been deleted.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full " label="Name" />
          <text-input v-model="form.period" :error="form.errors.period" class="pb-8 pr-6 w-full " label="Periodo" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!group.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button"
            @click="destroy">Delete Group</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Actualizar
            Grupo</loading-button>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">Activities</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">City</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Phone</th>
        </tr>
        <tr v-for="activity in group.activities" :key="activity.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/activities/${activity.id}/edit`">
            {{ activity.name }}
            <icon v-if="activity.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/activities/${activity.id}/edit`" tabindex="-1">
            {{ activity.city }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/activities/${activity.id}/edit`" tabindex="-1">
            {{ activity.phone }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/activities/${activity.id}/edit`" tabindex="-1">
            <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="group.activities.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No activities found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    group: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.group.name,
        period: this.group.period,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/groups/${this.group.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this group?')) {
        this.$inertia.delete(`/groups/${this.group.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this group?')) {
        this.$inertia.put(`/groups/${this.group.id}/restore`)
      }
    },
  },
}
</script>
