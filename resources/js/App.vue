<template>
    <div>
        <router-view />
        <ToastContainer />
    </div>
</template>

<script>
import 'leaflet/dist/leaflet.css';
import ToastContainer from './components/ToastContainer.vue';

export default {
    name: 'App',
    components: {
        ToastContainer
    }
};
</script>
