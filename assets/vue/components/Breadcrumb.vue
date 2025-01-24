<template>
  <div
    v-show="itemList.length > 0"
    class="app-breadcrumb"
  >
    <Breadcrumb
      :model="itemList"
      unstyled
    >
      <template #item="{ item, props }">
        <router-link
          v-if="item.route"
          v-slot="{ href, navigate }"
          :to="item.route"
          custom
        >
          <a
            :href="href"
            v-bind="props.action"
            @click="navigate"
          >
            <span :class="[item.icon]" />
            <span
              v-if="item.label"
              v-text="item.label"
            />
          </a>
        </router-link>
        <a
          v-else
          :href="item.url"
          v-bind="props.action"
        >
          <span>{{ item.label }}</span>
        </a>
      </template>

      <template #separator> / </template>
    </Breadcrumb>
    <div
      v-if="session"
      class="app-breadcrumb__session-title"
      v-text="session.title"
    />
  </div>
</template>

<script setup>
import { computed } from "vue"
import { useRoute } from "vue-router"
import { useI18n } from "vue-i18n"
import Breadcrumb from "primevue/breadcrumb"
import { useCidReqStore } from "../store/cidReq"
import { storeToRefs } from "pinia"

// eslint-disable-next-line no-undef
const componentProps = defineProps({
  layoutClass: {
    type: String,
    default: null,
  },
  legacy: {
    type: Array,
    default: () => [],
  },
})

const cidReqStore = useCidReqStore()
const route = useRoute()
const { t } = useI18n()

const { course, session } = storeToRefs(cidReqStore)

const itemList = computed(() => {
  const list = [
    "MyCourses",
    "MySessions",
    "MySessionsUpcoming",
    "MySessionsPast",
    "Home",
    "MessageList",
    "MessageNew",
    "MessageShow",
    "MessageCreate",
  ]

  const items = []

  if (route.name && route.name.includes("Page")) {
    items.push({
      label: t("Pages"),
      to: "/resources/pages",
    })
  }

  if (route.name && route.name.includes("Message")) {
    items.push({
      label: t("Messages"),
      //disabled: route.path === path || lastItem.path === route.path,
      to: "/resources/messages",
    })
  }

  if (list.includes(route.name)) {
    return items
  }

  if (course.value) {
    if (session.value) {
      items.push({
        label: t("My sessions"),
      })
    } else {
      items.push({
        label: t("My courses"),
      })
    }
  }

  if (componentProps.legacy.length > 0) {
    const mainUrl = window.location.href
    const mainPath = mainUrl.indexOf("main/")

    componentProps.legacy.forEach((item) => {
      let url = item.url.toString()
      let newUrl = url

      if (url.indexOf("main/") > 0) {
        newUrl = "/" + url.substring(mainPath, url.length)
      }

      if (newUrl === "/") {
        newUrl = "#"
      }

      items.push({
        label: item["name"],
        url: newUrl,
      })
    })
  } else {
    if (course.value) {
      items.push({
        label: course.value.title,
        route: { name: "CourseHome", params: { id: course.value.id }, query: route.query },
      })
    }
  }

  return items
})
</script>
