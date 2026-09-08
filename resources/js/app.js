import './bootstrap';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import 'leaflet.markercluster';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import ApexCharts from 'apexcharts';
import * as LucideIcons from 'lucide';

window.L = L;
window.ApexCharts = ApexCharts;
window.LucideIcons = LucideIcons;

// Helper to initialize Lucide icons anywhere
window.initLucideIcons = function() {
    if (typeof LucideIcons !== 'undefined' && LucideIcons.createIcons) {
        LucideIcons.createIcons();
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.initLucideIcons();
});

document.addEventListener('livewire:navigated', () => {
    window.initLucideIcons();
});

// Custom map factory helper with Google Earth Satellite & OSM layers
window.getMapLayers = function() {
    return {
        satellite: L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '&copy; Google Earth / Satellite'
        }),
        streets: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }),
        esriSatellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: '&copy; Esri World Imagery'
        })
    };
};

window.createGresikMap = function(containerId, options = {}) {
    const defaultCenter = options.center || [-7.1566, 112.6555]; // Gresik center
    const defaultZoom = options.zoom || 12;

    const map = L.map(containerId, {
        zoomControl: options.zoomControl !== false,
        attributionControl: false,
    }).setView(defaultCenter, defaultZoom);

    // Default to Google Earth Satellite Hybrid
    const layers = window.getMapLayers();
    layers.satellite.addTo(map);

    // Add Layer Switcher Control if enabled
    if (options.layerControl !== false) {
        L.control.layers({
            '🛰️ Google Earth (Satelit)': layers.satellite,
            '🗺️ Peta Jalan (Street)': layers.streets
        }, null, { position: 'topright' }).addTo(map);
    }

    return map;
};

// Fix leaflet default marker icons in webpack/vite
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
});
