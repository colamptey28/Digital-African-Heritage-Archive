<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>West African Countries Map</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css" type="text/css"/>

    <style>
        body { margin: 0; padding: 0; font-family: 'Arial', sans-serif; }
        #map { position: absolute; top: 50px; bottom: 0; width: 100%; height: calc(100vh - 50px); }
        .fixed-top { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background-color: #fff; padding: 10px; box-sizing: border-box; }
        .back-button { position: absolute; top: 10px; left: 10px; z-index: 999; }
        .back-button button { background-color: #007BFF; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; font-weight: bold; }
        .back-button button:hover { background-color: #0056b3; }
        .mapboxgl-popup {
            max-width: 400px;
            font: 14px/22px 'Helvetica Neue', Arial, Helvetica, sans-serif;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .marker {
            background-size: cover;
            width: 60px;
            height: 60px;
            cursor: pointer;
            border-radius: 50%;
            border: 2px solid #ffffff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .marker.small {
            width: 40px;
            height: 40px;
        }
        .marker:hover {
            transform: scale(1.15);
        }
        .flag {
            width: 40px;
            height: auto;
            margin-bottom: 15px;
        }
        .statistic {
            margin-top: 15px;
            color: #666666;
            font-size: 12px;
        }
        #geocoder { position: absolute; top: 10px; right: 10px; z-index: 999; }
    </style>
</head>
<body>

<div class="fixed-top">
    <div class="back-button">
        <button onclick="goBack()">Back</button>
    </div>
    <div id="geocoder" class="geocoder"></div>
</div>

<div id="map"></div>

<script>
    function goBack() {
        window.history.back();
    }

    mapboxgl.accessToken = 'pk.eyJ1IjoiY2FsZWItbCIsImEiOiJjbHVnMGEydzMxb3gyMmluczM0NjNjemM2In0.H3WE8GXnzVajEMl-MlhRXQ';

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [-3, 8],
        zoom: 3,
        maxBounds: [
            [-25, -40],
            [60, 40]
        ]
    });

    map.addControl(new mapboxgl.NavigationControl());

    const geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl,
        placeholder: 'Search for a place...',
        countries: 'gh,ng,sn,ml,bf,sl,gm'
    });

    document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

    const fetchWikipediaSummary = async (pageTitle) => {
        const response = await fetch(`https://en.wikipedia.org/w/api.php?action=query&format=json&prop=extracts&exintro=true&titles=${pageTitle}&origin=*`);
        const data = await response.json();
        const pages = data.query.pages;
        const pageId = Object.keys(pages)[0];
        const summary = pages[pageId].extract.split('. ')[0] + '.';
        return summary;
    };

    const fetchWikipediaImage = async (pageTitle) => {
        const response = await fetch(`https://en.wikipedia.org/w/api.php?action=query&format=json&prop=pageimages&titles=${pageTitle}&origin=*`);
        const data = await response.json();
        const pages = data.query.pages;
        const pageId = Object.keys(pages)[0];
        const imageUrl = pages[pageId].thumbnail ? pages[pageId].thumbnail.source : null;
        return imageUrl;
    };

    map.on('load', async () => {
        const westAfricanCountries = [
            { name: 'Nigeria', code: 'NG' },
            { name: 'Ghana', code: 'GH' },
            { name: 'Senegal', code: 'SN' },
            { name: 'Mali', code: 'ML' },
            { name: 'Burkina Faso', code: 'BF' },
            { name: 'Sierra Leone', code: 'SL' },
            { name: 'The Gambia', code: 'GM' }
        ];

        const ghanaHistoricalSites = [
            {
                name: 'Accra',
                description: 'Capital city of Ghana and home to Jamestown, Ussher Fort, and the Kwame Nkrumah Mausoleum. Accra is a bustling city with vibrant markets, lively nightlife, and beautiful beaches along the Gulf of Guinea.'
            },
            {
                name: 'Kumasi',
                description: 'Known as the "Garden City" due to its many species of flowers and plants. Kumasi is home to the Manhyia Palace, the Okomfo Anokye Sword, and the largest open-air market in West Africa, Kejetia Market.'
            },
            {
                name: 'Cape Coast',
                description: 'Cape Coast is known for its role in the transatlantic slave trade. Key attractions include Cape Coast Castle, one of about forty "slave castles" built on the Gold Coast of West Africa by European traders.'
            },
            {
                name: 'Elmina',
                description: 'Elmina is home to St. George\'s Castle, a UNESCO World Heritage Site and one of the oldest European buildings in existence south of the Sahara. The town is also known for its fishing port and beautiful beaches.'
            },
            {
                name: 'Afadjato Mountain',
                description: 'Afadjato Mountain is the highest mountain in Ghana, standing at 885 meters above sea level. It is known for its hiking trails, panoramic views of the Volta Region, and its significance in Ghanaian folklore.'
            }
        ];

        const fetchCountryData = async (countryCode) => {
            const response = await fetch(`https://restcountries.com/v3.1/alpha/${countryCode}`);
            const data = await response.json();
            return data[0];
        };

        const fetchCoordinates = async (placeName) => {
            const response = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${placeName}.json?access_token=${mapboxgl.accessToken}&country=gh`);
            const data = await response.json();
            if (data.features && data.features.length > 0) {
                return data.features[0].center;
            }
            return null;
        };

        for (const country of westAfricanCountries) {
            const countryData = await fetchCountryData(country.code);

            const el = document.createElement('div');
            el.className = 'marker';
            el.style.backgroundImage = `url(${countryData.flags.png})`;

            let infoText = '';
            if (countryData.languages) {
                infoText += `Languages: ${Object.values(countryData.languages).join(', ')}<br>`;
            }
            if (countryData.demonym) {
                infoText += `Demonym: ${countryData.demonym}<br>`;
            }
            if (countryData.capital) {
                infoText += `Capital: ${countryData.capital}<br>`;
            }
            if (countryData.population) {
                infoText += `Population: ${countryData.population.toLocaleString()}<br>`;
            }
            if (countryData.area) {
                infoText += `Area: ${countryData.area.toLocaleString()} km²<br>`;
            }

            new mapboxgl.Marker(el)
                .setLngLat(countryData.latlng.reverse())
                .setPopup(new mapboxgl.Popup({ offset: 25 })
                    .setHTML(`
                    <div style="text-align:center;">
                        <img class="flag" src="${countryData.flags.png}" alt="${country.name} Flag">
                        <h3>${country.name}</h3>
                        <p>${infoText}</p>
                    </div>
                    `))
                .addTo(map);
        }

        for (const site of ghanaHistoricalSites) {
            const coordinates = await fetchCoordinates(site.name);
            const imageUrl = await fetchWikipediaImage(site.name);

            const el = document.createElement('div');
            el.className = 'marker small';
            el.style.backgroundImage = `url(${imageUrl})`;

            new mapboxgl.Marker(el)
                .setLngLat(coordinates)
                .setPopup(new mapboxgl.Popup({ offset: 25 })
                    .setHTML(`
                    <div style="text-align:center;">
                        <img class="flag" src="${imageUrl}" alt="${site.name} Image">
                        <h3>${site.name}</h3>
                        <p>${site.description}</p>
                    </div>
                    `))
                .addTo(map);
        }
    });

    geocoder.on('result', async (ev) => {
        const coordinates = ev.result.center;
        const placeName = ev.result.text;

        const summary = await fetchWikipediaSummary(placeName);
        const imageUrl = await fetchWikipediaImage(placeName);

        const el = document.createElement('div');
        el.className = 'marker small';
        el.style.backgroundImage = `url(${imageUrl})`;

        new mapboxgl.Marker(el)
            .setLngLat(coordinates)
            .setPopup(new mapboxgl.Popup({ offset: 25 })
                .setHTML(`
                <div style="text-align:center;">
                    <img class="flag" src="${imageUrl}" alt="${placeName} Image">
                    <h3>${placeName}</h3>
                    <p>${summary}</p>
                </div>
                `))
            .addTo(map);

        map.flyTo({
            center: coordinates,
            zoom: 12,
            speed: 1,
            curve: 1,
            easing(t) {
                return t;
            }
        });
    });

</script>

</body>
</html>
