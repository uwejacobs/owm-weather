<?php
// To prevent calling the plugin directly
if (!function_exists('add_action')) {
    echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
    exit;
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//SVG Icon animation
// original viewbox: "15 15 70 70"
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_sun()
{
    $id = owmw_unique_id_esc("sunFillClip");
    return '
		<svg
        id="sun"
        class="climacon climacon-sun"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path
            d="M0,0v100h100V0H0z M50.001,57.999c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C57.999,54.417,54.418,57.999,50.001,57.999z"
            />
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-sun">
            <g class="climacon-componentWrap climacon-componentWrap-sun">
                <g class="climacon-componentWrap climacon-componentWrap-sunSpoke">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                    d="M72.03,51.999h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S73.136,51.999,72.03,51.999z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northEast"
                    d="M64.175,38.688c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L64.175,38.688z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                    d="M50.034,34.002c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C52.034,33.106,51.136,34.002,50.034,34.002z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northWest"
                    d="M35.893,38.688l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C37.94,39.469,36.674,39.469,35.893,38.688z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-west"
                    d="M34.034,50c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C33.14,48,34.034,48.896,34.034,50z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-southWest"
                    d="M35.893,61.312c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L35.893,61.312z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-south"
                    d="M50.034,65.998c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C48.034,66.893,48.929,65.998,50.034,65.998z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-southEast"
                    d="M64.175,61.312l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C62.126,60.531,63.392,60.531,64.175,61.312z"
                    />
                </g>
                <g class="climacon-componentWrap climacon-componentWrap_sunBody" clip-path="url(#'.esc_attr($id).')">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="50.034"
                    cy="50"
                    r="11.999"
                    />
                </g>
            </g>
        </g>
    </svg>
	<!-- sun -->';
}

function owmw_moon()
{
    $id = owmw_unique_id_esc("moonFillClip");
    return '
		<svg
        id="moon"
        class="climacon climacon-moon"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M50,57.999c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C56.979,55.344,53.802,57.999,50,57.999z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-moon">
            <g class="climacon-componentWrap climacon-componentWrap-moon" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_moon"
                d="M50,61.998c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C61.998,56.626,56.626,61.998,50,61.998z"/>
            </g>
        </g>
    </svg><!-- moon --> ';
}

function owmw_wind()
{
    return '
		<svg
        id="wind"
        class="climacon climacon-wind"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-wind">
            <g class="climacon-wrapperComponent climacon-componentWrap-wind">
                <path
                class="climacon-component climacon-component-stroke climacon-component-wind climacon-component-wind_curl"
                d="M65.999,52L65.999,52h-3c-1.104,0-2-0.895-2-1.999c0-1.104,0.896-2,2-2h3c1.104,0,2-0.896,2-1.999c0-1.105-0.896-2-2-2s-2-0.896-2-2s0.896-2,2-2c0.138,0,0.271,0.014,0.401,0.041c3.121,0.211,5.597,2.783,5.597,5.959C71.997,49.314,69.312,52,65.999,52z"/>
                <path 
                class="climacon-component climacon-component-stroke climacon-component-wind"
                d="M55.999,48.001h-2h-6.998H34.002c-1.104,0-1.999,0.896-1.999,2c0,1.104,0.895,1.999,1.999,1.999h2h3.999h3h4h3h3.998h2c3.313,0,6,2.688,6,6c0,3.176-2.476,5.748-5.597,5.959C56.271,63.986,56.139,64,55.999,64c-1.104,0-2-0.896-2-2c0-1.105,0.896-2,2-2s2-0.896,2-2s-0.896-2-2-2h-2h-3.998h-3h-4h-3h-3.999h-2c-3.313,0-5.999-2.686-5.999-5.999c0-3.175,2.475-5.747,5.596-5.959c0.131-0.026,0.266-0.04,0.403-0.04l0,0h12.999h6.998h2c1.104,0,2-0.896,2-2s-0.896-2-2-2s-2-0.895-2-2c0-1.104,0.896-2,2-2c0.14,0,0.272,0.015,0.403,0.041c3.121,0.211,5.597,2.783,5.597,5.959C61.999,45.314,59.312,48.001,55.999,48.001z"/>
            </g>
        </g>
    </svg><!-- wind --> ';
}

function owmw_tornado()
{
    return '
		<svg
        id="tornado"
        class="climacon climacon-tornado"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-tornado">
            <g class="climacon-componentWrap climacon-componentWrap-tornado">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_tornadoLine"
                d="M68.997,36.459H31.002c-1.104,0-2-0.896-2-1.999c0-1.104,0.896-2,2-2h37.995c1.104,0,2,0.896,2,2C70.997,35.563,70.102,36.459,68.997,36.459z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_tornadoLine"
                d="M35.002,40.459h29.996c1.104,0,2,0.896,2,2s-0.896,1.999-2,1.999H35.002c-1.104,0-2-0.896-2-1.999C33.002,41.354,33.898,40.459,35.002,40.459z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_tornadoLine"
                d="M39.001,48.458h21.998c1.104,0,1.999,0.896,1.999,1.999c0,1.104-0.896,2-1.999,2H39.001c-1.104,0-1.999-0.896-1.999-2C37.002,49.354,37.897,48.458,39.001,48.458z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_tornadoLine"
                d="M47,64.456h5.999c1.104,0,2,0.896,2,1.999s-0.896,2-2,2H47c-1.104,0-2-0.896-2-2S45.896,64.456,47,64.456z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_tornadoLine"
                d="M40.869,58.456c0-1.104,0.896-1.999,2-1.999h13.998c1.104,0,2,0.896,2,1.999c0,1.104-0.896,2-2,2H42.869C41.765,60.456,40.869,59.561,40.869,58.456z"/>
            </g>
        </g>
    </svg><!-- tornado --> ';
}

function owmw_cloud()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    return '
		<svg
        id="cloud"
        class="climacon climacon-cloud"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloud">
            <g class="climacon-componentWrap climacon-componentWrap_cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M43.945,65.639c-8.835,0-15.998-7.162-15.998-15.998c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.168,65.639,47.143,65.639,43.945,65.639z"/>
            </g>
        </g>
    </svg><!-- cloud --> ';
}

function owmw_cloudFill()
{
    return '
		<svg
        id="cloudFill"
        class="climacon climacon-cloudFill"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-cloud">
            <g class="climacon-componentWrap climacon-componentWrap_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M43.945,65.639c-8.835,0-15.998-7.162-15.998-15.998c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.168,65.639,47.143,65.639,43.945,65.639z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                fill="#FFFFFF"
                d="M59.943,61.639c4.418,0,8-3.582,8-7.998c0-4.417-3.582-8-8-8c-1.601,0-3.082,0.481-4.334,1.291c-1.23-5.316-5.973-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.999c0,6.626,5.372,11.998,11.998,11.998C47.562,61.639,56.924,61.639,59.943,61.639z"/>
            </g>
        </g>
    </svg><!-- cloudFill  --> ';
}

function owmw_cloudSun()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("sunCloudFillClip");
    return '
		<svg
        id="cloudSun"
        class="climacon climacon-cloudSun"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path
            d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-cloudSun-iconWrap">
            <g clip-path="url(#'.esc_attr($id).')">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud"  >
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-orth"
                        d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z"/>
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#'.esc_attr($id1).')">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M44.033,65.641c-8.836,0-15.999-7.162-15.999-15.998c0-8.835,7.163-15.998,15.999-15.998c6.006,0,11.233,3.312,13.969,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.26,65.641,47.23,65.641,44.033,65.641z"/>
            </g>
        </g>
    </svg><!-- cloudSun --> ';
}

function owmw_cloudMoon()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("moonCloudFillClip");
    return '
		<svg
        id="cloudMoon"
        class="climacon climacon-cloudMoon"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path
            d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudMoon">
            <g clip-path="url(#'.esc_attr($id).')">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#'.esc_attr($id1).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_moon"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M44.033,65.641c-8.836,0-15.999-7.162-15.999-15.998c0-8.835,7.163-15.998,15.999-15.998c6.006,0,11.233,3.312,13.969,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.26,65.641,47.23,65.641,44.033,65.641z"/>
            </g>
        </g>
    </svg><!-- cloudMoon --> ';
}

function owmw_cloudDrizzle()
{
    return '
		<svg
        id="cloudDrizzle"
        class="climacon climacon-cloudDrizzle"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzle">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                d="M42.001,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2.001-0.895-2.001-2v-3.998C40,54.538,40.896,53.644,42.001,53.644z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M49.999,53.644c1.104,0,2,0.896,2,2v4c0,1.104-0.896,2-2,2s-1.998-0.896-1.998-2v-4C48.001,54.54,48.896,53.644,49.999,53.644z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M57.999,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2-0.895-2-2v-3.998C55.999,54.538,56.894,53.644,57.999,53.644z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.944v-4.381c2.387-1.386,3.998-3.961,3.998-6.92c0-4.418-3.58-8-7.998-8c-1.603,0-3.084,0.481-4.334,1.291c-1.232-5.316-5.973-9.29-11.664-9.29c-6.628,0-11.999,5.372-11.999,12c0,3.549,1.55,6.729,3.998,8.926v4.914c-4.776-2.769-7.998-7.922-7.998-13.84c0-8.836,7.162-15.999,15.999-15.999c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.336-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.997,58.864,68.655,63.296,63.999,64.944z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzle --> ';
}

function owmw_cloudDrizzleSun()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("sunCloudFillClip");
    $id2 = owmw_unique_id_esc("cloudSunFillClip");
    return '
		<svg
        id="cloudDrizzleSun"
        class="climacon climacon-cloudDrizzleSun"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id2).'">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleSun">
            <g clip-path="url(#'.esc_attr($id2).')">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud">
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z"/>
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#'.esc_attr($id1).')">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                d="M42.001,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2.001-0.895-2.001-2v-3.998C40,54.538,40.896,53.644,42.001,53.644z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M49.999,53.644c1.104,0,2,0.896,2,2v4c0,1.104-0.896,2-2,2s-1.998-0.896-1.998-2v-4C48.001,54.54,48.896,53.644,49.999,53.644z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M57.999,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2-0.895-2-2v-3.998C55.999,54.538,56.894,53.644,57.999,53.644z"/>
            </g>

            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.944v-4.381c2.387-1.386,3.998-3.961,3.998-6.92c0-4.418-3.58-8-7.998-8c-1.603,0-3.084,0.481-4.334,1.291c-1.232-5.316-5.973-9.29-11.664-9.29c-6.628,0-11.999,5.372-11.999,12c0,3.549,1.55,6.729,3.998,8.926v4.914c-4.776-2.769-7.998-7.922-7.998-13.84c0-8.836,7.162-15.999,15.999-15.999c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.336-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.997,58.864,68.655,63.296,63.999,64.944z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleSun --> ';
}

function owmw_cloudDrizzleMoon()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("moonCloudFillClip");
    return '
		<svg
        id="cloudDrizzleMoon"
        class="climacon climacon-cloudDrizzleMoon"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleMoon">
            <g clip-path="url(#'.esc_attr($id).')">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#'.esc_attr($id1).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_moon"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                d="M42.001,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2.001-0.895-2.001-2v-3.998C40,54.538,40.896,53.644,42.001,53.644z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M49.999,53.644c1.104,0,2,0.896,2,2v4c0,1.104-0.896,2-2,2s-1.998-0.896-1.998-2v-4C48.001,54.54,48.896,53.644,49.999,53.644z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M57.999,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2-0.895-2-2v-3.998C55.999,54.538,56.894,53.644,57.999,53.644z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.944v-4.381c2.387-1.386,3.998-3.961,3.998-6.92c0-4.418-3.58-8-7.998-8c-1.603,0-3.084,0.481-4.334,1.291c-1.232-5.316-5.973-9.29-11.664-9.29c-6.628,0-11.999,5.372-11.999,12c0,3.549,1.55,6.729,3.998,8.926v4.914c-4.776-2.769-7.998-7.922-7.998-13.84c0-8.836,7.162-15.999,15.999-15.999c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.336-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.997,58.864,68.655,63.296,63.999,64.944z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleMoon --> ';
}

function owmw_cloudDrizzleMoonFill()
{
    return '
		<svg
        id="cloudDrizzleMoonFill"
        class="climacon climacon-cloudDrizzleMoonFill"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleMoonFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_moon"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                d="M42.001,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2.001-0.895-2.001-2v-3.998C40,54.538,40.896,53.644,42.001,53.644z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M49.999,53.644c1.104,0,2,0.896,2,2v4c0,1.104-0.896,2-2,2s-1.998-0.896-1.998-2v-4C48.001,54.54,48.896,53.644,49.999,53.644z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M57.999,53.644c1.104,0,2,0.896,2,2v3.998c0,1.105-0.896,2-2,2c-1.105,0-2-0.895-2-2v-3.998C55.999,54.538,56.894,53.644,57.999,53.644z"/>
            </g>
            <g class="climacon-componentWrap climacon-componentWrap_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M43.945,65.639c-8.835,0-15.998-7.162-15.998-15.998c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.168,65.639,47.143,65.639,43.945,65.639z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                fill="#FFFFFF"
                d="M59.943,61.639c4.418,0,8-3.582,8-7.998c0-4.417-3.582-8-8-8c-1.601,0-3.082,0.481-4.334,1.291c-1.23-5.316-5.973-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.999c0,6.626,5.372,11.998,11.998,11.998C47.562,61.639,56.924,61.639,59.943,61.639z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleMoonFill --> ';
}

function owmw_cloudDrizzleAlt()
{
    return '
		<svg
        id="cloudDrizzleAlt"
        class="climacon climacon-cloudDrizzleAlt"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                d="M56.969,57.672l-2.121,2.121c-1.172,1.172-1.172,3.072,0,4.242c1.17,1.172,3.07,1.172,4.24,0c1.172-1.17,1.172-3.07,0-4.242L56.969,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M50.088,57.672l-2.119,2.121c-1.174,1.172-1.174,3.07,0,4.242c1.17,1.172,3.068,1.172,4.24,0s1.172-3.07,0-4.242L50.088,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M43.033,57.672l-2.121,2.121c-1.172,1.172-1.172,3.07,0,4.242s3.07,1.172,4.244,0c1.172-1.172,1.172-3.07,0-4.242L43.033,57.672z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.943,41.642c-0.696,0-1.369,0.092-2.033,0.205c-2.736-4.892-7.961-8.203-13.965-8.203c-8.835,0-15.998,7.162-15.998,15.997c0,5.992,3.3,11.207,8.177,13.947c0.276-1.262,0.892-2.465,1.873-3.445l0.057-0.057c-3.644-2.061-6.106-5.963-6.106-10.445c0-6.626,5.372-11.998,11.998-11.998c5.691,0,10.433,3.974,11.666,9.29c1.25-0.81,2.732-1.291,4.332-1.291c4.418,0,8,3.581,8,7.999c0,3.443-2.182,6.371-5.235,7.498c0.788,1.146,1.194,2.471,1.222,3.807c4.666-1.645,8.014-6.077,8.014-11.305C71.941,47.014,66.57,41.642,59.943,41.642z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleAlt --> ';
}

function owmw_cloudDrizzleSunAlt()
{
    $id = owmw_unique_id_esc("cloudSunFillClip");
    $id1 = owmw_unique_id_esc("cloudFillClip");
    $id2 = owmw_unique_id_esc("sunCloudFillClip");
    return '
		<svg
        id="cloudDrizzleSunAlt"
        class="climacon climacon-cloudDrizzleSunAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id2).'">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id).'">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleSunAlt">
            <g clip-path="url(#'.esc_attr($id).')">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud">
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z"/>
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#'.esc_attr($id2).')">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                d="M56.969,57.672l-2.121,2.121c-1.172,1.172-1.172,3.072,0,4.242c1.17,1.172,3.07,1.172,4.24,0c1.172-1.17,1.172-3.07,0-4.242L56.969,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M50.088,57.672l-2.119,2.121c-1.174,1.172-1.174,3.07,0,4.242c1.17,1.172,3.068,1.172,4.24,0s1.172-3.07,0-4.242L50.088,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M43.033,57.672l-2.121,2.121c-1.172,1.172-1.172,3.07,0,4.242s3.07,1.172,4.244,0c1.172-1.172,1.172-3.07,0-4.242L43.033,57.672z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#'.esc_attr($id1).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.944v-4.381c2.387-1.386,3.998-3.961,3.998-6.92c0-4.418-3.58-8-7.998-8c-1.603,0-3.084,0.481-4.334,1.291c-1.232-5.316-5.973-9.29-11.664-9.29c-6.628,0-11.999,5.372-11.999,12c0,3.549,1.55,6.729,3.998,8.926v4.914c-4.776-2.769-7.998-7.922-7.998-13.84c0-8.836,7.162-15.999,15.999-15.999c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.336-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.997,58.864,68.655,63.296,63.999,64.944z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleSunAlt --> ';
}

function owmw_cloudDrizzleMoonAlt()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("moonCloudFillClip");
    return '
		<svg
        id="cloudDrizzleMoonAlt"
        class="climacon climacon-cloudDrizzleMoonAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleMoonAlt">
            <g clip-path="url(#'.esc_attr($id).')">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#'.esc_attr($id1).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                d="M56.969,57.672l-2.121,2.121c-1.172,1.172-1.172,3.072,0,4.242c1.17,1.172,3.07,1.172,4.24,0c1.172-1.17,1.172-3.07,0-4.242L56.969,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M50.088,57.672l-2.119,2.121c-1.174,1.172-1.174,3.07,0,4.242c1.17,1.172,3.068,1.172,4.24,0s1.172-3.07,0-4.242L50.088,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M43.033,57.672l-2.121,2.121c-1.172,1.172-1.172,3.07,0,4.242s3.07,1.172,4.244,0c1.172-1.172,1.172-3.07,0-4.242L43.033,57.672z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.943,41.642c-0.696,0-1.369,0.092-2.033,0.205c-2.736-4.892-7.961-8.203-13.965-8.203c-8.835,0-15.998,7.162-15.998,15.997c0,5.992,3.3,11.207,8.177,13.947c0.276-1.262,0.892-2.465,1.873-3.445l0.057-0.057c-3.644-2.061-6.106-5.963-6.106-10.445c0-6.626,5.372-11.998,11.998-11.998c5.691,0,10.433,3.974,11.666,9.29c1.25-0.81,2.732-1.291,4.332-1.291c4.418,0,8,3.581,8,7.999c0,3.443-2.182,6.371-5.235,7.498c0.788,1.146,1.194,2.471,1.222,3.807c4.666-1.645,8.014-6.077,8.014-11.305C71.941,47.014,66.57,41.642,59.943,41.642z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleMoonAlt --> ';
}

function owmw_cloudDrizzleMoonFillAlt()
{
    return '
		<svg
        id="cloudDrizzleMoonFillAlt"
        class="climacon climacon-cloudDrizzleMoonFillAlt"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleMoonFillAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                d="M56.969,57.672l-2.121,2.121c-1.172,1.172-1.172,3.072,0,4.242c1.17,1.172,3.07,1.172,4.24,0c1.172-1.17,1.172-3.07,0-4.242L56.969,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M50.088,57.672l-2.119,2.121c-1.174,1.172-1.174,3.07,0,4.242c1.17,1.172,3.068,1.172,4.24,0s1.172-3.07,0-4.242L50.088,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M43.033,57.672l-2.121,2.121c-1.172,1.172-1.172,3.07,0,4.242s3.07,1.172,4.244,0c1.172-1.172,1.172-3.07,0-4.242L43.033,57.672z"/>
            </g>
            <g class="climacon-componentWrap climacon-componentWrap_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M43.945,65.639c-8.835,0-15.998-7.162-15.998-15.998c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.168,65.639,47.143,65.639,43.945,65.639z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                fill="#FFFFFF"
                d="M59.943,61.639c4.418,0,8-3.582,8-7.998c0-4.417-3.582-8-8-8c-1.601,0-3.082,0.481-4.334,1.291c-1.23-5.316-5.973-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.999c0,6.626,5.372,11.998,11.998,11.998C47.562,61.639,56.924,61.639,59.943,61.639z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleMoonFillAlt --> ';
}

function owmw_cloudRain()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    return '
		<svg
        id="cloudRain"
        class="climacon climacon-cloudRain"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRain">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-rain">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- left"
                d="M41.946,53.641c1.104,0,1.999,0.896,1.999,2v15.998c0,1.105-0.895,2-1.999,2s-2-0.895-2-2V55.641C39.946,54.537,40.842,53.641,41.946,53.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- middle"
                d="M49.945,57.641c1.104,0,2,0.896,2,2v15.998c0,1.104-0.896,2-2,2s-2-0.896-2-2V59.641C47.945,58.535,48.841,57.641,49.945,57.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- right"
                d="M57.943,53.641c1.104,0,2,0.896,2,2v15.998c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2V55.641C55.943,54.537,56.84,53.641,57.943,53.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- left"
                d="M41.946,53.641c1.104,0,1.999,0.896,1.999,2v15.998c0,1.105-0.895,2-1.999,2s-2-0.895-2-2V55.641C39.946,54.537,40.842,53.641,41.946,53.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- middle"
                d="M49.945,57.641c1.104,0,2,0.896,2,2v15.998c0,1.104-0.896,2-2,2s-2-0.896-2-2V59.641C47.945,58.535,48.841,57.641,49.945,57.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- right"
                d="M57.943,53.641c1.104,0,2,0.896,2,2v15.998c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2V55.641C55.943,54.537,56.84,53.641,57.943,53.641z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent_cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.943,64.941v-4.381c2.389-1.384,4-3.961,4-6.92c0-4.417-3.582-8-8-8c-1.601,0-3.082,0.48-4.334,1.291c-1.23-5.317-5.973-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.998c0,3.549,1.551,6.728,4,8.924v4.916c-4.777-2.768-8-7.922-8-13.84c0-8.835,7.163-15.997,15.998-15.997c6.004,0,11.229,3.311,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.372,11.998,12C71.941,58.863,68.602,63.293,63.943,64.941z"/>
            </g>
        </g>
    </svg><!-- cloudRain --> ';
}

function owmw_cloudRainSun()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("sunCloudFillClip");
    $id2 = owmw_unique_id_esc("cloudSunFillClip");
    return '
		<svg
        id="cloudRainSun"
        class="climacon climacon-cloudRainSun"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id2).'">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainSun">
            <g clip-path="url(#'.esc_attr($id2).')">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud">
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z"/>
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#'.esc_attr($id1).')">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-rain">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- left"
                d="M41.946,53.641c1.104,0,1.999,0.896,1.999,2v15.998c0,1.105-0.895,2-1.999,2s-2-0.895-2-2V55.641C39.946,54.537,40.842,53.641,41.946,53.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- middle"
                d="M49.945,57.641c1.104,0,2,0.896,2,2v15.998c0,1.104-0.896,2-2,2s-2-0.896-2-2V59.641C47.945,58.535,48.841,57.641,49.945,57.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- right"
                d="M57.943,53.641c1.104,0,2,0.896,2,2v15.998c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2V55.641C55.943,54.537,56.84,53.641,57.943,53.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- left"
                d="M41.946,53.641c1.104,0,1.999,0.896,1.999,2v15.998c0,1.105-0.895,2-1.999,2s-2-0.895-2-2V55.641C39.946,54.537,40.842,53.641,41.946,53.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- middle"
                d="M49.945,57.641c1.104,0,2,0.896,2,2v15.998c0,1.104-0.896,2-2,2s-2-0.896-2-2V59.641C47.945,58.535,48.841,57.641,49.945,57.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- right"
                d="M57.943,53.641c1.104,0,2,0.896,2,2v15.998c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2V55.641C55.943,54.537,56.84,53.641,57.943,53.641z"/>
            </g>

            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.943,64.941v-4.381c2.389-1.384,4-3.961,4-6.92c0-4.417-3.582-8-8-8c-1.601,0-3.082,0.48-4.334,1.291c-1.23-5.317-5.973-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.998c0,3.549,1.551,6.728,4,8.924v4.916c-4.777-2.768-8-7.922-8-13.84c0-8.835,7.163-15.997,15.998-15.997c6.004,0,11.229,3.311,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.372,11.998,12C71.941,58.863,68.602,63.293,63.943,64.941z"/>
            </g>
        </g>
    </svg><!-- cloudRainSun --> ';
}

function owmw_cloudRainMoon()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("moonCloudFillClip");
    return '
		<svg
        id="cloudRainMoon"
        class="climacon climacon-cloudRainMoon"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainMoon">
            <g clip-path="url(#'.esc_attr($id).')">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#'.esc_attr($id1).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-rain">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- left"
                d="M41.946,53.641c1.104,0,1.999,0.896,1.999,2v15.998c0,1.105-0.895,2-1.999,2s-2-0.895-2-2V55.641C39.946,54.537,40.842,53.641,41.946,53.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- middle"
                d="M49.945,57.641c1.104,0,2,0.896,2,2v15.998c0,1.104-0.896,2-2,2s-2-0.896-2-2V59.641C47.945,58.535,48.841,57.641,49.945,57.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- right"
                d="M57.943,53.641c1.104,0,2,0.896,2,2v15.998c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2V55.641C55.943,54.537,56.84,53.641,57.943,53.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- left"
                d="M41.946,53.641c1.104,0,1.999,0.896,1.999,2v15.998c0,1.105-0.895,2-1.999,2s-2-0.895-2-2V55.641C39.946,54.537,40.842,53.641,41.946,53.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- middle"
                d="M49.945,57.641c1.104,0,2,0.896,2,2v15.998c0,1.104-0.896,2-2,2s-2-0.896-2-2V59.641C47.945,58.535,48.841,57.641,49.945,57.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- right"
                d="M57.943,53.641c1.104,0,2,0.896,2,2v15.998c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2V55.641C55.943,54.537,56.84,53.641,57.943,53.641z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.943,41.642c-0.696,0-1.369,0.092-2.033,0.205c-2.736-4.892-7.961-8.203-13.965-8.203c-8.835,0-15.998,7.162-15.998,15.997c0,5.992,3.3,11.207,8.177,13.947c0.276-1.262,0.892-2.465,1.873-3.445l0.057-0.057c-3.644-2.061-6.106-5.963-6.106-10.445c0-6.626,5.372-11.998,11.998-11.998c5.691,0,10.433,3.974,11.666,9.29c1.25-0.81,2.732-1.291,4.332-1.291c4.418,0,8,3.581,8,7.999c0,3.443-2.182,6.371-5.235,7.498c0.788,1.146,1.194,2.471,1.222,3.807c4.666-1.645,8.014-6.077,8.014-11.305C71.941,47.014,66.57,41.642,59.943,41.642z"/>
            </g>
        </g>
    </svg><!-- cloudRainMoon --> ';
}

function owmw_cloudRainAlt()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    return '
		<svg
        id="cloudRainAlt"
        class="climacon climacon-cloudRainAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-rain climacon-wrapperComponent-rain_alt">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent_cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.999,65.641c-0.267,0-0.614,0-1,0c0-1.373-0.319-2.742-0.942-4c0.776,0,1.45,0,1.942,0c4.418,0,7.999-3.58,7.999-7.998c0-4.418-3.581-8-7.999-8c-1.601,0-3.083,0.481-4.334,1.29c-1.231-5.316-5.973-9.289-11.664-9.289c-6.627,0-11.998,5.372-11.998,11.998c0,5.953,4.339,10.879,10.023,11.822c-0.637,1.218-0.969,2.55-1.012,3.888c-7.406-1.399-13.012-7.896-13.012-15.709c0-8.835,7.162-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.204c0.664-0.114,1.337-0.205,2.033-0.205c6.627,0,11.998,5.372,11.998,12C71.996,60.27,66.626,65.641,59.999,65.641z"/>
            </g>
        </g>
    </svg><!-- cloudRainAlt --> ';
}

function owmw_cloudSunRainAlt()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("sunCloudFillClip");
    $id2 = owmw_unique_id_esc("cloudSunFillClip");
    return '
		<svg
        id="cloudRainSunAlt"
        class="climacon climacon-cloudSunRainAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id2).'">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainSunAlt">
            <g clip-path="url(#'.esc_attr($id2).')">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud"  >
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z"/>
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#'.esc_attr($id1).')">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-rain climacon-wrapperComponent-rain_alt">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
            </g>

            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.999,65.641c-0.267,0-0.614,0-1,0c0-1.373-0.319-2.742-0.942-4c0.776,0,1.45,0,1.942,0c4.418,0,7.999-3.58,7.999-7.998c0-4.418-3.581-8-7.999-8c-1.601,0-3.083,0.481-4.334,1.29c-1.231-5.316-5.973-9.289-11.664-9.289c-6.627,0-11.998,5.372-11.998,11.998c0,5.953,4.339,10.879,10.023,11.822c-0.637,1.218-0.969,2.55-1.012,3.888c-7.406-1.399-13.012-7.896-13.012-15.709c0-8.835,7.162-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.204c0.664-0.114,1.337-0.205,2.033-0.205c6.627,0,11.998,5.372,11.998,12C71.996,60.27,66.626,65.641,59.999,65.641z"/>
            </g>
        </g>
    </svg><!-- cloudSunRainAlt --> ';
}

function owmw_cloudMoonRainAlt()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("moonCloudFillClip");
    return '
		<svg
        id="cloudRainMoonAlt"
        class="climacon climacon-cloudMoonRainAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainMoonAlt">
            <g clip-path="url(#'.esc_attr($id).')">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#'.esc_attr($id1).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-rain climacon-wrapperComponent-rain_alt">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent_cloud" clip-path="url(#'.esc_attr($id).')">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.999,65.641c-0.267,0-0.614,0-1,0c0-1.373-0.319-2.742-0.942-4c0.776,0,1.45,0,1.942,0c4.418,0,7.999-3.58,7.999-7.998c0-4.418-3.581-8-7.999-8c-1.601,0-3.083,0.481-4.334,1.29c-1.231-5.316-5.973-9.289-11.664-9.289c-6.627,0-11.998,5.372-11.998,11.998c0,5.953,4.339,10.879,10.023,11.822c-0.637,1.218-0.969,2.55-1.012,3.888c-7.406-1.399-13.012-7.896-13.012-15.709c0-8.835,7.162-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.204c0.664-0.114,1.337-0.205,2.033-0.205c6.627,0,11.998,5.372,11.998,12C71.996,60.27,66.626,65.641,59.999,65.641z"/>
            </g>
        </g>
    </svg><!-- cloudMoonRainAlt --> ';
}

function owmw_cloudHailAlt()
{
    return '
		<svg
        id="cloudHailAlt"
        class="climacon climacon-cloudHailAlt"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-cloudHailAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-hailAlt">
                <g class="climacon-component climacon-component-stroke climacon-component-stroke_hailAlt climacon-component-stroke_hailAlt-left">
                    <circle cx="42" cy="65.498" r="2"/>
                </g>
                <g class="climacon-component climacon-component-stroke climacon-component-stroke_hailAlt climacon-component-stroke_hailAlt-middle">
                    <circle cx="49.999" cy="65.498" r="2"/>
                </g>
                <g class="climacon-component climacon-component-stroke climacon-component-stroke_hailAlt climacon-component-stroke_hailAlt-right">
                    <circle cx="57.998" cy="65.498" r="2"/>
                </g>
                <g class="climacon-component climacon-component-stroke climacon-component-stroke_hailAlt climacon-component-stroke_hailAlt-left">
                    <circle cx="42" cy="65.498" r="2"/>
                </g>
                <g class="climacon-component climacon-component-stroke climacon-component-stroke_hailAlt climacon-component-stroke_hailAlt-middle">
                    <circle cx="49.999" cy="65.498" r="2"/>
                </g>
                <g class="climacon-component climacon-component-stroke climacon-component-stroke_hailAlt climacon-component-stroke_hailAlt-right">
                    <circle cx="57.998" cy="65.498" r="2"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.941v-4.381c2.39-1.384,3.999-3.961,3.999-6.92c0-4.417-3.581-8-7.998-8c-1.602,0-3.084,0.48-4.334,1.291c-1.23-5.317-5.974-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.998c0,3.549,1.55,6.728,3.999,8.924v4.916c-4.776-2.768-7.998-7.922-7.998-13.84c0-8.835,7.162-15.997,15.997-15.997c6.004,0,11.229,3.311,13.966,8.203c0.663-0.113,1.336-0.205,2.033-0.205c6.626,0,11.998,5.372,11.998,12C71.998,58.863,68.656,63.293,63.999,64.941z"/>
            </g>
        </g>
    </svg><!-- cloudHailAlt --> ';
}

function owmw_cloudSnow()
{
    return '
		<svg
        id="cloudSnow"
        class="climacon climacon-cloudSnow"
        viewBox="15 10 80 65">

        <g class="climacon-iconWrap climacon-iconWrap-cloudSnow">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snow">
                <circle
                class="climacon-component climacon-component-stroke climacon-component-stroke_snow climacon-component-stroke_snow-left"
                cx="42.001"
                cy="59.641"
                r="2"/>
                <circle
                class="climacon-component climacon-component-stroke climacon-component-stroke_snow climacon-component-stroke_snow-middle"
                cx="50.001"
                cy="59.641"
                r="2"/>
                <circle
                class="climacon-component climacon-component-stroke climacon-component-stroke_snow climacon-component-stroke_snow-right"
                cx="57.999"
                cy="59.641"
                r="2"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.943v-4.381c2.39-1.386,3.999-3.963,3.999-6.922c0-4.417-3.581-7.999-7.999-7.999c-1.601,0-3.083,0.48-4.333,1.291c-1.23-5.317-5.974-9.291-11.665-9.291c-6.627,0-11.998,5.373-11.998,12c0,3.549,1.55,6.729,4,8.924v4.916c-4.777-2.769-8-7.922-8-13.84c0-8.836,7.163-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.113,1.337-0.205,2.033-0.205c6.627,0,11.999,5.373,11.999,11.999C71.998,58.863,68.654,63.293,63.999,64.943z"/>
            </g>
        </g>
    </svg><!-- cloudSnow --> ';
}

function owmw_cloudSnowSun()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("sunCloudFillClip");
    $id2 = owmw_unique_id_esc("cloudSunFillClip");
    return '
		<svg
        id="cloudSnowSun"
        class="climacon climacon-cloudSnowSun"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id2).'">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowSun">
            <g clip-path="url(#'.esc_attr($id2).')">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud">
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z"/>
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#'.esc_attr($id1).')">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snow">
                <circle
                class="climacon-component climacon-component-stroke climacon-component-stroke_snow climacon-component-stroke_snow-left"
                cx="42.001"
                cy="59.641"
                r="2"/>
                <circle
                class="climacon-component climacon-component-stroke climacon-component-stroke_snow climacon-component-stroke_snow-middle"
                cx="50.001"
                cy="59.641"
                r="2"/>
                <circle
                class="climacon-component climacon-component-stroke climacon-component-stroke_snow climacon-component-stroke_snow-right"
                cx="57.999"
                cy="59.641"
                r="2"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.943v-4.381c2.39-1.386,3.999-3.963,3.999-6.922c0-4.417-3.581-7.999-7.999-7.999c-1.601,0-3.083,0.48-4.333,1.291c-1.23-5.317-5.974-9.291-11.665-9.291c-6.627,0-11.998,5.373-11.998,12c0,3.549,1.55,6.729,4,8.924v4.916c-4.777-2.769-8-7.922-8-13.84c0-8.836,7.163-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.113,1.337-0.205,2.033-0.205c6.627,0,11.999,5.373,11.999,11.999C71.998,58.863,68.654,63.293,63.999,64.943z"/>
            </g>
        </g>
    </svg><!-- cloudSnowSun --> ';
}

function owmw_cloudSnowMoon()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("moonCloudFillClip");
    return '
		<svg
        id="cloudSnowMoon"
        class="climacon climacon-cloudSnowMoon"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowMoon">
            <g clip-path="url(#'.esc_attr($id).')">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#'.esc_attr($id1).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snow">
                <circle
                class="climacon-component climacon-component-stroke climacon-component-stroke_snow climacon-component-stroke_snow-left"
                cx="42.001"
                cy="59.641"
                r="2"/>
                <circle
                class="climacon-component climacon-component-stroke climacon-component-stroke_snow climacon-component-stroke_snow-middle"
                cx="50.001"
                cy="59.641"
                r="2"/>
                <circle
                class="climacon-component climacon-component-stroke climacon-component-stroke_snow climacon-component-stroke_snow-right"
                cx="57.999"
                cy="59.641"
                r="2"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.943v-4.381c2.39-1.386,3.999-3.963,3.999-6.922c0-4.417-3.581-7.999-7.999-7.999c-1.601,0-3.083,0.48-4.333,1.291c-1.23-5.317-5.974-9.291-11.665-9.291c-6.627,0-11.998,5.373-11.998,12c0,3.549,1.55,6.729,4,8.924v4.916c-4.777-2.769-8-7.922-8-13.84c0-8.836,7.163-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.113,1.337-0.205,2.033-0.205c6.627,0,11.999,5.373,11.999,11.999C71.998,58.863,68.654,63.293,63.999,64.943z"/>
            </g>
        </g>
    </svg><!-- cloudSnowMoon --> ';
}

function owmw_cloudSnowAlt()
{
    $id = owmw_unique_id_esc("snowFillClip");
    return '
		<svg
        id="cloudSnowAlt"
        class="climacon climacon-cloudSnowAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M50,65.641c-1.104,0-2-0.896-2-2c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2S51.104,65.641,50,65.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snowAlt">
                <g class="climacon-component climacon-component climacon-component-snowAlt" clip-path="url(#'.esc_attr($id).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_snowAlt"
                    d="M43.072,59.641c0.553-0.957,1.775-1.283,2.732-0.731L48,60.176v-2.535c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2v2.535l2.195-1.268c0.957-0.551,2.18-0.225,2.73,0.732c0.553,0.957,0.225,2.18-0.73,2.731l-2.196,1.269l2.196,1.268c0.955,0.553,1.283,1.775,0.73,2.732c-0.552,0.954-1.773,1.282-2.73,0.729L52,67.104v2.535c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2v-2.535l-2.195,1.269c-0.957,0.553-2.18,0.226-2.732-0.729c-0.552-0.957-0.225-2.181,0.732-2.732L46,63.641l-2.195-1.268C42.848,61.82,42.521,60.598,43.072,59.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M61.998,65.461v-4.082c3.447-0.891,6-4.012,6-7.738c0-4.417-3.582-7.999-7.999-7.999c-1.601,0-3.084,0.48-4.334,1.291c-1.231-5.317-5.973-9.291-11.664-9.291c-6.627,0-11.999,5.373-11.999,12c0,4.438,2.417,8.305,5.999,10.379v4.444c-5.86-2.375-9.998-8.112-9.998-14.825c0-8.835,7.162-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.113,1.336-0.205,2.033-0.205c6.626,0,11.998,5.373,11.998,11.998C71.997,59.586,67.671,64.506,61.998,65.461z"/>
            </g>
        </g>
    </svg><!-- cloudSnowAlt --> ';
}

function owmw_cloudSnowSunAlt()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("sunCloudFillClip");
    $id2 = owmw_unique_id_esc("cloudSunFillClip");
    $id3 = owmw_unique_id_esc("snowFillClip");
    return '
		<svg
        id="cloudSnowSunAlt"
        class="climacon climacon-cloudSnowSunAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id2).'">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id3).'">
            <path d="M15,15v70h70V15H15z M50,65.641c-1.104,0-2-0.896-2-2c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2S51.104,65.641,50,65.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowSunAlt">
            <g clip-path="url(#'.esc_attr($id2).')">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud">
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z"/>
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#'.esc_attr($id1).')">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snowAlt">
                <g class="climacon-component climacon-component climacon-component-snowAlt" clip-path="url(#'.esc_attr($id3).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_snowAlt"
                    d="M43.072,59.641c0.553-0.957,1.775-1.283,2.732-0.731L48,60.176v-2.535c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2v2.535l2.195-1.268c0.957-0.551,2.18-0.225,2.73,0.732c0.553,0.957,0.225,2.18-0.73,2.731l-2.196,1.269l2.196,1.268c0.955,0.553,1.283,1.775,0.73,2.732c-0.552,0.954-1.773,1.282-2.73,0.729L52,67.104v2.535c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2v-2.535l-2.195,1.269c-0.957,0.553-2.18,0.226-2.732-0.729c-0.552-0.957-0.225-2.181,0.732-2.732L46,63.641l-2.195-1.268C42.848,61.82,42.521,60.598,43.072,59.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M61.998,65.461v-4.082c3.447-0.891,6-4.012,6-7.738c0-4.417-3.582-7.999-7.999-7.999c-1.601,0-3.084,0.48-4.334,1.291c-1.231-5.317-5.973-9.291-11.664-9.291c-6.627,0-11.999,5.373-11.999,12c0,4.438,2.417,8.305,5.999,10.379v4.444c-5.86-2.375-9.998-8.112-9.998-14.825c0-8.835,7.162-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.113,1.336-0.205,2.033-0.205c6.626,0,11.998,5.373,11.998,11.998C71.997,59.586,67.671,64.506,61.998,65.461z"/>
            </g>
        </g>
    </svg><!-- cloudSnowSunAlt --> ';
}

function owmw_cloudSnowMoonAlt()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("moonCloudFillClip");
    $id2 = owmw_unique_id_esc("snowFillClip");
    return '
		<svg
        id="cloudSnowAlt"
        class="climacon climacon-cloudSnowAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id2).'">
            <path d="M15,15v70h70V15H15z M50,65.641c-1.104,0-2-0.896-2-2c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2S51.104,65.641,50,65.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowAlt">
            <g clip-path="url(#'.esc_attr($id1).')">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#'.esc_attr($id1).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snowAlt">
                <g class="climacon-component climacon-component climacon-component-snowAlt" clip-path="url(#'.esc_attr($id2).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_snowAlt"
                    d="M43.072,59.641c0.553-0.957,1.775-1.283,2.732-0.731L48,60.176v-2.535c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2v2.535l2.195-1.268c0.957-0.551,2.18-0.225,2.73,0.732c0.553,0.957,0.225,2.18-0.73,2.731l-2.196,1.269l2.196,1.268c0.955,0.553,1.283,1.775,0.73,2.732c-0.552,0.954-1.773,1.282-2.73,0.729L52,67.104v2.535c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2v-2.535l-2.195,1.269c-0.957,0.553-2.18,0.226-2.732-0.729c-0.552-0.957-0.225-2.181,0.732-2.732L46,63.641l-2.195-1.268C42.848,61.82,42.521,60.598,43.072,59.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M61.998,65.461v-4.082c3.447-0.891,6-4.012,6-7.738c0-4.417-3.582-7.999-7.999-7.999c-1.601,0-3.084,0.48-4.334,1.291c-1.231-5.317-5.973-9.291-11.664-9.291c-6.627,0-11.999,5.373-11.999,12c0,4.438,2.417,8.305,5.999,10.379v4.444c-5.86-2.375-9.998-8.112-9.998-14.825c0-8.835,7.162-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.113,1.336-0.205,2.033-0.205c6.626,0,11.998,5.373,11.998,11.998C71.997,59.586,67.671,64.506,61.998,65.461z"/>
            </g>
        </g>
    </svg><!-- cloudSnowMoonAlt --> ';
}

function owmw_cloudFog()
{
    return '
		<svg
        id="cloudFog"
        class="climacon climacon-cloudFog"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-cloudFog">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-Fog">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine climacon-component-stroke_fogLine-top"
                d="M69.998,57.641H30.003c-1.104,0-2-0.895-2-2c0-1.104,0.896-2,2-2h39.995c1.104,0,2,0.896,2,2C71.998,56.746,71.104,57.641,69.998,57.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine climacon-component-stroke_fogLine-middle"
                d="M69.998,65.641H30.003c-1.104,0-2-0.896-2-2s0.896-2,2-2h39.995c1.104,0,2,0.896,2,2C71.998,64.744,71.104,65.641,69.998,65.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine climacon-component-stroke_fogLine-bottom"
                d="M30.003,69.639h39.995c1.104,0,2,0.896,2,2c0,1.105-0.896,2-2,2H30.003c-1.104,0-2-0.895-2-2C28.003,70.535,28.898,69.639,30.003,69.639z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.999,45.643c-1.601,0-3.083,0.48-4.333,1.291c-1.232-5.317-5.974-9.291-11.665-9.291c-6.626,0-11.998,5.373-11.998,12h-4c0-8.835,7.163-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.113,1.337-0.205,2.033-0.205c5.222,0,9.651,3.342,11.301,8h-4.381C65.535,47.253,62.958,45.643,59.999,45.643z"/>
            </g>
        </g>
    </svg><!--cloudFog --> ';
}

function owmw_cloudFogSun()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("sunCloudFillClip");
    $id2 = owmw_unique_id_esc("cloudSunFillClip");
    return '
		<svg
        id="cloudFogSun"
        class="climacon climacon-cloudFogSun"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id2).'">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"
            />
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogSun">
            <g clip-path="url(#'.esc_attr($id2).')">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud">
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z"/>
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#'.esc_attr($id1).')">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-Fog">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine climacon-component-stroke_fogLine-top"
                d="M69.998,57.641H30.003c-1.104,0-2-0.895-2-2c0-1.104,0.896-2,2-2h39.995c1.104,0,2,0.896,2,2C71.998,56.746,71.104,57.641,69.998,57.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine climacon-component-stroke_fogLine-middle"
                d="M69.998,65.641H30.003c-1.104,0-2-0.896-2-2s0.896-2,2-2h39.995c1.104,0,2,0.896,2,2C71.998,64.744,71.104,65.641,69.998,65.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine climacon-component-stroke_fogLine-bottom"
                d="M30.003,69.639h39.995c1.104,0,2,0.896,2,2c0,1.105-0.896,2-2,2H30.003c-1.104,0-2-0.895-2-2C28.003,70.535,28.898,69.639,30.003,69.639z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.999,45.643c-1.601,0-3.083,0.48-4.333,1.291c-1.232-5.317-5.974-9.291-11.665-9.291c-6.626,0-11.998,5.373-11.998,12h-4c0-8.835,7.163-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.113,1.337-0.205,2.033-0.205c5.222,0,9.651,3.342,11.301,8h-4.381C65.535,47.253,62.958,45.643,59.999,45.643z"/>
            </g>
        </g>
    </svg><!--cloudFogSun --> ';
}

function owmw_cloudFogMoon()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("moonCloudFillClip");
    return '
		<svg
        id="cloudFogMoon"
        class="climacon climacon-cloudFogMoon"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogMoon">
            <g clip-path="url(#'.esc_attr($id).')">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#'.esc_attr($id1).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-Fog">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine climacon-component-stroke_fogLine-top"
                d="M69.998,57.641H30.003c-1.104,0-2-0.895-2-2c0-1.104,0.896-2,2-2h39.995c1.104,0,2,0.896,2,2C71.998,56.746,71.104,57.641,69.998,57.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine climacon-component-stroke_fogLine-middle"
                d="M69.998,65.641H30.003c-1.104,0-2-0.896-2-2s0.896-2,2-2h39.995c1.104,0,2,0.896,2,2C71.998,64.744,71.104,65.641,69.998,65.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine climacon-component-stroke_fogLine-bottom"
                d="M30.003,69.639h39.995c1.104,0,2,0.896,2,2c0,1.105-0.896,2-2,2H30.003c-1.104,0-2-0.895-2-2C28.003,70.535,28.898,69.639,30.003,69.639z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.999,45.643c-1.601,0-3.083,0.48-4.333,1.291c-1.232-5.317-5.974-9.291-11.665-9.291c-6.626,0-11.998,5.373-11.998,12h-4c0-8.835,7.163-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.113,1.337-0.205,2.033-0.205c5.222,0,9.651,3.342,11.301,8h-4.381C65.535,47.253,62.958,45.643,59.999,45.643z"/>
            </g>
        </g>
    </svg><!-- cloudFogMoon --> ';
}

function owmw_cloudFogAlt()
{
    return '
		<svg
        id="cloudFogAlt"
        class="climacon climacon-cloudFogAlt"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-Fog">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M29.177,55.641c-0.262-0.646-0.473-1.314-0.648-2h43.47c0,0.685-0.069,1.349-0.181,2H29.177z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M36.263,35.643c2.294-1.271,4.93-1.999,7.738-1.999c2.806,0,5.436,0.73,7.728,1.999H36.263z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M28.142,47.642c0.085-0.682,0.218-1.347,0.387-1.999h40.396c0.552,0.613,1.039,1.281,1.455,1.999H28.142z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M29.177,43.643c0.281-0.693,0.613-1.359,0.984-2h27.682c0.04,0.068,0.084,0.135,0.123,0.205c0.664-0.114,1.339-0.205,2.033-0.205c2.451,0,4.729,0.738,6.627,2H29.177z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M31.524,39.643c0.58-0.723,1.225-1.388,1.92-2h21.123c0.689,0.61,1.326,1.28,1.902,2H31.524z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M71.816,51.641H28.142c-0.082-0.656-0.139-1.32-0.139-1.999h43.298C71.527,50.285,71.702,50.953,71.816,51.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M71.301,57.641c-0.246,0.699-0.555,1.367-0.921,2H31.524c-0.505-0.629-0.957-1.299-1.363-2H71.301z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M33.444,61.641h35.48c-0.68,0.758-1.447,1.435-2.299,2H36.263C35.247,63.078,34.309,62.4,33.444,61.641z"/>
            </g>
        </g>
    </svg><!--cloudFogAlt --> ';
}

function owmw_cloudFogSunAlt()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("sunCloudFillClip");
    $id2 = owmw_unique_id_esc("cloudSunFillClip");
    return '
		<svg
        id="cloudFogSunAlt"
        class="climacon climacon-cloudFogSunAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id1).'">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="'.esc_attr($id2).'">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogSunAlt">
            <g clip-path="url(#'.esc_attr($id1).')">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud">
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M80.029,43.611h-3.998c-1.105,0-2-0.896-2-1.999s0.895-2,2-2h3.998c1.104,0,2,0.896,2,2S81.135,43.611,80.029,43.611z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,30.3c-0.781,0.781-2.049,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.779-0.781,2.047-0.781,2.828,0c0.779,0.781,0.779,2.047,0,2.828L72.174,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,25.614c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C60.033,24.718,59.135,25.614,58.033,25.614z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,30.3l-2.827-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.781,2.047-0.781,2.827,0l2.827,2.828c0.781,0.781,0.781,2.047,0,2.828C45.939,31.081,44.673,31.081,43.892,30.3z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M42.033,41.612c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.998-0.896-1.998-1.999s0.896-2,1.998-2h4C41.139,39.612,42.033,40.509,42.033,41.612z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M43.892,52.925c0.781-0.78,2.048-0.78,2.827,0c0.781,0.78,0.781,2.047,0,2.828l-2.827,2.827c-0.78,0.781-2.047,0.781-2.827,0c-0.781-0.78-0.781-2.047,0-2.827L43.892,52.925z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M58.033,57.61c1.104,0,2,0.895,2,1.999v4c0,1.104-0.896,2-2,2c-1.105,0-2-0.896-2-2v-4C56.033,58.505,56.928,57.61,58.033,57.61z"/>
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M72.174,52.925l2.828,2.828c0.779,0.78,0.779,2.047,0,2.827c-0.781,0.781-2.049,0.781-2.828,0l-2.828-2.827c-0.781-0.781-0.781-2.048,0-2.828C70.125,52.144,71.391,52.144,72.174,52.925z"/>
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#'.esc_attr($id2).')">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-Fog">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M29.177,55.641c-0.262-0.646-0.473-1.314-0.648-2h43.47c0,0.685-0.069,1.349-0.181,2H29.177z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M36.263,35.643c2.294-1.271,4.93-1.999,7.738-1.999c2.806,0,5.436,0.73,7.728,1.999H36.263z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M28.142,47.642c0.085-0.682,0.218-1.347,0.387-1.999h40.396c0.552,0.613,1.039,1.281,1.455,1.999H28.142z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M29.177,43.643c0.281-0.693,0.613-1.359,0.984-2h27.682c0.04,0.068,0.084,0.135,0.123,0.205c0.664-0.114,1.339-0.205,2.033-0.205c2.451,0,4.729,0.738,6.627,2H29.177z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M31.524,39.643c0.58-0.723,1.225-1.388,1.92-2h21.123c0.689,0.61,1.326,1.28,1.902,2H31.524z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M71.816,51.641H28.142c-0.082-0.656-0.139-1.32-0.139-1.999h43.298C71.527,50.285,71.702,50.953,71.816,51.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M71.301,57.641c-0.246,0.699-0.555,1.367-0.921,2H31.524c-0.505-0.629-0.957-1.299-1.363-2H71.301z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M33.444,61.641h35.48c-0.68,0.758-1.447,1.435-2.299,2H36.263C35.247,63.078,34.309,62.4,33.444,61.641z"/>
            </g>
        </g>
    </svg><!--cloudFogSunAlt --> ';
}

function owmw_cloudFogMoonAlt()
{
    $id = owmw_unique_id_esc("cloudFillClip");
    $id1 = owmw_unique_id_esc("moonCloudFillClip");
    return '
		<svg
        id="cloudFogMoonAlt"
        class="climacon climacon-cloudFogMoonAlt"
        viewBox="15 10 80 65">
        <clipPath id="'.esc_attr($id).'">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
       <clipPath id="'.esc_attr($id1).'">
            <path d="M15,15v70h70V15H15z M59.943,65.638c-2.775,0-12.801,0-15.998,0c-8.836,0-15.998-7.162-15.998-15.998c0-8.835,7.162-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.941,60.265,66.57,65.638,59.943,65.638z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogMoon">
            <g clip-path="url(#'.esc_attr($id1).')">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#'.esc_attr($id).')">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-Fog">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M29.177,55.641c-0.262-0.646-0.473-1.314-0.648-2h43.47c0,0.685-0.069,1.349-0.181,2H29.177z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M36.263,35.643c2.294-1.271,4.93-1.999,7.738-1.999c2.806,0,5.436,0.73,7.728,1.999H36.263z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M28.142,47.642c0.085-0.682,0.218-1.347,0.387-1.999h40.396c0.552,0.613,1.039,1.281,1.455,1.999H28.142z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M29.177,43.643c0.281-0.693,0.613-1.359,0.984-2h27.682c0.04,0.068,0.084,0.135,0.123,0.205c0.664-0.114,1.339-0.205,2.033-0.205c2.451,0,4.729,0.738,6.627,2H29.177z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M31.524,39.643c0.58-0.723,1.225-1.388,1.92-2h21.123c0.689,0.61,1.326,1.28,1.902,2H31.524z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M71.816,51.641H28.142c-0.082-0.656-0.139-1.32-0.139-1.999h43.298C71.527,50.285,71.702,50.953,71.816,51.641z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M71.301,57.641c-0.246,0.699-0.555,1.367-0.921,2H31.524c-0.505-0.629-0.957-1.299-1.363-2H71.301z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_fogLine"
                d="M33.444,61.641h35.48c-0.68,0.758-1.447,1.435-2.299,2H36.263C35.247,63.078,34.309,62.4,33.444,61.641z"/>
            </g>
        </g>
    </svg><!--cloudFogMoonAlt --> ';
}

function owmw_cloudLightning()
{
    return '
		<svg
        id="cloudLightning"
        class="climacon climacon-cloudLightning"
        viewBox="15 10 80 65">
        <g class="climacon-iconWrap climacon-iconWrap-cloudLightning">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-lightning">
                <polygon
                class="climacon-component climacon-component-stroke climacon-component-stroke_lightning"
                points="48.001,51.641 57.999,51.641 52,61.641 58.999,61.641 46.001,77.639 49.601,65.641 43.001,65.641 "/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.999,65.641c-0.28,0-0.649,0-1.062,0l3.584-4.412c3.182-1.057,5.478-4.053,5.478-7.588c0-4.417-3.581-7.998-7.999-7.998c-1.602,0-3.083,0.48-4.333,1.29c-1.231-5.316-5.974-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,12c0,5.446,3.632,10.039,8.604,11.503l-1.349,3.777c-6.52-2.021-11.255-8.098-11.255-15.282c0-8.835,7.163-15.999,15.998-15.999c6.004,0,11.229,3.312,13.965,8.204c0.664-0.114,1.338-0.205,2.033-0.205c6.627,0,11.999,5.371,11.999,11.999C71.999,60.268,66.626,65.641,59.999,65.641z"/>
            </g>
        </g>
    </svg><!--cloudLightning --> ';
}

function owmw_colorAnimatedConfig()
{
    return
    ('<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" class="ac-svg-template">
   <defs>
    <radialgradient id="owmw-gradYellow" cx="50%" cy="50%" r="80%" fx="90%" fy="80%">
      <stop offset="0%" class="ac-stop-yellow" />
      <stop offset="100%" class="ac-stop-orange" />
    </radialgradient>
    <radialgradient id="owmw-gradDarkGray" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
      <stop offset="0%" class="ac-stop-white" />
      <stop offset="70%" class="ac-stop-gray" />
      <stop offset="100%" class="ac-stop-dimgray" />
    </radialgradient>
    <radialgradient id="owmw-gradGray" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
      <stop offset="0%" class="ac-stop-white" />
      <stop offset="100%" class="ac-stop-darkgray" />
    </radialgradient>
    <lineargradient id="owmw-gradWhite" x1="40%" y1="50%" x2="90%" y2="90%">
      <stop offset="0%" class="ac-stop-white" />
      <stop offset="100%" class="ac-stop-darkgray" />
    </lineargradient>     
  </defs>
  
    <!-- Sun -->
  <symbol id="owmw-sun">
    <!-- center -->
    <circle cx="50" cy="50" r="20" fill="url(#owmw-gradYellow)" />

    <!-- long rays -->
    <line x1="50" y1="27" x2="50" y2="2" class="longRay" />
    <line x1="50" y1="73" x2="50" y2="98" class="longRay" />
    <line x1="27" y1="50" x2="2" y2="50" class="longRay" />
    <line x1="73" y1="50" x2="98" y2="50" class="longRay" />
    <line x1="34" y1="34" x2="16" y2="16" class="longRay" />
    <line x1="66" y1="66" x2="84" y2="84" class="longRay" />
    <line x1="34" y1="66" x2="16" y2="84" class="longRay" />
    <line x1="66" y1="34" x2="84" y2="16" class="longRay" />

    <!-- short rays -->
    <line x1="59" y1="29" x2="66" y2="13" class="shortRay"/>
    <line x1="71" y1="42" x2="87" y2="35" class="shortRay" />
    <line x1="71" y1="58.5" x2="87" y2="65" class="shortRay" />
    <line x1="59" y1="71" x2="66" y2="87" class="shortRay" />
    <line x1="41" y1="71" x2="34" y2="87" class="shortRay" />
    <line x1="29.5" y1="58.5" x2="13" y2="66" class="shortRay" />
    <line x1="29" y1="42" x2="13" y2="35" class="shortRay" />
    <line x1="41" y1="29" x2="35" y2="13" class="shortRay" />
  </symbol>
  
  <!-- Moon -->
  <symbol id="owmw-moon">
    <path d="M60,20 A30,30 0 1,0 90,65 22,22 0 1,1 60,20z" fill="url(#owmw-gradYellow)"/>
  </symbol>

  <!-- Star -->
  <symbol id="owmw-star">
    <polygon points="5,0 2,10 10,4 0,4 8,10" style="fill:url(#owmw-gradYellow);fill-rule:nonzero;"/>
  </symbol>
    
  <!-- Small Gray Cloud -->
  <symbol id="owmw-grayCloud">
    <path  d="M20,15 Q25,0 45,11 Q60,5 60,20 A30,15 5 1,1 20,15 Z" />
  </symbol>
  
  <!-- White Cloud -->
  <symbol id="owmw-whiteCloud">
    <path fill="url(#owmw-gradWhite)" d="M11,47 Q13,37 21,42 Q31,30 41,38 A28,21 -25 1,1 35,75 Q23,85 19,73 A12,12 0 0,1 11,47Z" />
  </symbol>
  
  <!-- Rain Drop -->
  <symbol id="owmw-rainDrop">
    <path fill="lightblue" d="M10 0 Q10,0 14,7 A5,5 0 1,1 6,7 Q10,0 10,0Z" />
  </symbol>

  <!-- Rain Drizzle -->
  <symbol id="owmw-rainDrizzle">
    <line x1="20" y1="2" x2="10" y2="40" />
  </symbol>

  <!-- Thunder Bolt -->
  <symbol id="owmw-thunderBolt">
    <path fill="black" d="M15,0 L1,23 L8,23 L0,40 L15,19 L8,19Z" />
  </symbol>

  <!-- Snow Flake -->
  <symbol id="owmw-snowFlake">
    <path d="M5,0 L5,10 M0,5 L10,5 M1.5,1.5 L8.5,8.5 M8.5,1.5 L1.5,8.5" />
    <path d="M3.5,0.25 L5,2 L6.5,0.25 M3.5,9.75 L5,8 L6.5,9.75" />
    <path d="M0.25,3.5 L2,5 L0.25,6.5 M9.75,3.5 L8,5 L9.75,6.5" />
    <path d="M0.75,2.90 L2.85,2.85 L2.90,0.75 M7.25,9.35 L7.15,7.15 L9.35,7.25" />
    <path d="M0.75,7.25 L2.85,7.15 L2.90,9.35 M7.15,0.75 L7.25,2.85 L9.35,2.90" />
  </symbol>

  <!-- Hail/Ice Pellet-->
  <symbol id="owmw-icePellet">
    <circle cx="4" cy="4" r="4" fill="#e3fcff" />
  </symbol>

  <!-- Mist -->
  <symbol id="owmw-mist">
    <path d="M5,34 L43,34" />
    <path d="M10,40 L40,40 Q51.5,40 50,35 T60,30 L80,30" />
    <path d="M15,45 L45,45 Q56.5,45 55,40 T65,35 L90,35" />
    <path d="M60,42 L85,42" />
  </symbol>
  
</svg>');
}

function owmw_colorAnimated_Dark_Clouds()
{
    return
    ('<!-- Dark clouds -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use xlink:href="#owmw-grayCloud" x="25" y="10" class="reverse-small-cloud" fill="url(#owmw-gradDarkGray)"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Few_Clouds()
{
    return
    ('<!-- Few clouds -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Sun()
{
    return
    ('<!-- Sunny -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-sun"/>
    </svg>');
}

function owmw_colorAnimated_Partly_Cloudy_Day()
{
    return
    ('<!-- Partly Cloudy Day -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-sun" x="-12" y="-18"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Patchy_Rain_Day()
{
    return
    ('<!-- Patchy Rain Day -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-sun" x="-12" y="-18"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use class="drop1" xlink:href="#rainDrop" x="25" y="65"/>
      <use class="drop3" xlink:href="#rainDrop" x="45" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Patchy_Drizzle_Day()
{
    return
    ('<!-- Patchy Drizzle Day -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-sun" x="-12" y="-18"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use xlink:href="#owmw-rainDrizzle" x="25" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="40" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Patchy_Snow_Day()
{
    return
    ('<!-- Patchy Snow Day -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-sun" x="-12" y="-18"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use class="snowFlake2" xlink:href="#snowFlake" x="30" y="65"/>
      <use class="snowFlake4" xlink:href="#snowFlake" x="45" y="65"/>
      <use class="snowFlake5" xlink:href="#snowFlake" x="58" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Patchy_Sleet_Day()
{
    return
    ('<!-- Patchy Sleet Day -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-sun" x="-12" y="-18"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use class="snowFlake2" xlink:href="#snowFlake" x="35" y="65"/>
      <use class="snowFlake4" xlink:href="#snowFlake" x="50" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="25" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="40" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Moon()
{
    return
    ('<!-- Moon -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-moon" x="-15"/>
      <use xlink:href="#owmw-star" x="42" y="30" class="stars animated infinite flash"/>
      <use xlink:href="#owmw-star" x="61" y="32" class="stars animated infinite flash delay-1s"/>
      <use xlink:href="#owmw-star" x="55" y="50" class="stars animated infinite flash delay-2s"/>
    </svg>');
}

function owmw_colorAnimated_Partly_Cloudy_Night()
{
    return
    ('<!-- Partly Cloudy Night -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-moon" x="-20" y="-15"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Patchy_Rain_Night()
{
    return
    ('<!-- Patchy Rain Night -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-moon" x="-20" y="-15"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use class="drop1" xlink:href="#rainDrop" x="25" y="65"/>
      <use class="drop3" xlink:href="#rainDrop" x="45" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Patchy_Drizzle_Night()
{
    return
    ('<!-- Patchy Drizzle Night -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-moon" x="-20" y="-15"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use xlink:href="#owmw-rainDrizzle" x="25" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="40" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Patchy_Snow_Night()
{
    return
    ('<!-- Patchy Snow Night -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-moon" x="-20" y="-15"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use class="snowFlake2" xlink:href="#snowFlake" x="30" y="65"/>
      <use class="snowFlake4" xlink:href="#snowFlake" x="45" y="65"/>
      <use class="snowFlake5" xlink:href="#snowFlake" x="58" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Patchy_Sleet_Night()
{
    return
    ('<!-- Patchy Sleet Night -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-moon" x="-20" y="-15"/>
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use class="snowFlake2" xlink:href="#snowFlake" x="35" y="65"/>
      <use class="snowFlake4" xlink:href="#snowFlake" x="50" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="25" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="40" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
    </svg>');
}

function owmw_colorAnimated_Rain()
{
    return
    ('<!-- Rain -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-grayCloud" x="25" y="10" class="reverse-small-cloud" fill="url(#owmw-gradDarkGray)"/>
      <use class="drop4" xlink:href="#rainDrop" x="15" y="65"/>
      <use class="drop1" xlink:href="#rainDrop" x="25" y="65"/>
      <use class="drop2" xlink:href="#rainDrop" x="37" y="65"/>
      <use class="drop3" xlink:href="#rainDrop" x="50" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="5" y="-7"/>
    </svg>');
}

function owmw_colorAnimated_Drizzle()
{
    return
    ('<!-- Drizzle -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-rainDrizzle" x="15" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="25" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="35" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="45" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="10" y="-10"/>
    </svg>');
}

function owmw_colorAnimated_Thunderstorm()
{
    return
    ('<!-- Thunderstorm -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradGray)"/>
      <use xlink:href="#owmw-grayCloud" x="25" y="10" class="reverse-small-cloud" fill="url(#owmw-gradDarkGray)"/>
      <use xlink:href="#owmw-thunderBolt" x="30" y="61" class="lighting animated infinite flash"/>
      <use xlink:href="#owmw-whiteCloud" x="7" />
      <use xlink:href="#owmw-thunderBolt" x="45" y="56" class="lighting animated infinite flash delay-1s"/>
    </svg>');
}

function owmw_colorAnimated_Snow()
{
    return
    ('<!-- Snow -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use class="snowFlake1" xlink:href="#snowFlake" x="20" y="55"/>
      <use class="snowFlake2" xlink:href="#snowFlake" x="35" y="65"/>
      <use class="snowFlake3" xlink:href="#snowFlake" x="45" y="60"/>
      <use class="snowFlake4" xlink:href="#snowFlake" x="50" y="65"/>
      <use class="snowFlake5" xlink:href="#snowFlake" x="63" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="10" y="-15"/>
    </svg>');
}

function owmw_colorAnimated_Sleet()
{
    return
    ('<!-- Sleet -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-grayCloud" x="25" y="10" class="reverse-small-cloud" fill="url(#owmw-gradDarkGray)"/>
      <use class="snowFlake1" xlink:href="#snowFlake" x="20" y="55"/>
      <use class="snowFlake2" xlink:href="#snowFlake" x="35" y="65"/>
      <use class="snowFlake3" xlink:href="#snowFlake" x="45" y="60"/>
      <use class="snowFlake4" xlink:href="#snowFlake" x="50" y="65"/>
      <use class="snowFlake5" xlink:href="#snowFlake" x="63" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="15" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="25" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="35" y="65"/>
      <use xlink:href="#owmw-rainDrizzle" x="45" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="5" y="-7"/>
    </svg>');
}

function owmw_colorAnimated_Hail()
{
    return
    ('<!-- Hail/Ice Pellets -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-grayCloud" x="25" y="10" class="reverse-small-cloud" fill="url(#owmw-gradDarkGray)"/>
      <use class="ice4" xlink:href="#icePellet" x="25" y="65"/>
      <use class="ice1" xlink:href="#icePellet" x="35" y="65"/>
      <use class="ice2" xlink:href="#icePellet" x="47" y="65"/>
      <use class="ice3" xlink:href="#icePellet" x="60" y="65"/>
      <use xlink:href="#owmw-whiteCloud" x="5" y="-7"/>
    </svg>');
}

function owmw_colorAnimated_Mist_Cloud()
{
    return
    ('<!-- Mist Cloud -->
    <svg class="ac-icon" viewbox="0 0 100 100">
      <use xlink:href="#owmw-grayCloud" class="small-cloud" fill="url(#owmw-gradDarkGray)" x="0" y="20"/>
      <use xlink:href="#owmw-grayCloud" x="30" y="30" class="reverse-small-cloud" fill="url(#owmw-gradGray)"/>
      <use class="mist" xlink:href="#mist" x="0" y="30"/>
    </svg>');
}

function owmw_colorAnimated_Wind()
{
    return
    ('<!-- Wind -->
    <svg class="icon wind" viewBox="0 0 100 100">
      <path class="wind1" d="M 8,37 L 35,37"/>
      <path class="wind2" d="M 2,45 L 45,45 C65,45 64,25 52,25 C47,24 42,30 44,35"/>
      <path class="wind3" d="M 20,55 L 75,55 C90,53 90,35 80,32 C70,28 60,42 70,48 C80,50 80,40 78,41"/>
      <path class="wind4" d="M 12,65 L 65,65 C85,68 75,87 65,83 C60,81 60,78 61,76"/>
      <path class="wind5" d="M 5,75 L 48,75"/>
    </svg>');
}
  
function owmw_colorAnimated_Tornado()
{
    return
    ('<!-- Tornado -->
    <svg class="ac-icon" viewBox="0 0 100 100">
      <path d="M26,17.5 A25,6.8 -3 1,1 68,15.5 A22,5.25 -3 1,0 26,17.5z" fill="black"/>
      <path d="M29,18.5 A19.5,4.5 -3 1,1 65,17 A17,3.5 -3 1,0 29,18.5z" fill="black"/>
      <path d="M35,20 A15,2.25 -3 1,1 60,19 A13,1.5 -3 1,0 35,20z" fill="black"/>
      <path d="M22,11 A35,12 -3 1,0 70,7 A28,9 -3 1,1 22,11z" fill="black"/>
      <path d="M25,23 A34,11.70 -4 1,0 75,18 A29,7.75 -4 1,1 25,23" fill="black"/>
      <path d="M38,33 A30,11 -5 1,0 70,31 A24,7.5 -5 1,1 38,33" fill="black"/>
      <path d="M38,45 A27,10.5 -3 1,0 73,42 A22.5,7.75 -3 1,1 38,45" fill="black"/>
      <path d="M35,56 A23,9.15 -1 1,0 75,56 A20.25,8 -1 1,1 35,56" fill="black"/>
      <path d="M33,62 A20,8.75 0 1,0 69,66 A18,7.75 0 1,1 33,62" fill="black"/>
      <path d="M31,68 A17,7.75 -1 1,0 60,73 A15,7.125 -1 1,1 31,68" fill="black"/>
      <path d="M28,74.5 Q25,86 50,98 C45,85 61,84 57,77 A8,3.85 2 1,1 28,74.5" fill="black"/>
    </svg>');
}
