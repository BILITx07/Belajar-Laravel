/*
 * peta-map.js — WebGIS BWS Kalimantan I
 * Fitur: Peta Leaflet Interaktif, Layer Spasial, Popup Card Modern (ala Gambar 4),
 * Live Search Autocomplete, Filter Kabupaten, Filter Tab Kategori Tabel & Pagination 10 baris.
 */

/* ============================================================
   1. INISIALISASI PETA LEAFLET & BASEMAP
   ============================================================ */
var defaultCenter = [-0.15, 109.85]; // Koordinat Pusat Kalimantan Barat

// Peta Dasar OpenStreetMap
var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap | BWS Kalimantan I'
});

// Peta Satelit Google Hybrid
var googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: '&copy; Google Maps Satellite | BWS Kalimantan I'
});

var map = L.map('map', {
    center: defaultCenter,
    zoom: 8,
    layers: [osm],
    zoomControl: true
});
window.map = map;
var mapBounds = L.latLngBounds();

// Pastikan ukuran peta selalu pas dan responsif mengikuti tinggi kontainer
window.addEventListener('resize', function() {
    map.invalidateSize();
});
setTimeout(function() {
    map.invalidateSize();
}, 250);

/* Skala Peta Metrik (Cartographic Scale Bar) */
L.control.scale({
    imperial: false,
    metric: true,
    position: 'bottomleft',
    maxWidth: 130
}).addTo(map);

/* Aturan Ketebalan & Ukuran Geometri Adaptif Mengikuti Tingkat Zoom Peta (Pencegah Gumpalan Merah) */
function getSaluranWeight(z) {
    if (z <= 8) return 0.75;
    if (z <= 10) return 1.1;
    if (z <= 12) return 1.6;
    if (z <= 14) return 2.2;
    return 2.8;
}

function getSaluranOpacity(z) {
    if (z <= 8) return 0.45;
    if (z <= 10) return 0.65;
    if (z <= 12) return 0.85;
    return 0.95;
}

function getBangunanRadius(z) {
    if (z <= 8) return 1.8;
    if (z <= 10) return 2.8;
    if (z <= 12) return 4.2;
    if (z <= 14) return 5.5;
    return 7.0;
}

function getBangunanWeight(z) {
    if (z <= 9) return 0.8;
    if (z <= 11) return 1.2;
    return 1.8;
}

/* Sinkronisasi Ukuran Card Popup secara Proporsional Mengikuti Zoom (Murni Skala Vektor) */
function syncMapZoomState() {
    var z = map.getZoom();
    var container = map.getContainer();
    if (!container) return;

    var scale = 1.0;
    if (z >= 15)      scale = 1.15;
    else if (z === 14) scale = 1.05;
    else if (z === 13) scale = 0.96;
    else if (z === 12) scale = 0.88;
    else if (z === 11) scale = 0.78;
    else if (z === 10) scale = 0.68;
    else if (z === 9)  scale = 0.58;
    else if (z === 8)  scale = 0.48;
    else if (z === 7)  scale = 0.40;
    else              scale = 0.32;

    container.style.setProperty('--popup-zoom-scale', scale);

    // Aktifkan mode detail (munculkan simbol ikon pintu air mini pada zoom >= 12)
    if (z >= 12) {
        container.classList.add('zoom-detail');
    } else {
        container.classList.remove('zoom-detail');
    }
}

/* Update Dinamis Gaya Spasial Saluran & Bangunan saat Zoom Berubah */
function updateDynamicLayerStyles() {
    var z = map.getZoom();
    var sw = getSaluranWeight(z);
    var so = getSaluranOpacity(z);
    var br = getBangunanRadius(z);
    var bw = getBangunanWeight(z);

    layerSaluran.eachLayer(function(gj) {
        if (gj.setStyle) {
            gj.setStyle({ weight: sw, opacity: so });
        }
    });

    layerBangunan.eachLayer(function(gj) {
        if (gj.eachLayer) {
            gj.eachLayer(function(pt) {
                if (pt.setRadius) pt.setRadius(br);
                if (pt.setStyle) pt.setStyle({ weight: bw });
            });
        }
    });
}

map.on('zoom', syncMapZoomState);
map.on('zoomend', function() {
    syncMapZoomState();
    updateDynamicLayerStyles();
});
syncMapZoomState();

/* ============================================================
   2. LAYER GROUPS PER KATEGORI SPASIAL & SWITCHER BASEMAP
   ============================================================ */
var layerBaku       = L.layerGroup().addTo(map);
var layerFungsional = L.layerGroup().addTo(map);
var layerPotensial  = L.layerGroup().addTo(map);
var layerSaluran    = L.layerGroup().addTo(map);
var layerBangunan   = L.layerGroup().addTo(map);
var layerWsKapuas   = L.layerGroup();

/* Fungsi Ganti Peta Dasar (Peta Jalan vs Satelit) via Tombol Rapi di Atas Peta */
window.switchBasemap = function(type) {
    var btnOsm = document.getElementById('btnBasemapOsm');
    var btnSat = document.getElementById('btnBasemapSat');
    if (type === 'sat') {
        if (map.hasLayer(osm)) map.removeLayer(osm);
        if (!map.hasLayer(googleSat)) map.addLayer(googleSat);
        if (btnOsm) btnOsm.classList.remove('active');
        if (btnSat) btnSat.classList.add('active');
    } else {
        if (map.hasLayer(googleSat)) map.removeLayer(googleSat);
        if (!map.hasLayer(osm)) map.addLayer(osm);
        if (btnSat) btnSat.classList.remove('active');
        if (btnOsm) btnOsm.classList.add('active');
    }
};

/* ============================================================
   3. REPROYEKSI KOORDINAT (ArcMap / WGS84)
   ============================================================ */
function projectCoordinate(x, y, wkid) {
    if (typeof x !== 'number' || typeof y !== 'number' || isNaN(x) || isNaN(y)) return [x, y];
    if (Math.abs(x) <= 180 && Math.abs(y) <= 90) return [x, y];

    if (Math.abs(x) >= 5000000 && Math.abs(x) <= 20000000 && Math.abs(y) <= 2000000) {
        var lon = (x / 6378137.0) * (180.0 / Math.PI);
        var lat = (2.0 * Math.atan(Math.exp(y / 6378137.0)) - (Math.PI / 2.0)) * (180.0 / Math.PI);
        return [lon, lat];
    }
    try {
        var wkidStr = String(wkid || '');
        if (wkid === 54034 || wkidStr.indexOf('54034') !== -1) {
            var lonEqc = (x / 6378137.0) * (180.0 / Math.PI);
            var latEqc = (2.0 * Math.atan(Math.exp(y / 6378137.0)) - (Math.PI / 2.0)) * (180.0 / Math.PI);
            return [lonEqc, latEqc];
        }
        if (wkid === 3857 || wkidStr.indexOf('3857') !== -1)  return proj4('EPSG:3857',  'EPSG:4326', [x, y]);
        if (wkid === 32649 || wkidStr.indexOf('32649') !== -1) return proj4('EPSG:32649', 'EPSG:4326', [x, y]);
        if (wkid === 32749 || wkidStr.indexOf('32749') !== -1) return proj4('EPSG:32749', 'EPSG:4326', [x, y]);
        if (wkid && proj4.defs['EPSG:' + wkid]) return proj4('EPSG:' + wkid, 'EPSG:4326', [x, y]);
    } catch(e) {}

    if (x > 100000 && x < 1000000) {
        try { return proj4('EPSG:32649', 'EPSG:4326', [x, y]); } catch(e){}
    }
    return [x, y];
}

function transformGeoJsonCoords(coords, geomType, wkid) {
    if (!coords) return coords;
    if (geomType === 'Point') return projectCoordinate(coords[0], coords[1], wkid);
    if (geomType === 'LineString' || geomType === 'MultiPoint')
        return coords.map(function(c) { return projectCoordinate(c[0], c[1], wkid); });
    if (geomType === 'Polygon' || geomType === 'MultiLineString')
        return coords.map(function(ring) { return ring.map(function(c) { return projectCoordinate(c[0], c[1], wkid); }); });
    if (geomType === 'MultiPolygon')
        return coords.map(function(poly) { return poly.map(function(ring) { return ring.map(function(c) { return projectCoordinate(c[0], c[1], wkid); }); }); });
    return coords;
}

function convertEsriToGeoJson(data) {
    if (!data) return null;
    var wkid = null;
    if (data.crs && data.crs.properties && data.crs.properties.name) wkid = String(data.crs.properties.name);
    else if (data.spatialReference) wkid = data.spatialReference.wkid || data.spatialReference.latestWkid;

    if (data.type === 'FeatureCollection' && Array.isArray(data.features)) {
        data.features.forEach(function(f) {
            if (f.geometry && f.geometry.coordinates)
                f.geometry.coordinates = transformGeoJsonCoords(f.geometry.coordinates, f.geometry.type, wkid);
        });
        return data;
    }
    if (!data.features || !Array.isArray(data.features)) return null;

    var geomType = data.geometryType;
    var gj = { type: 'FeatureCollection', features: [] };
    data.features.forEach(function(f, idx) {
        if (!f.geometry) return;
        var geom = null;
        if (geomType === 'esriGeometryPoint' || (f.geometry.x !== undefined && f.geometry.y !== undefined)) {
            var pt = projectCoordinate(f.geometry.x, f.geometry.y, wkid);
            geom = { type: 'Point', coordinates: [pt[0], pt[1]] };
        } else if (geomType === 'esriGeometryPolyline' || f.geometry.paths) {
            var paths = f.geometry.paths || [];
            if (paths.length === 1) geom = { type: 'LineString', coordinates: paths[0].map(function(c){ return projectCoordinate(c[0],c[1],wkid); }) };
            else if (paths.length > 1) geom = { type: 'MultiLineString', coordinates: paths.map(function(p){ return p.map(function(c){ return projectCoordinate(c[0],c[1],wkid); }); }) };
        } else if (geomType === 'esriGeometryPolygon' || f.geometry.rings) {
            var rings = f.geometry.rings || [];
            if (rings.length > 0) geom = { type: 'Polygon', coordinates: rings.map(function(r){ return r.map(function(c){ return projectCoordinate(c[0],c[1],wkid); }); }) };
        }
        if (geom) {
            gj.features.push({
                type: 'Feature',
                id: (f.attributes && (f.attributes.FID || f.attributes.OBJECTID)) ? (f.attributes.FID || f.attributes.OBJECTID) : idx,
                geometry: geom,
                properties: f.attributes || {}
            });
        }
    });
    return gj;
}

/* ============================================================
   4. DETEKSI KABUPATEN DARI NAMA FILE / NAMA DAERAH IRIGASI
   ============================================================ */
function detectKabupaten(fileName, title) {
    var str = ((fileName || '') + ' ' + (title || '')).toLowerCase();
    if (str.indexOf('sambas') !== -1 || str.indexOf('pemangkat') !== -1 || str.indexOf('sebangkau') !== -1 || str.indexOf('selakau') !== -1 || str.indexOf('sebubus') !== -1 || str.indexOf('pimpinan') !== -1) {
        return 'Kab. Sambas';
    }
    if (str.indexOf('kubu') !== -1 || str.indexOf('kerawang') !== -1 || str.indexOf('teluk_bayur') !== -1 || str.indexOf('kakap') !== -1) {
        return 'Kab. Kubu Raya';
    }
    if (str.indexOf('ketapang') !== -1 || str.indexOf('kinjil') !== -1 || str.indexOf('pematang') !== -1 || str.indexOf('gadung') !== -1 || str.indexOf('pesaguan') !== -1) {
        return 'Kab. Ketapang';
    }
    return 'Kalimantan Barat';
}

function getKabupatenKey(kabString) {
    var k = (kabString || '').toLowerCase();
    if (k.indexOf('sambas') !== -1) return 'sambas';
    if (k.indexOf('kubu') !== -1) return 'kubu_raya';
    if (k.indexOf('ketapang') !== -1) return 'ketapang';
    return 'all';
}

/* Normalisasi Teks Pencarian Pintar (Mengabaikan titik D.I.R vs DIR, garis bawah, strip, & spasi) */
function normalizeSearchText(str) {
    return (str || '').toLowerCase()
        .replace(/d\.i\.r\./gi, 'dir')
        .replace(/d\.i\./gi, 'di')
        .replace(/[._\-\(\),]/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
}

/* ============================================================
   5. PEMBUATAN POPUP CARD MODERN (SESUAI GAMBAR 4 REFERENSI)
   ============================================================ */
function createModernPopupHtml(title, categoryName, categoryType, fileName, lat, lng, properties) {
    var p = properties || {};
    var kab = p.KABUPATEN || p.Kabupaten || p.WADMKC || detectKabupaten(fileName, title);
    var latStr = (lat !== undefined && lat !== null && !isNaN(lat)) ? Number(lat).toFixed(5) : '-';
    var lngStr = (lng !== undefined && lng !== null && !isNaN(lng)) ? Number(lng).toFixed(5) : '-';

    var badgeBg = '#2563eb';
    var iconClass = 'fas fa-map-marker-alt';
    if (categoryType === 'baku')            { badgeBg = '#10b981'; iconClass = 'fas fa-seedling'; }
    else if (categoryType === 'potensial')  { badgeBg = '#f59e0b'; iconClass = 'fas fa-drafting-compass'; }
    else if (categoryType === 'saluran')    { badgeBg = '#0284c7'; iconClass = 'fas fa-route'; }
    else if (categoryType === 'bangunan')   { badgeBg = '#d97706'; iconClass = 'fas fa-archway'; }
    else if (categoryType === 'fungsional') { badgeBg = '#2563eb'; iconClass = 'fas fa-water'; }
    else if (categoryType === 'ws_kapuas')  { badgeBg = '#0891b2'; iconClass = 'fas fa-tint'; }

    // Rangkai baris detail spesifik sesuai jenis aset SDA agar informatif & akurat
    var tableRows = '';

    if (categoryType === 'bangunan') {
        var statusKet = p.Ket || 'Pemeliharaan Rutin';
        tableRows += '<tr><th>Fungsi</th><td>Pintu Air / Pengatur</td></tr>';
        tableRows += '<tr><th>Status O&P</th><td><span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-size:11px;">' + statusKet + '</span></td></tr>';
        tableRows += '<tr><th>Titik Lokasi</th><td><code style="color:#0f172a; font-size:11px;">' + latStr + ', ' + lngStr + '</code></td></tr>';
    } else if (categoryType === 'saluran') {
        var jenisSal = p.Jenis ? ('Saluran ' + p.Jenis) : 'Saluran Irigasi';
        var pjg = p.PJG || (p.Panjang ? (Math.round(p.Panjang).toLocaleString('id-ID') + ' m') : 'Tercatat di Skema');
        var statusKet = p.Ket || 'Pemeliharaan Rutin';
        tableRows += '<tr><th>Hierarki</th><td>' + jenisSal + '</td></tr>';
        tableRows += '<tr><th>Panjang Saluran</th><td><b>' + pjg + '</b></td></tr>';
        tableRows += '<tr><th>Status O&P</th><td>' + statusKet + '</td></tr>';
    } else {
        // Daerah Irigasi (Baku / Fungsional / Potensial)
        var luas = '';
        if (p.Luas_Ha) luas = Number(p.Luas_Ha).toLocaleString('id-ID', { maximumFractionDigits: 2 }) + ' Ha';
        else if (p.LUAS_BAKU || p.Luas_Baku) luas = (p.LUAS_BAKU || p.Luas_Baku) + ' Ha';
        else if (p.LUAS) luas = p.LUAS + ' Ha';
        else luas = 'Tersedia di Buku DIR';

        var kewenangan = p.KEWENANGAN || p.Kewenagan || 'Pusat (Kemen PU)';
        var wsDas = (p.Nama_WS || p.Nama_DAS)
            ? ((p.Nama_WS ? 'WS ' + p.Nama_WS : '') + (p.Nama_DAS ? ' / DAS ' + p.Nama_DAS : ''))
            : (p.PROVINSI ? 'Kalimantan Barat' : 'BWS Kalimantan I');

        tableRows += '<tr><th>Luas Area</th><td><b>' + luas + '</b></td></tr>';
        tableRows += '<tr><th>Kewenangan</th><td>' + kewenangan + '</td></tr>';
        tableRows += '<tr><th>Wilayah Sungai</th><td>' + wsDas + '</td></tr>';
    }

    return '<div class="bws-popup-card">' +
        '<div class="bws-popup-header">' +
            '<div class="d-flex align-items-center justify-content-between mb-1">' +
                '<span class="bws-popup-badge" style="background:' + badgeBg + '; color:#fff;"><i class="' + iconClass + ' mr-1"></i>' + categoryName + '</span>' +
                '<button type="button" class="bws-popup-close-btn" onclick="map.closePopup()" title="Tutup">&times;</button>' +
            '</div>' +
            '<h6 class="bws-popup-title">' + title + '</h6>' +
            '<div class="bws-popup-sub"><i class="fas fa-map-marker-alt mr-1"></i>' + kab + '</div>' +
        '</div>' +
        '<div class="bws-popup-body">' +
            '<table class="bws-popup-table">' +
                tableRows +
            '</table>' +
        '</div>' +
    '</div>';
}

/* ============================================================
   6. LOAD SELURUH GEOJSON & PARSING KE LAYER PETA
   ============================================================ */
var mapLoadingEl = document.getElementById('map-loading');
var allTableRows = [];
var currentSearchText = '';
var currentCategoryFilter = 'all';
var currentWilayahFilter = 'all';
var currentPage = 1;
var pageSize = 10;

if (window.geojsonFiles && window.geojsonFiles.length > 0) {
    var loadedCount = 0;

    setTimeout(function() {
        if (mapLoadingEl && mapLoadingEl.style.display !== 'none') {
            mapLoadingEl.style.display = 'none';
            if (mapBounds.isValid()) map.fitBounds(mapBounds, { padding: [40, 40] });
            renderTable();
        }
    }, 7000);

    window.geojsonFiles.forEach(function(fileName) {
        fetch('/geojson/' + encodeURIComponent(fileName))
            .then(function(res) { if (!res.ok) throw new Error('HTTP ' + res.status); return res.json(); })
            .then(function(rawData) {
                var gj = convertEsriToGeoJson(rawData);
                if (!gj || !gj.features || gj.features.length === 0) return;

                var nameUpper = fileName.toUpperCase();
                var cleanName = fileName.replace(/\.(json|geojson)$/i, '').replace(/_/g, ' ');
                var targetLayer = layerBaku;
                var categoryType = 'baku';
                var categoryName = 'DIR Baku';
                var categoryBadge = '<span class="badge badge-baku px-2 py-1">DIR Baku</span>';
                var strokeColor = '#10b981', fillColor = '#34d399';

                if (nameUpper.indexOf('BAKU') === 0) {
                    targetLayer = layerBaku; categoryType = 'baku'; categoryName = 'DIR Baku';
                    categoryBadge = '<span class="badge badge-baku px-2 py-1">DIR Baku</span>';
                    strokeColor = '#059669'; fillColor = '#10b981';
                } else if (nameUpper.indexOf('FUNGSIONAL') === 0) {
                    targetLayer = layerFungsional; categoryType = 'fungsional'; categoryName = 'DIR Fungsional';
                    categoryBadge = '<span class="badge badge-fungsional px-2 py-1">DIR Fungsional</span>';
                    strokeColor = '#1d4ed8'; fillColor = '#3b82f6';
                } else if (nameUpper.indexOf('POTENSIAL') === 0) {
                    targetLayer = layerPotensial; categoryType = 'potensial'; categoryName = 'DIR Potensial';
                    categoryBadge = '<span class="badge badge-potensial px-2 py-1">DIR Potensial</span>';
                    strokeColor = '#d97706'; fillColor = '#f59e0b';
                } else if (nameUpper.indexOf('SALURAN') === 0) {
                    targetLayer = layerSaluran; categoryType = 'saluran'; categoryName = 'Jaringan Saluran';
                    categoryBadge = '<span class="badge badge-saluran px-2 py-1">Saluran Irigasi</span>';
                    strokeColor = '#0284c7'; fillColor = '#38bdf8';
                } else if (nameUpper.indexOf('BANGUNAN') === 0) {
                    targetLayer = layerBangunan; categoryType = 'bangunan'; categoryName = 'Bangunan Air';
                    categoryBadge = '<span class="badge badge-bangunan px-2 py-1">Bangunan Air</span>';
                    strokeColor = '#b45309'; fillColor = '#f59e0b';
                } else if (nameUpper.indexOf('KAPUAS') !== -1 || nameUpper.indexOf('WS') === 0 || nameUpper.indexOf('DAS') !== -1) {
                    targetLayer = layerWsKapuas; categoryType = 'ws_kapuas'; categoryName = 'WS Kapuas';
                    categoryBadge = '<span class="badge px-2 py-1" style="background:#0891b2;color:#fff;">WS Kapuas</span>';
                    strokeColor = '#0891b2'; fillColor = '#06b6d4';
                }

                var kabName = detectKabupaten(fileName, cleanName);
                var kabKey = getKabupatenKey(kabName);

                var firstPopupHtml = null;
                var firstLayer = null;
                var firstTitle = null;
                var featureNames = [];

                var isSaluran  = (categoryType === 'saluran');
                var isBangunan = (categoryType === 'bangunan');
                var isWs       = (categoryType === 'ws_kapuas');

                var gjLayer = L.geoJSON(gj, {
                    style: function() {
                        var currentZoom = map.getZoom();
                        return {
                            color: strokeColor,
                            weight: isSaluran ? getSaluranWeight(currentZoom) : 1.5,
                            opacity: isSaluran ? getSaluranOpacity(currentZoom) : 0.85,
                            fillOpacity: isSaluran ? 0 : (isWs ? 0.05 : 0.22),
                            fillColor: fillColor
                        };
                    },
                    pointToLayer: function(feat, latlng) {
                        if (categoryType === 'bangunan') {
                            var pName = (feat.properties && (feat.properties.NAMOBJ || feat.properties.Name || feat.properties.D_I_R)) ? (feat.properties.NAMOBJ || feat.properties.Name || feat.properties.D_I_R) : 'Bangunan Air';
                            return L.marker(latlng, {
                                icon: L.divIcon({
                                    className: 'bws-bangunan-marker-wrap',
                                    html: '<div class="bws-gate-pin" title="' + pName.replace(/"/g, '&quot;') + '"><i class="fas fa-archway"></i></div>',
                                    iconSize: [22, 22],
                                    iconAnchor: [11, 11],
                                    popupAnchor: [0, -11]
                                })
                            });
                        }
                        var currentZoom = map.getZoom();
                        return L.circleMarker(latlng, {
                            radius: getBangunanRadius(currentZoom),
                            fillColor: fillColor,
                            color: '#ffffff',
                            weight: getBangunanWeight(currentZoom),
                            fillOpacity: 0.90
                        });
                    },
                    onEachFeature: function(feat, layer) {
                        var p = feat.properties || {};
                        var title = p.NAMOBJ || p.Name || p.D_I_R || cleanName;
                        if (title) featureNames.push(title);

                        var b = layer.getBounds ? layer.getBounds() : null;
                        var center = b && b.isValid && b.isValid() ? b.getCenter() : (layer.getLatLng ? layer.getLatLng() : null);
                        var lat = center ? center.lat : 0;
                        var lng = center ? center.lng : 0;

                        var popupHtml = createModernPopupHtml(title, categoryName, categoryType, fileName, lat, lng, p);
                        layer.bindPopup(popupHtml, { maxWidth: 320 });

                        if (!firstPopupHtml) {
                            firstPopupHtml = popupHtml;
                            firstLayer = layer;
                            firstTitle = title;
                        }

                        layer.on('click', function(e) {
                            if (categoryType === 'bangunan' && center) {
                                map.flyTo([center.lat, center.lng], Math.max(map.getZoom(), 15), { animate: true, duration: 0.8 });
                            } else if (b && b.isValid && b.isValid()) {
                                map.flyToBounds(b, { padding: [50, 50], maxZoom: 15, duration: 0.8 });
                            } else if (center) {
                                map.flyTo([center.lat, center.lng], Math.max(map.getZoom(), 14), { animate: true, duration: 0.8 });
                            }
                        });
                    }
                });

                targetLayer.addLayer(gjLayer);

                var b = gjLayer.getBounds();
                if (b.isValid()) {
                    if (categoryType !== 'ws_kapuas') {
                        mapBounds.extend(b);
                    }
                    var center = b.getCenter();
                    var finalPopup = firstPopupHtml || createModernPopupHtml(cleanName, categoryName, categoryType, fileName, center.lat, center.lng, {});
                    allTableRows.push({
                        name: cleanName,
                        title: firstTitle || cleanName,
                        featureNames: featureNames.slice(0, 100).join(' '),
                        categoryType: categoryType,
                        categoryBadge: categoryBadge,
                        kabupaten: kabName,
                        kabupatenKey: kabKey,
                        lat: center.lat,
                        lng: center.lng,
                        fileName: fileName,
                        bounds: b,
                        layerObj: gjLayer,
                        firstLayer: firstLayer,
                        popupHtml: finalPopup
                    });
                }
            })
            .catch(function(err) {})
            .finally(function() {
                loadedCount++;
                if (loadedCount >= window.geojsonFiles.length) {
                    if (mapLoadingEl) mapLoadingEl.style.display = 'none';
                    if (mapBounds.isValid()) map.fitBounds(mapBounds, { padding: [40, 40] });
                    renderTable();
                }
            });
    });
} else {
    if (mapLoadingEl) mapLoadingEl.style.display = 'none';
    renderTable();
}

/* ============================================================
   7. LIVE AUTOCOMPLETE PENCARIAN SIDEBAR (ALA GAMBAR 3 QROP)
   ============================================================ */
window.handleSidebarSearch = function(val) {
    var rawQ = (val || '').trim();
    var dropdown = document.getElementById('sidebarSearchResults');
    var btnClear = document.getElementById('btnSidebarSearchClear');

    if (btnClear) {
        btnClear.style.display = rawQ ? 'flex' : 'none';
    }

    if (!dropdown) return;
    if (!rawQ) {
        dropdown.style.display = 'none';
        dropdown.innerHTML = '';
        return;
    }

    var normQ = normalizeSearchText(rawQ);
    var tokens = normQ.split(' ').filter(Boolean);

    var matches = allTableRows.filter(function(r) {
        var rText = normalizeSearchText((r.title || '') + ' ' + (r.name || '') + ' ' + (r.fileName || '') + ' ' + (r.kabupaten || ''));
        return (rText.indexOf(normQ) !== -1) || (tokens.length > 0 && tokens.every(function(t) { return rText.indexOf(t) !== -1; }));
    });

    if (matches.length === 0) {
        dropdown.innerHTML = '<div class="p-3 text-center text-muted small"><i class="fas fa-info-circle mr-1"></i> Tidak ditemukan objek irigasi dengan kata kunci tersebut.</div>';
        dropdown.style.display = 'block';
        return;
    }

    var html = '';
    matches.slice(0, 8).forEach(function(row) {
        var displayName = row.title || row.name;
        html += '<div class="bws-search-item" onclick="focusFromSidebarSearch(\'' + row.name.replace(/'/g, "\\'") + '\')">' +
            '<div class="bws-search-item-title"><i class="fas fa-map-pin mr-1 text-danger"></i> ' + displayName + '</div>' +
            '<div class="bws-search-item-sub">' + row.kabupaten + ' &bull; ' + row.fileName + '</div>' +
        '</div>';
    });
    dropdown.innerHTML = html;
    dropdown.style.display = 'block';
};

window.clearSidebarSearch = function() {
    var input = document.getElementById('sidebarSearchInput');
    var dropdown = document.getElementById('sidebarSearchResults');
    var btnClear = document.getElementById('btnSidebarSearchClear');
    if (input) input.value = '';
    if (dropdown) { dropdown.style.display = 'none'; dropdown.innerHTML = ''; }
    if (btnClear) btnClear.style.display = 'none';
};

window.focusFromSidebarSearch = function(name) {
    var target = allTableRows.find(function(r) { return r.name === name; });
    if (!target) return;

    clearSidebarSearch();
    var input = document.getElementById('sidebarSearchInput');
    if (input) input.value = target.name;

    // Pastikan layer kategori aktif
    var layerMap = {
        'baku': layerBaku,
        'fungsional': layerFungsional,
        'potensial': layerPotensial,
        'saluran': layerSaluran,
        'bangunan': layerBangunan,
        'ws_kapuas': layerWsKapuas
    };
    if (layerMap[target.categoryType] && !map.hasLayer(layerMap[target.categoryType])) {
        map.addLayer(layerMap[target.categoryType]);
        var chk = document.getElementById('chkLayer' + target.categoryType.charAt(0).toUpperCase() + target.categoryType.slice(1));
        if (chk) chk.checked = true;
    }

    // Scroll ke peta jika berada di luar viewport
    var mapEl = document.getElementById('map') || document.querySelector('.bws-leaflet-card');
    if (mapEl) {
        var rect = mapEl.getBoundingClientRect();
        if (rect.top < 0 || rect.bottom > window.innerHeight) {
            window.scrollTo({ top: mapEl.offsetTop - 85, behavior: 'smooth' });
        }
    }

    var targetLatLng = [target.lat, target.lng];
    if (target.bounds && target.bounds.isValid && target.bounds.isValid()) {
        map.flyToBounds(target.bounds, { padding: [60, 60], maxZoom: 14, duration: 0.9 });
    } else if (target.lat && target.lng) {
        map.flyTo(targetLatLng, 14, { animate: true, duration: 0.9 });
    }

    // Buka card popup modern secara otomatis
    setTimeout(function() {
        if (target.firstLayer && typeof target.firstLayer.openPopup === 'function') {
            target.firstLayer.openPopup();
        } else if (target.popupHtml && target.lat && target.lng) {
            L.popup({ maxWidth: 320, autoPan: false, className: 'bws-leaflet-popup' })
                .setLatLng(targetLatLng)
                .setContent(target.popupHtml)
                .openOn(map);
        }
    }, 500);
};

/* ============================================================
   8. FILTER WILAYAH KABUPATEN
   ============================================================ */
window.handleFilterWilayah = function(val) {
    currentWilayahFilter = val;
    currentPage = 1;

    var breadcrumb = document.getElementById('currentBreadcrumbLabel');
    var labelMap = {
        'all': 'Seluruh Kalimantan Barat',
        'sambas': 'Kabupaten Sambas / Pemangkat',
        'kubu_raya': 'Kabupaten Kubu Raya',
        'ketapang': 'Kabupaten Ketapang'
    };
    if (breadcrumb) {
        breadcrumb.textContent = labelMap[val] || 'Seluruh Kalimantan Barat';
    }

    if (val === 'sambas') {
        map.flyTo([1.16, 108.98], 11, { animate: true, duration: 1.2 });
    } else if (val === 'kubu_raya') {
        map.flyTo([-0.55, 109.50], 10, { animate: true, duration: 1.2 });
    } else if (val === 'ketapang') {
        map.flyTo([-1.90, 110.05], 10, { animate: true, duration: 1.2 });
    } else {
        if (mapBounds && mapBounds.isValid()) map.fitBounds(mapBounds, { padding: [40, 40] });
        else map.setView(defaultCenter, 8);
    }

    renderTable();
};

/* ============================================================
   9. CHECKBOX TOGGLE KATEGORI LAYER DARI SIDEBAR
   ============================================================ */
window.toggleCategoryLayer = function(cat, isChecked) {
    var layerMap = {
        'baku': layerBaku,
        'fungsional': layerFungsional,
        'potensial': layerPotensial,
        'saluran': layerSaluran,
        'bangunan': layerBangunan
    };
    var layer = layerMap[cat];
    if (!layer) return;

    if (isChecked) {
        if (!map.hasLayer(layer)) map.addLayer(layer);
    } else {
        if (map.hasLayer(layer)) map.removeLayer(layer);
    }

    // Beri umpan balik visual (.inactive) pada kartu kategori di sidebar
    var chk = document.getElementById('chkLayer' + cat.charAt(0).toUpperCase() + cat.slice(1));
    if (chk) {
        var row = chk.closest('.bws-category-row');
        if (row) {
            if (isChecked) {
                row.classList.remove('inactive');
            } else {
                row.classList.add('inactive');
            }
        }
    }
};

/* ============================================================
   10. TOGGLE TAMPILAN TABEL DATA
   ============================================================ */
window.toggleTableVisibility = function() {
    var tableSec = document.getElementById('section-table');
    var labelTop = document.getElementById('labelToggleTable');
    var labelBottom = document.getElementById('labelBottomToggleTable');
    if (!tableSec) return;
    if (tableSec.style.display === 'none' || !tableSec.style.display) {
        tableSec.style.display = 'block';
        if (labelTop) labelTop.textContent = 'Tutup Tabel Data';
        if (labelBottom) labelBottom.textContent = 'Sembunyikan Daftar Pos & Data Spasial ▴';
        renderTable();
        window.scrollTo({ top: tableSec.offsetTop - 75, behavior: 'smooth' });
    } else {
        tableSec.style.display = 'none';
        var total = allTableRows.length || 57;
        if (labelTop) labelTop.textContent = 'Buka Tabel Data (' + total + ')';
        if (labelBottom) labelBottom.textContent = 'Tampilkan Daftar Pos & Data Spasial Tabular ▾';
    }
};

window.switchViewSegment = function(mode) {
    var secTable = document.getElementById('section-table');
    var labelTop = document.getElementById('labelToggleTable');
    var labelBottom = document.getElementById('labelBottomToggleTable');
    var total = allTableRows.length || 57;
    if (mode === 'maps') {
        if (secTable) secTable.style.display = 'none';
        if (labelTop) labelTop.textContent = 'Buka Tabel Data (' + total + ')';
        if (labelBottom) labelBottom.textContent = 'Tampilkan Daftar Pos & Data Spasial Tabular ▾';
    } else {
        if (secTable) secTable.style.display = 'block';
        if (labelTop) labelTop.textContent = 'Tutup Tabel Data';
        if (labelBottom) labelBottom.textContent = 'Sembunyikan Daftar Pos & Data Spasial ▴';
        renderTable();
    }
};

/* ============================================================
   11. RESET VIEW KE KALBAR
   ============================================================ */
window.resetToKalbar = function() {
    var selectWil = document.getElementById('selectFilterWilayah');
    if (selectWil) selectWil.value = 'all';
    currentWilayahFilter = 'all';
    currentPage = 1;

    var breadcrumb = document.getElementById('currentBreadcrumbLabel');
    if (breadcrumb) breadcrumb.textContent = 'Seluruh Kalimantan Barat';

    if (mapBounds && mapBounds.isValid()) {
        map.fitBounds(mapBounds, { padding: [40, 40] });
    } else {
        map.setView(defaultCenter, 8);
    }
    renderTable();
};

/* ============================================================
   12. TABEL DATA: FILTER KATEGORI & PAGINATION 10 BARIS
   ============================================================ */
window.filterTableCategory = function(cat) {
    currentCategoryFilter = cat;
    currentPage = 1;

    document.querySelectorAll('.bws-tab-pill, .bws-tab-btn').forEach(function(btn) {
        if (btn.getAttribute('data-cat') === cat) btn.classList.add('active');
        else btn.classList.remove('active');
    });

    renderTable();
};

window.filterTabelKoordinat = function(val) {
    currentSearchText = (val || '').trim().toLowerCase();
    currentPage = 1;
    renderTable();
};

window.goToPage = function(page) {
    currentPage = page;
    renderTable();
    var tableSec = document.getElementById('section-table');
    if (tableSec && window.scrollY > tableSec.offsetTop) {
        window.scrollTo({ top: tableSec.offsetTop - 80, behavior: 'smooth' });
    }
};

function renderTable() {
    var tbody = document.getElementById('tbody-lokasi');
    var footerInfo = document.getElementById('tabel-footer-info');
    var paginationUl = document.getElementById('tablePagination');
    if (!tbody) return;

    // 1. Filter Data dengan Pencarian Pintar (Normalisasi Punctuation, D.I.R vs DIR, kata parsial)
    var filtered = allTableRows.filter(function(row) {
        // Filter Pencarian Teks
        var matchSearch = true;
        if (currentSearchText !== '') {
            var normQuery = normalizeSearchText(currentSearchText);
            if (normQuery !== '') {
                var tokens = normQuery.split(' ').filter(Boolean);
                var rowHaystack = normalizeSearchText(
                    (row.title || '') + ' ' +
                    (row.name || '') + ' ' +
                    (row.fileName || '') + ' ' +
                    (row.kabupaten || '') + ' ' +
                    (row.featureNames || '')
                );
                matchSearch = (rowHaystack.indexOf(normQuery) !== -1) || (tokens.length > 0 && tokens.every(function(t) {
                    return rowHaystack.indexOf(t) !== -1;
                }));
            }
        }

        // Filter Kategori Tab
        var matchCat = true;
        if (currentCategoryFilter !== 'all') {
            matchCat = (row.categoryType === currentCategoryFilter);
        }

        // Filter Wilayah
        var matchWilayah = true;
        if (currentWilayahFilter !== 'all') {
            matchWilayah = (row.kabupatenKey === currentWilayahFilter);
        }

        return matchSearch && matchCat && matchWilayah;
    });

    var totalData = filtered.length;
    var totalPages = Math.ceil(totalData / pageSize) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;

    var startIdx = (currentPage - 1) * pageSize;
    var endIdx = Math.min(startIdx + pageSize, totalData);
    var pagedData = filtered.slice(startIdx, endIdx);

    // 2. Render Teks Footer
    if (footerInfo) {
        if (totalData === 0) {
            footerInfo.innerHTML = 'Tidak ada data spasial yang cocok.';
        } else {
            footerInfo.innerHTML = 'Menampilkan <b>' + (startIdx + 1) + ' - ' + endIdx + '</b> dari <b>' + totalData + '</b> data pos & file GIS';
        }
    }

    // 3. Render Baris Tabel
    if (pagedData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-muted">' +
            '<i class="fas fa-info-circle fa-2x text-gray-300 mb-2 d-block"></i>' +
            '<div class="font-weight-bold">Tidak ada data yang cocok dengan kriteria filter saat ini.</div>' +
            '<small>Coba pilih kategori lain atau reset kata kunci pencarian.</small>' +
            '</td></tr>';
        if (paginationUl) paginationUl.innerHTML = '';
        return;
    }

    var frag = document.createDocumentFragment();
    pagedData.forEach(function(row, idx) {
        var tr = document.createElement('tr');
        var rowNumber = startIdx + idx + 1;
        var latStr = (row.lat !== null && !isNaN(row.lat)) ? Number(row.lat).toFixed(5) : null;
        var lngStr = (row.lng !== null && !isNaN(row.lng)) ? Number(row.lng).toFixed(5) : null;
        var coordBadge = (latStr && lngStr)
            ? '<span class="badge border font-weight-bold px-2 py-1" style="font-size:11.5px; font-family:Consolas, Monaco, monospace; background:#f8fafc; border-color:#cbd5e1; color:#0f172a;"><i class="fas fa-map-pin text-danger mr-1"></i>' + latStr + ', ' + lngStr + '</span>'
            : '<span class="text-muted small">-</span>';

        var displayTitle = row.title || row.name;

        tr.innerHTML =
            '<td class="text-center font-weight-bold text-muted align-middle">' + rowNumber + '</td>' +
            '<td class="align-middle">' +
                '<div class="font-weight-bold text-dark" style="font-size:13px;">' + displayTitle + '</div>' +
                '<small class="text-muted"><i class="fas fa-map-marker-alt text-primary mr-1"></i>' + row.kabupaten + ' &bull; <span style="font-family:monospace; font-size:10.5px;">' + row.fileName + '</span></small>' +
            '</td>' +
            '<td class="align-middle">' + row.categoryBadge + '</td>' +
            '<td class="text-center align-middle">' + coordBadge + '</td>' +
            '<td class="text-center align-middle">' +
                '<button type="button" class="btn btn-sm btn-outline-primary font-weight-bold px-3 py-1 shadow-sm btn-focus-row" style="font-size:11.5px; border-radius:6px;" title="Fokuskan peta ke objek ini">' +
                    '<i class="fas fa-crosshairs mr-1"></i> Fokus' +
                '</button>' +
            '</td>';

        var btn = tr.querySelector('.btn-focus-row');
        btn.addEventListener('click', function() {
            // 1. Pastikan layer kategori aktif & tampil di peta
            var layerMap = {
                'baku': layerBaku,
                'fungsional': layerFungsional,
                'potensial': layerPotensial,
                'saluran': layerSaluran,
                'bangunan': layerBangunan,
                'ws_kapuas': layerWsKapuas
            };
            if (layerMap[row.categoryType] && !map.hasLayer(layerMap[row.categoryType])) {
                map.addLayer(layerMap[row.categoryType]);
                var chk = document.getElementById('chkLayer' + row.categoryType.charAt(0).toUpperCase() + row.categoryType.slice(1));
                if (chk) chk.checked = true;
            }

            // 2. Smooth scroll otomatis ke atas menuju peta
            var mapEl = document.getElementById('map') || document.querySelector('.bws-leaflet-card');
            if (mapEl) {
                var targetTop = mapEl.getBoundingClientRect().top + window.pageYOffset - 85;
                window.scrollTo({ top: targetTop, behavior: 'smooth' });
            }

            // 3. Zoom / Terbang ke poligon atau titik koordinat secara akurat
            var targetLatLng = [row.lat, row.lng];
            if (row.categoryType === 'bangunan' && row.lat && row.lng && !isNaN(row.lat) && !isNaN(row.lng)) {
                map.flyTo(targetLatLng, 15, { animate: true, duration: 0.8 });
            } else if (row.bounds && row.bounds.isValid && row.bounds.isValid()) {
                map.flyToBounds(row.bounds, { padding: [60, 60], maxZoom: 15, duration: 0.8 });
            } else if (row.lat && row.lng && !isNaN(row.lat) && !isNaN(row.lng)) {
                map.flyTo(targetLatLng, 15, { animate: true, duration: 0.8 });
            }

            // 4. Buka Card Popup Modern secara otomatis di peta (Persis Gambar 3)
            setTimeout(function() {
                if (row.firstLayer && typeof row.firstLayer.openPopup === 'function') {
                    row.firstLayer.openPopup();
                } else if (row.popupHtml && row.lat && row.lng) {
                    L.popup({ maxWidth: 320, autoPan: false, className: 'bws-leaflet-popup' })
                        .setLatLng(targetLatLng)
                        .setContent(row.popupHtml)
                        .openOn(map);
                } else if (row.layerObj && row.layerObj.openPopup) {
                    row.layerObj.openPopup();
                }
            }, 550);
        });

        frag.appendChild(tr);
    });
    tbody.innerHTML = '';
    tbody.appendChild(frag);

    // 4. Render Tombol Navigasi Pagination
    if (paginationUl) {
        if (totalPages <= 1) {
            paginationUl.innerHTML = '';
            return;
        }

        var pagHtml = '';

        // Tombol Prev
        pagHtml += '<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') + '">' +
            '<a class="page-link" href="javascript:void(0)" onclick="goToPage(' + (currentPage - 1) + ')" aria-label="Sebelumnya">&laquo;</a>' +
        '</li>';

        // Nomor Halaman
        for (var p = 1; p <= totalPages; p++) {
            if (p === 1 || p === totalPages || (p >= currentPage - 2 && p <= currentPage + 2)) {
                pagHtml += '<li class="page-item ' + (p === currentPage ? 'active' : '') + '">' +
                    '<a class="page-link" href="javascript:void(0)" onclick="goToPage(' + p + ')">' + p + '</a>' +
                '</li>';
            } else if (p === currentPage - 3 || p === currentPage + 3) {
                pagHtml += '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
            }
        }

        // Tombol Next
        pagHtml += '<li class="page-item ' + (currentPage === totalPages ? 'disabled' : '') + '">' +
            '<a class="page-link" href="javascript:void(0)" onclick="goToPage(' + (currentPage + 1) + ')" aria-label="Selanjutnya">&raquo;</a>' +
        '</li>';

        paginationUl.innerHTML = pagHtml;
    }
}

// Fungsi cari baris di tabel dari tombol popup card
window.highlightInTable = function(title, fileName, categoryType) {
    // 1. Pastikan view menampilkan tabel (jika sedang mode peta penuh)
    if (typeof switchViewSegment === 'function') {
        switchViewSegment('both');
    }

    // 2. Jika filter kategori tabel sedang aktif dan berbeda dari objek yang diklik, reset ke 'all'
    if (categoryType && currentCategoryFilter !== 'all' && currentCategoryFilter !== categoryType) {
        if (typeof filterTableCategory === 'function') {
            filterTableCategory('all');
        } else {
            currentCategoryFilter = 'all';
        }
    }

    // 3. Reset dropdown wilayah jika sedang memfilter wilayah lain
    if (currentWilayahFilter !== 'all') {
        var selWil = document.getElementById('selectFilterWilayah');
        if (selWil) selWil.value = 'all';
        currentWilayahFilter = 'all';
    }

    // 4. Masukkan nama/judul ke input pencarian tabel dan jalankan filter
    var searchText = title || fileName || '';
    var input = document.getElementById('cari-tabel-koordinat');
    if (input) {
        input.value = searchText;
    }
    filterTabelKoordinat(searchText);

    // 5. Scroll halus ke tabel
    var tableSec = document.getElementById('section-table');
    if (tableSec) {
        var targetY = tableSec.getBoundingClientRect().top + window.pageYOffset - 80;
        window.scrollTo({ top: targetY, behavior: 'smooth' });
    }
};
