import Welcome from './pages/Welcome.vue';
import Login from './pages/auth/Login.vue';
import Register from './pages/auth/Register.vue';
import Dashboard from './pages/Dashboard.vue';
import MapPage from './pages/MapPage.vue';
import Location from './pages/Location.vue';
import Profile from './pages/Profile.vue';
import RuasJalanIndex from './pages/ruasjalan/Index.vue';
import RuasJalanCreate from './pages/ruasjalan/Create.vue';
import RuasJalanEdit from './pages/ruasjalan/Edit.vue';
import RuasJalanShow from './pages/ruasjalan/Show.vue';
import Settings from './pages/Settings.vue';
import MarkersIndex from './pages/markers/Index.vue';
import MarkersCreate from './pages/markers/Create.vue';
import MarkersEdit from './pages/markers/Edit.vue';
import LinesIndex from './pages/lines/Index.vue';
import LinesCreate from './pages/lines/Create.vue';
import LinesEdit from './pages/lines/Edit.vue';
import PolygonsIndex from './pages/polygons/Index.vue';
import PolygonsCreate from './pages/polygons/Create.vue';
import PolygonsEdit from './pages/polygons/Edit.vue';
import CirclesIndex from './pages/circles/Index.vue';
import CirclesCreate from './pages/circles/Create.vue';
import CirclesEdit from './pages/circles/Edit.vue';
import MarkerCategories from './pages/categories/MarkerCategories.vue';
import LineCategories from './pages/categories/LineCategories.vue';
import PolygonCategories from './pages/categories/PolygonCategories.vue';
import CircleCategories from './pages/categories/CircleCategories.vue';
import CustomanIndex from './pages/customan/Index.vue';
import CustomanCreate from './pages/customan/Create.vue';
import CustomanEdit from './pages/customan/Edit.vue';
import CustomanShow from './pages/customan/Show.vue';

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
        path: '/location',
        name: 'location',
        component: Location,
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
        path: '/markers/:id/edit',
        name: 'markers.edit',
        component: MarkersEdit,
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
        path: '/lines/:id/edit',
        name: 'lines.edit',
        component: LinesEdit,
        meta: { requiresAuth: true },
    },
    {
        path: '/polygons',
        name: 'polygons',
        component: PolygonsIndex,
        meta: { requiresAuth: true },
    },
    {
        path: '/polygons/create',
        name: 'polygons.create',
        component: PolygonsCreate,
        meta: { requiresAuth: true },
    },
    {
        path: '/polygons/:id/edit',
        name: 'polygons.edit',
        component: PolygonsEdit,
        meta: { requiresAuth: true },
    },
    {
        path: '/circles',
        name: 'circles',
        component: CirclesIndex,
        meta: { requiresAuth: true },
    },
    {
        path: '/circles/create',
        name: 'circles.create',
        component: CirclesCreate,
        meta: { requiresAuth: true },
    },
    {
        path: '/circles/:id/edit',
        name: 'circles.edit',
        component: CirclesEdit,
        meta: { requiresAuth: true },
    },
    {
        path: '/marker-categories',
        name: 'marker-categories',
        component: MarkerCategories,
        meta: { requiresAuth: true },
    },
    {
        path: '/line-categories',
        name: 'line-categories',
        component: LineCategories,
        meta: { requiresAuth: true },
    },
    {
        path: '/polygon-categories',
        name: 'polygon-categories',
        component: PolygonCategories,
        meta: { requiresAuth: true },
    },
    {
        path: '/circle-categories',
        name: 'circle-categories',
        component: CircleCategories,
        meta: { requiresAuth: true },
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: { requiresAuth: true },
    },
    {
        path: '/customan',
        name: 'customan',
        component: CustomanIndex,
        meta: { requiresAuth: true },
    },
    {
        path: '/customan/create',
        name: 'customan.create',
        component: CustomanCreate,
        meta: { requiresAuth: true },
    },
    {
        path: '/customan/:id/edit',
        name: 'customan.edit',
        component: CustomanEdit,
        meta: { requiresAuth: true },
    },
    {
        path: '/customan/:id',
        name: 'customan.show',
        component: CustomanShow,
        meta: { requiresAuth: true },
    },
];

