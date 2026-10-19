import fs from 'fs';
import path from 'path';
import proj4 from 'proj4';

proj4.defs('EPSG:32649', '+proj=utm +zone=49 +datum=WGS84 +units=m +no_defs');
proj4.defs('EPSG:32749', '+proj=utm +zone=49 +south +datum=WGS84 +units=m +no_defs');
proj4.defs('ESRI:54034', '+proj=cea +lat_ts=0 +lon_0=0 +x_0=0 +y_0=0 +datum=WGS84 +units=m +no_defs');

function convertCoordinate(coord, fromProj) {
    if (!fromProj || fromProj === 'EPSG:4326') return coord;
    try {
        const [lng, lat] = proj4(fromProj, 'EPSG:4326', [coord[0], coord[1]]);
        return [parseFloat(lng.toFixed(6)), parseFloat(lat.toFixed(6))];
    } catch (e) {
        return coord;
    }
}

function transformCoords(coords, fromProj, depth) {
    if (depth === 0) {
        return convertCoordinate(coords, fromProj);
    }
    return coords.map(c => transformCoords(c, fromProj, depth - 1));
}

function esriToGeoJSON(esri) {
    const wkid = esri.spatialReference?.wkid || esri.spatialReference?.latestWkid;
    let fromProj = null;
    if (wkid === 32649) fromProj = 'EPSG:32649';
    else if (wkid === 32749) fromProj = 'EPSG:32749';
    else if (wkid === 54034) fromProj = 'ESRI:54034';
    else if (wkid === 4326) fromProj = 'EPSG:4326';

    const features = (esri.features || []).map((feat, idx) => {
        let geometry = null;
        const geom = feat.geometry;
        if (!geom) return null;

        if (esri.geometryType === 'esriGeometryPolygon' && geom.rings) {
            geometry = {
                type: 'Polygon',
                coordinates: transformCoords(geom.rings, fromProj, 2)
            };
        } else if (esri.geometryType === 'esriGeometryPolyline' && geom.paths) {
            const transformedPaths = transformCoords(geom.paths, fromProj, 2);
            geometry = {
                type: transformedPaths.length === 1 ? 'LineString' : 'MultiLineString',
                coordinates: transformedPaths.length === 1 ? transformedPaths[0] : transformedPaths
            };
        } else if (esri.geometryType === 'esriGeometryPoint') {
            let pt = [geom.x, geom.y];
            if (fromProj) pt = convertCoordinate(pt, fromProj);
            geometry = {
                type: 'Point',
                coordinates: pt
            };
        }

        if (!geometry) return null;

        return {
            type: 'Feature',
            id: feat.attributes?.FID ?? idx,
            properties: feat.attributes || {},
            geometry: geometry
        };
    }).filter(f => f !== null);

    return {
        type: 'FeatureCollection',
        crs: {
            type: 'name',
            properties: { name: 'urn:ogc:def:crs:OGC:1.3:CRS84' }
        },
        features: features
    };
}

const dir = 'public/geojson';
const files = fs.readdirSync(dir);
let converted = 0;
let skipped = 0;

for (const file of files) {
    if (!file.endsWith('.json') && !file.endsWith('.geojson')) continue;
    const fullPath = path.join(dir, file);
    const content = fs.readFileSync(fullPath, 'utf8');

    // Jika sudah FeatureCollection (seperti WS KAPUAS), lewati
    if (content.includes('"FeatureCollection"') && !content.includes('"geometryType"')) {
        console.log(`[SKIP - ALREADY GEOJSON] ${file}`);
        skipped++;
        continue;
    }

    try {
        const parsed = JSON.parse(content);
        if (parsed.geometryType && parsed.features) {
            const geojson = esriToGeoJSON(parsed);
            fs.writeFileSync(fullPath, JSON.stringify(geojson));
            console.log(`[CONVERTED] ${file} -> ${geojson.features.length} features`);
            converted++;
        } else {
            console.log(`[UNKNOWN FORMAT] ${file}`);
            skipped++;
        }
    } catch (e) {
        console.error(`[ERROR] ${file}:`, e.message);
    }
}

console.log(`\nDONE! Converted: ${converted}, Skipped: ${skipped}`);
