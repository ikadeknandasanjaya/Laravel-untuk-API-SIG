<template>
    <!-- This is a legacy layout component for backward compatibility -->
    <!-- New pages should use AppLayout instead -->
    <div class="min-h-screen bg-gray-50">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'Layout'
}
</script>

<style scoped>
/* Legacy layout styles */
</style>