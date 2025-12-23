import Welcome from './pages/WelcomeSimple.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import Dashboard from './pages/Dashboard.vue';
import MapPage from './pages/MapPage.vue';
import RuasJalanIndex from './pages/ruasjalan/IndexNew.vue';
import RuasJalanCreate from './pages/ruasjalan/CreateNew.vue';
import RuasJalanEdit from './pages/ruasjalan/Edit.vue';
import RuasJalanShow from './pages/ruasjalan/Show.vue';
import Settings from './pages/Settings.vue';
import MarkersIndex from './pages/markers/Index.vue';
import MarkersCreate from './pages/markers/Create.vue';
import LinesIndex from './pages/lines/Index.vue';
import LinesCreate from './pages/lines/Create.vue';
import PolygonsIndex from './pages/polygons/Index.vue';
import CirclesIndex from './pages/circles/Index.vue';

export default [
    {
        path: '/',
        name: 'welcome',
        component: Welcome,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true },
    },
    {
        path: '/map',
        name: 'map',
        component: MapPage,
        meta: { requiresAuth: true },
    },
    {
        path: '/ruasjalan',
        name: 'ruasjalan.index',
        component: RuasJalanIndex,
        meta: { requiresAuth: true },
    },
    {
        path: '/ruasjalan/create',
        name: 'ruasjalan.create',
        component: RuasJalanCreate,
        meta: { requiresAuth: true },
    },
    {
        path: '/ruasjalan/:id',
        name: 'ruasjalan.show',
        component: RuasJalanShow,
        meta: { requiresAuth: true },
    },
    {
        path: '/ruasjalan/:id/edit',
        name: 'ruasjalan.edit',
        component: RuasJalanEdit,
        meta: { requiresAuth: true },
    },
    {
        path: '/settings',
        name: 'settings',
        component: Settings,
        meta: { requiresAuth: true },
    },
    {
        path: '/markers',
        name: 'markers',
        component: MarkersIndex,
        meta: { requiresAuth: true },
    },
    {
        path: '/markers/create',
        name: 'markers.create',
        component: MarkersCreate,
        meta: { requiresAuth: true },
    },
    {
        path: '/lines',
        name: 'lines',
        component: LinesIndex,
        meta: { requiresAuth: true },
    },
    {
        path: '/lines/create',
        name: 'lines.create',
        component: LinesCreate,
        meta: { requiresAuth: true },
    },
    {
        path: '/polygons',
        name: 'polygons',
        component: PolygonsIndex,
        meta: { requiresAuth: true },
    },
    {
        path: '/circles',
        name: 'circles',
        component: CirclesIndex,
        meta: { requiresAuth: true },
    },
];

