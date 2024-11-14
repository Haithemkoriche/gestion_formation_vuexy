<script setup>
import navItems from '@/navigation/vertical'; // Import default navigation items
import { themeConfig } from '@themeConfig';
import { onMounted, ref } from 'vue';

// Components
import Footer from '@/layouts/components/Footer.vue';
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue';
import UserProfile from '@/layouts/components/UserProfile.vue';
import NavBarI18n from '@core/components/I18n.vue';

// @layouts plugin
import { VerticalNavLayout } from '@layouts';

// Reactive navigation items that will include fetched services
const dynamicNavItems = ref([...navItems])

// Fetch services when the component mounts
onMounted(async () => {
  try {
    // Replace '/api/services' with your actual endpoint
    const response = await fetch('/api/services')
    const services = await response.json()

    // Map services to match nav item format
    const fetchedServiceItems = services.map(service => ({
      title: service.name || 'Default Service',
      to: { path: `/services/${service.id}` }, // Use `path` instead of a dynamic `name`
      icon: { icon: service.icon || 'tabler-settings' },
    }))


    // Add fetched items to navigation
    dynamicNavItems.value.push(...fetchedServiceItems)
  } catch (error) {
    console.error('Error fetching services:', error)
  }
})
</script>

<template>
  <VerticalNavLayout :nav-items="dynamicNavItems">
    <!-- Navbar section -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <IconBtn id="vertical-nav-toggle-btn" class="ms-n3 d-lg-none" @click="toggleVerticalOverlayNavActive(true)">
          <VIcon size="26" icon="tabler-menu-2" />
        </IconBtn>
        <NavbarThemeSwitcher />
        <VSpacer />
        <NavBarI18n v-if="themeConfig.app.i18n.enable && themeConfig.app.i18n.langConfig?.length"
          :languages="themeConfig.app.i18n.langConfig" />
        <UserProfile />
      </div>
    </template>

    <!-- Pages and footer slots -->
    <slot />
    <template #footer>
      <Footer />
    </template>
  </VerticalNavLayout>
</template>
