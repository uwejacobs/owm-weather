<?php 
// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
    exit;
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//SVG Icon animation
// original viewbox: "15 15 70 70"
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_sun() {
	return '
		<svg
        version="1.1"
        id="sun"
        class="climacon climacon-sun"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="sunFillClip">
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
                <g class="climacon-componentWrap climacon-componentWrap_sunBody" clip-path="url(#sunFillClip)">
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

function owmw_sunFill() {
	return '
		<svg
        version="1.1"
        id="sunFill"
        class="climacon climacon-sunFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-sunFill">
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
                <g class="climacon-componentWrap climacon-componentWrap_sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="50.034"
                    cy="50"
                    r="11.999"
                    />
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="50.034"
                    cy="50"
                    r="7.999"
                    />
                </g>
            </g>
        </g>
    </svg><!-- sunFill -->';
}

function owmw_moon() {
	return '
		<svg
        version="1.1"
        id="moon"
        class="climacon climacon-moon"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="moonFillClip">
            <path d="M15,15v70h70V15H15z M50,57.999c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C56.979,55.344,53.802,57.999,50,57.999z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-moon">
            <g class="climacon-componentWrap climacon-componentWrap-moon" clip-path="url(#moonFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_moon"
                d="M50,61.998c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C61.998,56.626,56.626,61.998,50,61.998z"/>
            </g>
        </g>
    </svg><!-- moon --> ';

}

function owmw_moonFill() {
	return '
		<svg
        version="1.1"
        id="moon"
        class="climacon climacon-moonFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-moonFill">
            <g class="climacon-componentWrap climacon-componentWrap-moon">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_moon"
                d="M50,61.998c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C61.998,56.626,56.626,61.998,50,61.998z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M48.212,42.208c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C52.938,50.884,49.115,47.062,48.212,42.208z"/>
            </g>
        </g>
    </svg><!-- moonFill --> ';
}

function owmw_snowflake() {
	return '
		<svg
        version="1.1"
        id="sunsetAlt"
        class="climacon climacon-snowflake"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="snowflakeFillClip">
            <path
            d="M15,15v70h70V15H15z M50,54c-2.209,0-4-1.791-4-4c0-2.208,1.791-3.999,4-3.999s3.999,1.791,3.999,3.999C53.999,52.209,52.209,54,50,54z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-snowflake">
            <g class="climacon-componentWrap climacon-componentWrap-snowflake" clip-path="url(#snowflakeFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_snowflake"
                d="M59.659,46.733l-1.958,1.13c0.188,0.682,0.298,1.396,0.298,2.137c0,0.742-0.108,1.456-0.298,2.139l1.958,1.129c0.956,0.553,1.284,1.775,0.731,2.732c-0.553,0.956-1.774,1.284-2.731,0.73l-1.954-1.127c-1.003,1.02-2.277,1.766-3.705,2.133v2.263c0,1.104-0.896,2-2,2c-1.104,0-2-0.896-2-2v-2.263c-1.428-0.367-2.703-1.113-3.705-2.133l-1.954,1.127c-0.957,0.554-2.18,0.226-2.731-0.73c-0.553-0.957-0.225-2.18,0.731-2.732l1.958-1.129c-0.189-0.683-0.298-1.396-0.298-2.139c0-0.741,0.108-1.455,0.298-2.137l-1.958-1.13c-0.956-0.553-1.284-1.775-0.731-2.732c0.552-0.956,1.774-1.284,2.731-0.731l1.954,1.128c1.002-1.02,2.277-1.766,3.705-2.134v-2.262c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2v2.262c1.428,0.368,2.702,1.114,3.705,2.134l1.954-1.128c0.957-0.553,2.18-0.225,2.731,0.731C60.943,44.958,60.615,46.181,59.659,46.733z"/>
            </g>
        </g>
    </svg><!-- snowflake --> ';
}

function owmw_snowflakeFill() {
	return '
		<svg
        version="1.1"
        id="snowflakeFill"
        class="climacon climacon-snowflakeFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-snowflakeFill">
            <g class="climacon-componentWrap climacon-componentWrap-snowflake">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_snowflake"
                d="M59.659,46.733l-1.958,1.13c0.188,0.682,0.298,1.396,0.298,2.137c0,0.742-0.108,1.456-0.298,2.139l1.958,1.129c0.956,0.553,1.284,1.775,0.731,2.732c-0.553,0.956-1.774,1.284-2.731,0.73l-1.954-1.127c-1.003,1.02-2.277,1.766-3.705,2.133v2.263c0,1.104-0.896,2-2,2c-1.104,0-2-0.896-2-2v-2.263c-1.428-0.367-2.703-1.113-3.705-2.133l-1.954,1.127c-0.957,0.554-2.18,0.226-2.731-0.73c-0.553-0.957-0.225-2.18,0.731-2.732l1.958-1.129c-0.189-0.683-0.298-1.396-0.298-2.139c0-0.741,0.108-1.455,0.298-2.137l-1.958-1.13c-0.956-0.553-1.284-1.775-0.731-2.732c0.552-0.956,1.774-1.284,2.731-0.731l1.954,1.128c1.002-1.02,2.277-1.766,3.705-2.134v-2.262c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2v2.262c1.428,0.368,2.702,1.114,3.705,2.134l1.954-1.128c0.957-0.553,2.18-0.225,2.731,0.731C60.943,44.958,60.615,46.181,59.659,46.733z"/>
                <circle
                class="climacon-component climacon-component-fill climacon-component-fill_snowflake"
                fill="#FFFFFF"
                cx="50"
                cy="50"
                r="4"/>
            </g>
        </g>
    </svg><!-- snowflakeFill --> ';
}

function owmw_wind() {
	return '
		<svg
        version="1.1"
        id="wind"
        class="climacon climacon-wind"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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

function owmw_tornado() {
	return '
		<svg
        version="1.1"
        id="tornado"
        class="climacon climacon-tornado"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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

function owmw_cloud() {
	return '
		<svg
        version="1.1"
        id="cloud"
        class="climacon climacon-cloud"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloud">
            <g class="climacon-componentWrap climacon-componentWrap_cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M43.945,65.639c-8.835,0-15.998-7.162-15.998-15.998c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.168,65.639,47.143,65.639,43.945,65.639z"/>
            </g>
        </g>
    </svg><!-- cloud --> ';
}

function owmw_cloudFill() {
	return '
		<svg
        version="1.1"
        id="cloudFill"
        class="climacon climacon-cloudFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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

function owmw_cloudSun() {
	return '
		<svg
        version="1.1"
        id="cloudSun"
        class="climacon climacon-cloudSun"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path
            d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-cloudSun-iconWrap">
            <g clip-path="url(#cloudFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M44.033,65.641c-8.836,0-15.999-7.162-15.999-15.998c0-8.835,7.163-15.998,15.999-15.998c6.006,0,11.233,3.312,13.969,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.26,65.641,47.23,65.641,44.033,65.641z"/>
            </g>
        </g>
    </svg><!-- cloudSun --> ';
}

function owmw_cloudSunFill() {
	return '
		<svg
        version="1.1"
        id="cloudSunFill"
        class="climacon climacon-cloudSunFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-cloudSunFill-iconWrap">
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
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="58.033"
                    cy="41.612"
                    r="11.999"
                    />
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="58.033"
                    cy="41.612" r="7.999"
                    />
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M44.033,65.641c-8.836,0-15.999-7.162-15.999-15.998c0-8.835,7.163-15.998,15.999-15.998c6.006,0,11.233,3.312,13.969,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.26,65.641,47.23,65.641,44.033,65.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                fill="#FFFFFF"
                d="M60.035,61.641c4.418,0,8-3.582,8-7.998c0-4.418-3.582-8-8-8c-1.6,0-3.082,0.481-4.334,1.291c-1.23-5.316-5.976-9.29-11.668-9.29c-6.627,0-11.999,5.372-11.999,11.999c0,6.627,5.372,11.998,11.999,11.998C47.65,61.641,57.016,61.641,60.035,61.641z"/>
            </g>
        </g>
    </svg><!-- cloudSunFill --> ';
}

function owmw_cloudMoon() {
	return '
		<svg
        version="1.1"
        id="cloudMoon"
        class="climacon climacon-cloudMoon"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path
            d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudMoon">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_moon"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M44.033,65.641c-8.836,0-15.999-7.162-15.999-15.998c0-8.835,7.163-15.998,15.999-15.998c6.006,0,11.233,3.312,13.969,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.26,65.641,47.23,65.641,44.033,65.641z"/>
            </g>
        </g>
    </svg><!-- cloudMoon --> ';
}

function owmw_cloudMoonFill() {
	return '
		<svg
        version="1.1"
        id="cloudMoonFill"
        class="climacon climacon-cloudMoonFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudMoonFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_moon"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M44.033,65.641c-8.836,0-15.999-7.162-15.999-15.998c0-8.835,7.163-15.998,15.999-15.998c6.006,0,11.233,3.312,13.969,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.26,65.641,47.23,65.641,44.033,65.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                fill="#FFFFFF"
                d="M60.035,61.641c4.418,0,8-3.582,8-7.998c0-4.418-3.582-8-8-8c-1.6,0-3.082,0.481-4.334,1.291c-1.23-5.316-5.976-9.29-11.668-9.29c-6.627,0-11.999,5.372-11.999,11.999c0,6.627,5.372,11.998,11.999,11.998C47.65,61.641,57.016,61.641,60.035,61.641z"/>
            </g>
        </g>
    </svg><!-- cloudMoonFill --> ';
}

function owmw_cloudDrizzle() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzle"
        class="climacon climacon-cloudDrizzle"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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

function owmw_cloudDrizzleFill() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleFill"
        class="climacon climacon-cloudDrizzleFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleFill">
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
    </svg><!-- cloudDrizzleFill --> ';
}

function owmw_cloudDrizzleSun() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleSun"
        class="climacon climacon-cloudDrizzleSun"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleSun">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
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

            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.944v-4.381c2.387-1.386,3.998-3.961,3.998-6.92c0-4.418-3.58-8-7.998-8c-1.603,0-3.084,0.481-4.334,1.291c-1.232-5.316-5.973-9.29-11.664-9.29c-6.628,0-11.999,5.372-11.999,12c0,3.549,1.55,6.729,3.998,8.926v4.914c-4.776-2.769-7.998-7.922-7.998-13.84c0-8.836,7.162-15.999,15.999-15.999c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.336-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.997,58.864,68.655,63.296,63.999,64.944z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleSun --> ';
}

function owmw_cloudDrizzleSunFill() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleSunFill"
        class="climacon climacon-cloudDrizzleSunFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleSunFill">
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
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="58.033"
                    cy="41.612"
                    r="11.999"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="58.033"
                    cy="41.612" r="7.999"/>
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
    </svg><!-- cloudDrizzleSunFill --> ';
}

function owmw_cloudDrizzleMoon() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleMoon"
        class="climacon climacon-cloudDrizzleMoon"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleMoon">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
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
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.944v-4.381c2.387-1.386,3.998-3.961,3.998-6.92c0-4.418-3.58-8-7.998-8c-1.603,0-3.084,0.481-4.334,1.291c-1.232-5.316-5.973-9.29-11.664-9.29c-6.628,0-11.999,5.372-11.999,12c0,3.549,1.55,6.729,3.998,8.926v4.914c-4.776-2.769-7.998-7.922-7.998-13.84c0-8.836,7.162-15.999,15.999-15.999c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.336-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.997,58.864,68.655,63.296,63.999,64.944z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleMoon --> ';
}

function owmw_cloudDrizzleMoonFill() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleMoonFill"
        class="climacon climacon-cloudDrizzleMoonFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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

function owmw_cloudDrizzleAlt() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleAlt"
        class="climacon climacon-cloudDrizzleAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                id="Drizzle-Left_1_"
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

function owmw_cloudDrizzleFillAlt() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleAlt"
        class="climacon climacon-cloudDrizzleFillAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleFillAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                id="Drizzle-Left_1_"
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
    </svg><!-- cloudDrizzleFillAlt --> ';
}

function owmw_cloudDrizzleSunAlt() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleSunAlt"
        class="climacon climacon-cloudDrizzleSunAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleSunAlt">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
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
                id="Drizzle-Left_1_"
                d="M56.969,57.672l-2.121,2.121c-1.172,1.172-1.172,3.072,0,4.242c1.17,1.172,3.07,1.172,4.24,0c1.172-1.17,1.172-3.07,0-4.242L56.969,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M50.088,57.672l-2.119,2.121c-1.174,1.172-1.174,3.07,0,4.242c1.17,1.172,3.068,1.172,4.24,0s1.172-3.07,0-4.242L50.088,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M43.033,57.672l-2.121,2.121c-1.172,1.172-1.172,3.07,0,4.242s3.07,1.172,4.244,0c1.172-1.172,1.172-3.07,0-4.242L43.033,57.672z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.944v-4.381c2.387-1.386,3.998-3.961,3.998-6.92c0-4.418-3.58-8-7.998-8c-1.603,0-3.084,0.481-4.334,1.291c-1.232-5.316-5.973-9.29-11.664-9.29c-6.628,0-11.999,5.372-11.999,12c0,3.549,1.55,6.729,3.998,8.926v4.914c-4.776-2.769-7.998-7.922-7.998-13.84c0-8.836,7.162-15.999,15.999-15.999c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.336-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.997,58.864,68.655,63.296,63.999,64.944z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleSunAlt --> ';
}

function owmw_cloudDrizzleSunFillAlt() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleSunFillAlt"
        class="climacon climacon-cloudDrizzleSunFillAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleSunFillAlt">
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
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="58.033"
                    cy="41.612"
                    r="11.999"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="58.033"
                    cy="41.612" r="7.999"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                id="Drizzle-Left_1_"
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
    </svg><!-- cloudDrizzleSunFillAlt --> ';
}

function owmw_cloudDrizzleMoonAlt() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleMoonAlt"
        class="climacon climacon-cloudDrizzleMoonAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudDrizzleMoonAlt">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-drizzle">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-left"
                id="Drizzle-Left_1_"
                d="M56.969,57.672l-2.121,2.121c-1.172,1.172-1.172,3.072,0,4.242c1.17,1.172,3.07,1.172,4.24,0c1.172-1.17,1.172-3.07,0-4.242L56.969,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-middle"
                d="M50.088,57.672l-2.119,2.121c-1.174,1.172-1.174,3.07,0,4.242c1.17,1.172,3.068,1.172,4.24,0s1.172-3.07,0-4.242L50.088,57.672z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_drizzle climacon-component-stroke_drizzle-right"
                d="M43.033,57.672l-2.121,2.121c-1.172,1.172-1.172,3.07,0,4.242s3.07,1.172,4.244,0c1.172-1.172,1.172-3.07,0-4.242L43.033,57.672z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.943,41.642c-0.696,0-1.369,0.092-2.033,0.205c-2.736-4.892-7.961-8.203-13.965-8.203c-8.835,0-15.998,7.162-15.998,15.997c0,5.992,3.3,11.207,8.177,13.947c0.276-1.262,0.892-2.465,1.873-3.445l0.057-0.057c-3.644-2.061-6.106-5.963-6.106-10.445c0-6.626,5.372-11.998,11.998-11.998c5.691,0,10.433,3.974,11.666,9.29c1.25-0.81,2.732-1.291,4.332-1.291c4.418,0,8,3.581,8,7.999c0,3.443-2.182,6.371-5.235,7.498c0.788,1.146,1.194,2.471,1.222,3.807c4.666-1.645,8.014-6.077,8.014-11.305C71.941,47.014,66.57,41.642,59.943,41.642z"/>
            </g>
        </g>
    </svg><!-- cloudDrizzleMoonAlt --> ';
}

function owmw_cloudDrizzleMoonFillAlt() {
	return '
		<svg
        version="1.1"
        id="cloudDrizzleMoonFillAlt"
        class="climacon climacon-cloudDrizzleMoonFillAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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
                id="Drizzle-Left_1_"
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

function owmw_cloudRain() {
	return '
		<svg
        version="1.1"
        id="cloudRain"
        class="climacon climacon-cloudRain"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
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
            <g class="climacon-wrapperComponent climacon-wrapperComponent_cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.943,64.941v-4.381c2.389-1.384,4-3.961,4-6.92c0-4.417-3.582-8-8-8c-1.601,0-3.082,0.48-4.334,1.291c-1.23-5.317-5.973-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.998c0,3.549,1.551,6.728,4,8.924v4.916c-4.777-2.768-8-7.922-8-13.84c0-8.835,7.163-15.997,15.998-15.997c6.004,0,11.229,3.311,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.372,11.998,12C71.941,58.863,68.602,63.293,63.943,64.941z"/>
            </g>
        </g>
    </svg><!-- cloudRain --> ';
}

function owmw_cloudRainFill() {
	return '
		<svg
        version="1.1"
        id="cloudRainFill"
        class="climacon climacon-cloudRainFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainFill">
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
    </svg><!-- cloudRainFill --> ';
}

function owmw_cloudRainSun() {
	return '
		<svg
        version="1.1"
        id="cloudRainSun"
        class="climacon climacon-cloudRainSun"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainSun">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
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

function owmw_cloudRainSunFill() {
	return '
		<svg
        version="1.1"
        id="cloudRainSunFill"
        class="climacon climacon-cloudRainSunFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainSunFill">
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
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="58.033"
                    cy="41.612"
                    r="11.999"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="58.033"
                    cy="41.612" r="7.999"/>
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
    </svg><!-- cloudRainSunFill --> ';
}

function owmw_cloudRainMoon() {
	return '
		<svg
        version="1.1"
        id="cloudRainMoon"
        class="climacon climacon-cloudRainMoon"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainMoon">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
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
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.943,41.642c-0.696,0-1.369,0.092-2.033,0.205c-2.736-4.892-7.961-8.203-13.965-8.203c-8.835,0-15.998,7.162-15.998,15.997c0,5.992,3.3,11.207,8.177,13.947c0.276-1.262,0.892-2.465,1.873-3.445l0.057-0.057c-3.644-2.061-6.106-5.963-6.106-10.445c0-6.626,5.372-11.998,11.998-11.998c5.691,0,10.433,3.974,11.666,9.29c1.25-0.81,2.732-1.291,4.332-1.291c4.418,0,8,3.581,8,7.999c0,3.443-2.182,6.371-5.235,7.498c0.788,1.146,1.194,2.471,1.222,3.807c4.666-1.645,8.014-6.077,8.014-11.305C71.941,47.014,66.57,41.642,59.943,41.642z"/>
            </g>
        </g>
    </svg><!-- cloudRainMoon --> ';
}

function owmw_cloudRainMoonFill() {
	return '
		<svg
        version="1.1"
        id="cloudRainMoonFill"
        class="climacon climacon-cloudRainMoonFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainMoonFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
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
    </svg><!-- cloudRainMoonFill --> ';
}

function owmw_cloudRainAlt() {
	return '
		<svg
        version="1.1"
        id="cloudRainAlt"
        class="climacon climacon-cloudRainAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
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
            <g class="climacon-wrapperComponent climacon-wrapperComponent_cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.999,65.641c-0.267,0-0.614,0-1,0c0-1.373-0.319-2.742-0.942-4c0.776,0,1.45,0,1.942,0c4.418,0,7.999-3.58,7.999-7.998c0-4.418-3.581-8-7.999-8c-1.601,0-3.083,0.481-4.334,1.29c-1.231-5.316-5.973-9.289-11.664-9.289c-6.627,0-11.998,5.372-11.998,11.998c0,5.953,4.339,10.879,10.023,11.822c-0.637,1.218-0.969,2.55-1.012,3.888c-7.406-1.399-13.012-7.896-13.012-15.709c0-8.835,7.162-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.204c0.664-0.114,1.337-0.205,2.033-0.205c6.627,0,11.998,5.372,11.998,12C71.996,60.27,66.626,65.641,59.999,65.641z"/>
            </g>
        </g>
    </svg><!-- cloudRainAlt --> ';
}

function owmw_cloudRainAltFill() {
	return '
		<svg
        version="1.1"
        id="cloudRainAltFill"
        class="climacon climacon-cloudRainAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainAltFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-rain climacon-wrapperComponent-rain_alt">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
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
    </svg><!-- cloudRainAlt --> ';
}

function owmw_cloudSunRainAlt() {
	return '
		<svg
        version="1.1"
        id="cloudRainSunAlt"
        class="climacon climacon-cloudSunRainAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainSunAlt">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
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
    </svg><!-- cloudRainSunAlt --> ';
}

function owmw_cloudSunRainAltFill() {
	return '
		<svg
        version="1.1"
        id="cloudRainSunAltFill"
        class="climacon climacon-cloudSunRainAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainSunAltFill">
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
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="58.033"
                    cy="41.612"
                    r="11.999"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="58.033"
                    cy="41.612" r="7.999"/>
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
    </svg><!-- cloudRainSunAltFill --> ';
}

function owmw_cloudMoonRainAlt() {
	return '
		<svg
        version="1.1"
        id="cloudRainMoonAlt"
        class="climacon climacon-cloudMoonRainAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainMoonAlt">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
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
            <g class="climacon-wrapperComponent climacon-wrapperComponent_cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M59.999,65.641c-0.267,0-0.614,0-1,0c0-1.373-0.319-2.742-0.942-4c0.776,0,1.45,0,1.942,0c4.418,0,7.999-3.58,7.999-7.998c0-4.418-3.581-8-7.999-8c-1.601,0-3.083,0.481-4.334,1.29c-1.231-5.316-5.973-9.289-11.664-9.289c-6.627,0-11.998,5.372-11.998,11.998c0,5.953,4.339,10.879,10.023,11.822c-0.637,1.218-0.969,2.55-1.012,3.888c-7.406-1.399-13.012-7.896-13.012-15.709c0-8.835,7.162-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.204c0.664-0.114,1.337-0.205,2.033-0.205c6.627,0,11.998,5.372,11.998,12C71.996,60.27,66.626,65.641,59.999,65.641z"/>
            </g>
        </g>
    </svg><!-- cloudRainMoonAlt --> ';
}

function owmw_cloudMoonRainAltFill() {
	return '
		<svg
        version="1.1"
        id="cloudRainMoonAltFill"
        class="climacon climacon-cloudMoonRainAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudRainSunAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-rain climacon-wrapperComponent-rain_alt">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_rain climacon-component-stroke_rain- alt"
                d="M50.001,58.568l3.535,3.535c1.95,1.953,1.95,5.119,0,7.07c-1.953,1.953-5.119,1.953-7.07,0c-1.953-1.951-1.953-5.117,0-7.07L50.001,58.568z"/>
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
    </svg><!-- cloudRainMoonAltFill --> ';
}

function owmw_cloudHailAlt() {
	return '
		<svg
        version="1.1"
        id="cloudHailAlt"
        class="climacon climacon-cloudHailAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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

function owmw_cloudHailAltFill() {
	return '
		<svg
        version="1.1"
        id="cloudHailAltFill"
        class="climacon climacon-cloudHailAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudHailAltFill">
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
    </svg><!-- cloudHailAltFill --> ';
}

function owmw_cloudHailAltSun() {
	return '
		<svg
        version="1.1"
        id="cloudHailAltSun"
        class="climacon climacon-cloudHailAltSun"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudHailAltSun">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
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
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.941v-4.381c2.39-1.384,3.999-3.961,3.999-6.92c0-4.417-3.581-8-7.998-8c-1.602,0-3.084,0.48-4.334,1.291c-1.23-5.317-5.974-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.998c0,3.549,1.55,6.728,3.999,8.924v4.916c-4.776-2.768-7.998-7.922-7.998-13.84c0-8.835,7.162-15.997,15.997-15.997c6.004,0,11.229,3.311,13.966,8.203c0.663-0.113,1.336-0.205,2.033-0.205c6.626,0,11.998,5.372,11.998,12C71.998,58.863,68.656,63.293,63.999,64.941z"/>
            </g>
        </g>
    </svg><!-- cloudHailAltSun --> ';
}

function owmw_cloudHailAltSunFill() {
	return '
		<svg
        version="1.1"
        id="cloudHailAltSun"
        class="climacon climacon-cloudHailAltSun"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudHailAltSun">
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
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="58.033"
                    cy="41.612"
                    r="11.999"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="58.033"
                    cy="41.612" r="7.999"/>
                </g>
            </g>
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
    </svg><!-- cloudHailAltSun --> ';
}

function owmw_cloudHailAltMoon() {
	return '
		<svg
        version="1.1"
        id="cloudHailAltMoon"
        class="climacon climacon-cloudHailAltMoon"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudHailAltMoon">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                    <path
                    class="climacon-component climacon-component-fill climacon-component-fill_moon"
                    fill="#FFFFFF"
                    d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
                </g>
            </g>
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
            <g class="climacon-wrapperComponent climacon-wrapperComponent-cloud" clip-path="url(#cloudFillClip)">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M63.999,64.941v-4.381c2.39-1.384,3.999-3.961,3.999-6.92c0-4.417-3.581-8-7.998-8c-1.602,0-3.084,0.48-4.334,1.291c-1.23-5.317-5.974-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.998c0,3.549,1.55,6.728,3.999,8.924v4.916c-4.776-2.768-7.998-7.922-7.998-13.84c0-8.835,7.162-15.997,15.997-15.997c6.004,0,11.229,3.311,13.966,8.203c0.663-0.113,1.336-0.205,2.033-0.205c6.626,0,11.998,5.372,11.998,12C71.998,58.863,68.656,63.293,63.999,64.941z"/>
            </g>
        </g>
    </svg><!-- cloudHailAltMoon --> ';
}

function owmw_cloudHailAltMoonFill() {
	return '
		<svg
        version="1.1"
        id="cloudHailAltMoonFill"
        class="climacon climacon-cloudHailAltMoonFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudHailAltMoon">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
            </g>
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
    </svg><!-- cloudHailAltMoonFill --> ';
}

function owmw_cloudSnow() {
	return '
		<svg
        version="1.1"
        id="cloudSnow"
        class="climacon climacon-cloudSnow"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">

        <g class="climacon-iconWrap climacon-iconWrap-cloudSnow">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snow" clip-path="url(#snowFillClip)">
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

function owmw_cloudSnowFill() {
	return '
		<svg
        version="1.1"
        id="cloudSnowFill"
        class="climacon climacon-cloudSnowFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowFill">
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
    </svg><!-- cloudSnowFill --> ';
}

function owmw_cloudSnowSun() {
	return '
		<svg
        version="1.1"
        id="cloudSnowSun"
        class="climacon climacon-cloudSnowSun"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowSun">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
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

function owmw_cloudSnowSunFill() {
	return '
		<svg
        version="1.1"
        id="cloudSnowSunFill"
        class="climacon climacon-cloudSnowSunFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowSunFill">
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
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="58.033"
                    cy="41.612"
                    r="11.999"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="58.033"
                    cy="41.612" r="7.999"/>
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
    </svg><!-- cloudSnowSunFill --> ';
}

function owmw_cloudSnowMoon() {
	return '
		<svg
        version="1.1"
        id="cloudSnowMoon"
        class="climacon climacon-cloudSnowMoon"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowMoon">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
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

function owmw_cloudSnowMoonFill() {
	return '
		<svg
        version="1.1"
        id="cloudSnowMoonFill"
        class="climacon climacon-cloudSnowMoonFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowMoonFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
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
    </svg><!-- cloudSnowMoonFill --> ';
}

function owmw_cloudSnowAlt() {
	return '
		<svg
        version="1.1"
        id="cloudSnowAlt"
        class="climacon climacon-cloudSnowAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="snowFillClip">
            <path d="M15,15v70h70V15H15z M50,65.641c-1.104,0-2-0.896-2-2c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2S51.104,65.641,50,65.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowAlt">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snowAlt">
                <g class="climacon-component climacon-component climacon-component-snowAlt" clip-path="url(#snowFillClip)">
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

function owmw_cloudSnowAltFill() {
	return '
		<svg
        version="1.1"
        id="cloudSnowAltFill"
        class="climacon climacon-cloudSnowAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowAltFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snowAlt">
                <g class="climacon-component climacon-component climacon-component-snowAlt">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_snowAlt"
                    d="M43.072,59.641c0.553-0.957,1.775-1.283,2.732-0.731L48,60.176v-2.535c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2v2.535l2.195-1.268c0.957-0.551,2.18-0.225,2.73,0.732c0.553,0.957,0.225,2.18-0.73,2.731l-2.196,1.269l2.196,1.268c0.955,0.553,1.283,1.775,0.73,2.732c-0.552,0.954-1.773,1.282-2.73,0.729L52,67.104v2.535c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2v-2.535l-2.195,1.269c-0.957,0.553-2.18,0.226-2.732-0.729c-0.552-0.957-0.225-2.181,0.732-2.732L46,63.641l-2.195-1.268C42.848,61.82,42.521,60.598,43.072,59.641z"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_snowAlt"
                    fill="#FFFFFF"
                    cx="50"
                    cy="63.641"
                    r="2"/>
                </g>
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
    </svg><!-- cloudSnowAltFill --> ';
}

function owmw_cloudSnowSunAlt() {
	return '
		<svg
        version="1.1"
        id="cloudSnowSunAlt"
        class="climacon climacon-cloudSnowSunAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <clipPath id="snowFillClip">
            <path d="M15,15v70h70V15H15z M50,65.641c-1.104,0-2-0.896-2-2c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2S51.104,65.641,50,65.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowSunAlt">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snowAlt">
                <g class="climacon-component climacon-component climacon-component-snowAlt" clip-path="url(#snowFillClip)">
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

function owmw_cloudSnowSunAltFill() {
	return '
		<svg
        version="1.1"
        id="cloudSnowSunAltFill"
        class="climacon climacon-cloudSnowSunAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowSunAltFill">
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
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="58.033"
                    cy="41.612"
                    r="11.999"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="58.033"
                    cy="41.612" r="7.999"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snowAlt">
                <g class="climacon-component climacon-component climacon-component-snowAlt">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_snowAlt"
                    d="M43.072,59.641c0.553-0.957,1.775-1.283,2.732-0.731L48,60.176v-2.535c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2v2.535l2.195-1.268c0.957-0.551,2.18-0.225,2.73,0.732c0.553,0.957,0.225,2.18-0.73,2.731l-2.196,1.269l2.196,1.268c0.955,0.553,1.283,1.775,0.73,2.732c-0.552,0.954-1.773,1.282-2.73,0.729L52,67.104v2.535c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2v-2.535l-2.195,1.269c-0.957,0.553-2.18,0.226-2.732-0.729c-0.552-0.957-0.225-2.181,0.732-2.732L46,63.641l-2.195-1.268C42.848,61.82,42.521,60.598,43.072,59.641z"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_snowAlt"
                    fill="#FFFFFF"
                    cx="50"
                    cy="63.641"
                    r="2"/>
                </g>
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
    </svg><!-- cloudSnowSunAltFill --> ';
}

function owmw_cloudSnowAlt2() {
	return '
		<svg
        version="1.1"
        id="cloudSnowAlt"
        class="climacon climacon-cloudSnowAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="snowFillClip">
            <path d="M15,15v70h70V15H15z M50,65.641c-1.104,0-2-0.896-2-2c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2S51.104,65.641,50,65.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowAlt">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snowAlt">
                <g class="climacon-component climacon-component climacon-component-snowAlt" clip-path="url(#snowFillClip)">
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

function owmw_cloudSnowAltFill2() {
	return '
		<svg
        version="1.1"
        id="cloudSnowAltFill"
        class="climacon climacon-cloudSnowAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudSnowAltFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-snowAlt">
                <g class="climacon-component climacon-component climacon-component-snowAlt">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_snowAlt"
                    d="M43.072,59.641c0.553-0.957,1.775-1.283,2.732-0.731L48,60.176v-2.535c0-1.104,0.896-2,2-2c1.104,0,2,0.896,2,2v2.535l2.195-1.268c0.957-0.551,2.18-0.225,2.73,0.732c0.553,0.957,0.225,2.18-0.73,2.731l-2.196,1.269l2.196,1.268c0.955,0.553,1.283,1.775,0.73,2.732c-0.552,0.954-1.773,1.282-2.73,0.729L52,67.104v2.535c0,1.105-0.896,2-2,2c-1.104,0-2-0.895-2-2v-2.535l-2.195,1.269c-0.957,0.553-2.18,0.226-2.732-0.729c-0.552-0.957-0.225-2.181,0.732-2.732L46,63.641l-2.195-1.268C42.848,61.82,42.521,60.598,43.072,59.641z"/>
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_snowAlt"
                    fill="#FFFFFF"
                    cx="50"
                    cy="63.641"
                    r="2"/>
                </g>
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
    </svg><!-- cloudSnowMoonAltFill --> ';
}

function owmw_cloudFog() {
	return '
		<svg
        version="1.1"
        id="cloudFog"
        class="climacon climacon-cloudFog"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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

function owmw_cloudFogFill() {
	return '
		<svg
        version="1.1"
        id="cloudFogFill"
        class="climacon climacon-cloudFogFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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
                <path 
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M71.287,49.355c-1.659-4.632-6.08-7.954-11.289-7.954c-0.695,0-1.369,0.091-2.033,0.205C55.229,36.72,50.004,33.413,44,33.413c-8.824,0-15.977,7.134-15.996,15.942H71.287z"/>
                <path 
                fill="#FFFFFF" 
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                d="M66.897,49.376c-1.389-2.369-3.955-3.965-6.899-3.965c-1.602,0-3.082,0.48-4.334,1.291c-1.23-5.316-5.973-9.291-11.664-9.291c-6.615,0-11.977,5.354-11.996,11.965H66.897z"/>
            </g>
        </g>
    </svg><!--cloudFogFill --> ';
}

function owmw_cloudFogSun() {
	return '
		<svg
        version="1.1"
        id="cloudFogSun"
        class="climacon climacon-cloudFogSun"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"
            />
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogSun">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
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

function owmw_cloudFogSunFill() {
	return '
		<svg
        version="1.1"
        id="cloudFogSunFill"
        class="climacon climacon-cloudFogSunFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogSunFill">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                        <circle
                        class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                        fill="#FFFFFF"
                        cx="58.033"
                        cy="41.612" r="7.999"/>
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
                <path 
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M71.287,49.355c-1.659-4.632-6.08-7.954-11.289-7.954c-0.695,0-1.369,0.091-2.033,0.205C55.229,36.72,50.004,33.413,44,33.413c-8.824,0-15.977,7.134-15.996,15.942H71.287z"/>
                <path 
                fill="#FFFFFF" 
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                d="M66.897,49.376c-1.389-2.369-3.955-3.965-6.899-3.965c-1.602,0-3.082,0.48-4.334,1.291c-1.23-5.316-5.973-9.291-11.664-9.291c-6.615,0-11.977,5.354-11.996,11.965H66.897z"/>
            </g>
        </g>
    </svg><!-- cloudFogSunFill --> ';
}

function owmw_cloudFogMoon() {
	return '
		<svg
        version="1.1"
        id="cloudFogMoon"
        class="climacon climacon-cloudFogMoon"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogMoon">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
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

function owmw_cloudFogMoonFill() {
	return '
		<svg
        version="1.1"
        id="cloudFogMoonFill"
        class="climacon climacon-cloudFogMoonFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogMoonFill">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                    <path
                    class="climacon-component climacon-component-fill climacon-component-fill_moon"
                    fill="#FFFFFF"
                    d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
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
                <path 
                class="climacon-component climacon-component-stroke climacon-component-stroke_cloud"
                d="M71.287,49.355c-1.659-4.632-6.08-7.954-11.289-7.954c-0.695,0-1.369,0.091-2.033,0.205C55.229,36.72,50.004,33.413,44,33.413c-8.824,0-15.977,7.134-15.996,15.942H71.287z"/>
                <path 
                fill="#FFFFFF" 
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                d="M66.897,49.376c-1.389-2.369-3.955-3.965-6.899-3.965c-1.602,0-3.082,0.48-4.334,1.291c-1.23-5.316-5.973-9.291-11.664-9.291c-6.615,0-11.977,5.354-11.996,11.965H66.897z"/>
            </g>
        </g>
    </svg><!-- cloudFogMoonFill --> ';
}

function owmw_cloudFogAlt() {
	return '
		<svg
        version="1.1"
        id="cloudFogAlt"
        class="climacon climacon-cloudFogAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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

function owmw_cloudFogAltFill() {
	return '
		<svg
        version="1.1"
        id="cloudFogAltFill"
        class="climacon climacon-cloudFogAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogAltFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-Fog">
                <g class="climacon-componentWrap climacon-componentWrap_cloud">
                    <path
                    class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                    fill="#FFFFFF"
                    d="M43.945,65.639c-8.835,0-15.998-7.162-15.998-15.998c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.168,65.639,47.143,65.639,43.945,65.639z"/>
                </g>
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
    </svg><!--cloudFogAltFill --> ';
}

function owmw_cloudFogSunAlt() {
	return '
		<svg
        version="1.1"
        id="cloudFogSunAlt"
        class="climacon climacon-cloudFogSunAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogSunAlt">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
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

function owmw_cloudFogSunAltFill() {
	return '
		<svg
        version="1.1"
        id="cloudFogSunAltFill"
        class="climacon climacon-cloudFogSunAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogSunAlt">
            <g class="climacon-componentWrap climacon-componentWrap_cloud">
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                fill="#FFFFFF"
                d="M43.945,65.639c-8.835,0-15.998-7.162-15.998-15.998c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.168,65.639,47.143,65.639,43.945,65.639z"/>
            </g>
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                        <circle
                        class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                        fill="#FFFFFF"
                        cx="58.033"
                        cy="41.612" r="7.999"/>
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
    </svg><!--cloudFogSunAltFill --> ';
}

function owmw_cloudFogMoonAlt() {
	return '
		<svg
        version="1.1"
        id="cloudFogMoonAlt"
        class="climacon climacon-cloudFogMoonAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
       <clipPath id="newMoonCloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,65.638c-2.775,0-12.801,0-15.998,0c-8.836,0-15.998-7.162-15.998-15.998c0-8.835,7.162-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.941,60.265,66.57,65.638,59.943,65.638z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogMoon">
            <g clip-path="url(#newMoonCloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
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

function owmw_cloudFogMoonAltFill() {
	return '
		<svg
        version="1.1"
        id="cloudFogMoonAltFill"
        class="climacon climacon-cloudFogMoonAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="newMoonCloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,65.638c-2.775,0-12.801,0-15.998,0c-8.836,0-15.998-7.162-15.998-15.998c0-8.835,7.162-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12C71.941,60.265,66.57,65.638,59.943,65.638z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudFogMoonAltFill">
            <g class="climacon-componentWrap climacon-componentWrap_cloud">
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_cloud"
                fill="#FFFFFF"
                d="M43.945,65.639c-8.835,0-15.998-7.162-15.998-15.998c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,6.625-5.371,11.998-11.998,11.998C57.168,65.639,47.143,65.639,43.945,65.639z"/>
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
            <g clip-path="url(#newMoonCloudFillClip)">

            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_moonBody"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
            </g>
        </g>
        </g>
    </svg><!--cloudFogMoonAltFill --> ';
}

function owmw_cloudLightning() {
	return '
		<svg
        version="1.1"
        id="cloudLightning"
        class="climacon climacon-cloudLightning"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
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

function owmw_cloudLightningFill() {
	return '
		<svg
        version="1.1"
        id="cloudLightningFill"
        class="climacon climacon-cloudLightningFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudLightningFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-lightning">
                <polygon
                class="climacon-component climacon-component-stroke climacon-component-stroke_lightning"
                points="48.001,51.641 57.999,51.641 52,61.641 58.999,61.641 46.001,77.639 49.601,65.641 43.001,65.641 "/>
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
    </svg><!--cloudLightningFill --> ';
}

function owmw_cloudLightningSun() {
	return '
		<svg
        version="1.1"
        id="cloudLightning"
        class="climacon climacon-cloudLightningSun"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>
        <clipPath id="sunCloudFillClip">
            <path
            d="M15,15v70h70V15H15z M57.945,49.641c-4.417,0-8-3.582-8-7.999c0-4.418,3.582-7.999,8-7.999s7.998,3.581,7.998,7.999C65.943,46.059,62.362,49.641,57.945,49.641z"/>
        </clipPath>
        <clipPath id="cloudSunFillClip">
            <path
            d="M15,15v70h20.947V63.481c-4.778-2.767-8-7.922-8-13.84c0-8.836,7.163-15.998,15.998-15.998c6.004,0,11.229,3.312,13.965,8.203c0.664-0.113,1.338-0.205,2.033-0.205c6.627,0,11.998,5.373,11.998,12c0,5.262-3.394,9.723-8.107,11.341V85H85V15H15z"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-cloudLightning">
            <g clip-path="url(#cloudSunFillClip)">
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" clip-path="url(#sunCloudFillClip)">
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                    </g>
                </g>
            </g>
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
    </svg><!--cloudLightningSun --> ';
}

function owmw_cloudLightningSunFill() {
	return '
		<svg
        version="1.1"
        id="cloudLightningSunFill"
        class="climacon climacon-cloudLightningSunFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">

        <g class="climacon-iconWrap climacon-iconWrap-cloudLightning">
            <g clip-path="url(#clip)">
                <g class="climacon-componentWrap climacon-componentWrap-sun climacon-componentWrap-sun_cloud">
                    <g class="climacon-componentWrap climacon-componentWrap_sunSpoke" >
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
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody" >
                        <circle
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="58.033"
                        cy="41.612"
                        r="11.999"/>
                        <circle
                        class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                        fill="#FFFFFF"
                        cx="58.033"
                        cy="41.612" r="7.999"/>
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-lightning">
                <polygon
                class="climacon-component climacon-component-stroke climacon-component-stroke_lightning"
                points="48.001,51.641 57.999,51.641 52,61.641 58.999,61.641 46.001,77.639 49.601,65.641 43.001,65.641 "/>
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
    </svg><!--cloudLightningSunFill --> ';
}

function owmw_cloudLightningMoon() {
	return '
		<svg
        version="1.1"
        id="cloudLightningMoon"
        class="climacon climacon-cloudLightningMoon"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="moonCloudFillClip">
            <path
            d="M0,0v100h100V0H0z M60.943,46.641c-4.418,0-7.999-3.582-7.999-7.999c0-3.803,2.655-6.979,6.211-7.792c0.903,4.854,4.726,8.676,9.579,9.58C67.922,43.986,64.745,46.641,60.943,46.641z"/>
        </clipPath>
        <clipPath id="cloudFillClip">
            <path d="M15,15v70h70V15H15z M59.943,61.639c-3.02,0-12.381,0-15.999,0c-6.626,0-11.998-5.371-11.998-11.998c0-6.627,5.372-11.999,11.998-11.999c5.691,0,10.434,3.974,11.665,9.29c1.252-0.81,2.733-1.291,4.334-1.291c4.418,0,8,3.582,8,8C67.943,58.057,64.361,61.639,59.943,61.639z"/>
        </clipPath>

        <g class="climacon-iconWrap climacon-iconWrap-cloudLightningMoon">
            <g clip-path="url(#cloudFillClip)">
                <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud" clip-path="url(#moonCloudFillClip)">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                </g>
            </g>            
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
    </svg><!--cloudLightningMoon --> ';
}

function owmw_cloudLightningMoonFill() {
	return '
		<svg
        version="1.1"
        id="cloudLightningMoonFill"
        class="climacon climacon-cloudLightningMoonFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-cloudLightningMoonFill">
            <g class="climacon-wrapperComponent climacon-wrapperComponent-moon climacon-componentWrap-moon_cloud">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                d="M61.023,50.641c-6.627,0-11.999-5.372-11.999-11.998c0-6.627,5.372-11.999,11.999-11.999c0.755,0,1.491,0.078,2.207,0.212c-0.132,0.576-0.208,1.173-0.208,1.788c0,4.418,3.582,7.999,8,7.999c0.614,0,1.212-0.076,1.788-0.208c0.133,0.717,0.211,1.452,0.211,2.208C73.021,45.269,67.649,50.641,61.023,50.641z"/>
                <path
                class="climacon-component climacon-component-fill climacon-component-fill_moon"
                fill="#FFFFFF"
                d="M59.235,30.851c-3.556,0.813-6.211,3.989-6.211,7.792c0,4.417,3.581,7.999,7.999,7.999c3.802,0,6.979-2.655,7.791-6.211C63.961,39.527,60.139,35.705,59.235,30.851z"/>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-lightning">
                <polygon
                class="climacon-component climacon-component-stroke climacon-component-stroke_lightning"
                points="48.001,51.641 57.999,51.641 52,61.641 58.999,61.641 46.001,77.639 49.601,65.641 43.001,65.641 "/>
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
    </svg><!--cloudLightningMoonFill --> ';
}

/*function owmw_sunrise() {
	return '
		<svg
        version="1.1"
        id="sunrise"
        class="climacon climacon-sunrise"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-sunrise">
            <g class="climacon-componentWrap climacon-componentWrap-sunrise">
                <g class="climacon-componentWrap climacon-componentWrap-sunSpoke">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                    d="M32.003,54h-4c-1.104,0-2,0.896-2,2s0.896,2,2,2h4c1.104,0,2-0.896,2-2S33.106,54,32.003,54z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northEast"
                    d="M38.688,41.859l-2.828-2.828c-0.781-0.78-2.047-0.78-2.828,0c-0.781,0.781-0.781,2.047,0,2.828l2.828,2.828c0.781,0.781,2.047,0.781,2.828,0C39.469,43.906,39.469,42.641,38.688,41.859z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                    d="M50.001,40.002c1.104,0,1.999-0.896,1.999-2v-3.999c0-1.104-0.896-2-1.999-2c-1.105,0-2,0.896-2,2v3.999C48.001,39.106,48.896,40.002,50.001,40.002z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northWest"
                    d="M66.969,39.031c-0.779-0.78-2.048-0.78-2.828,0l-2.828,2.828c-0.779,0.781-0.779,2.047,0,2.828c0.781,0.781,2.049,0.781,2.828,0l2.828-2.828C67.749,41.078,67.749,39.812,66.969,39.031z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-west"
                    d="M71.997,54h-3.999c-1.104,0-1.999,0.896-1.999,2s0.896,2,1.999,2h3.999c1.104,0,2-0.896,2-2S73.104,54,71.997,54z"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M50.001,44.002c-6.627,0-11.999,5.371-11.999,11.998c0,1.404,0.254,2.747,0.697,3.999h4.381c-0.683-1.177-1.079-2.54-1.079-3.999c0-4.418,3.582-7.999,8-7.999c4.417,0,7.998,3.581,7.998,7.999c0,1.459-0.396,2.822-1.078,3.999h4.381c0.443-1.252,0.697-2.595,0.697-3.999C61.999,49.373,56.627,44.002,50.001,44.002z"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-arrow">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_arrow climacon-component-stroke_arrow-up"
                    d="M50.001,63.046c0.552,0,0.999-0.447,0.999-1v-3.827l2.536,2.535c0.39,0.391,1.022,0.391,1.414,0c0.39-0.391,0.39-1.023,0-1.414l-4.242-4.242c-0.391-0.391-1.024-0.391-1.414,0l-4.242,4.242c-0.391,0.391-0.391,1.023,0,1.414c0.391,0.391,1.023,0.391,1.414,0l2.535-2.535v3.827C49.001,62.599,49.448,63.046,50.001,63.046z"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-horizonLine">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_horizonLine"
                    d="M59.999,63.999H40.001c-1.104,0-1.999,0.896-1.999,2s0.896,1.999,1.999,1.999h19.998c1.104,0,2-0.895,2-1.999S61.104,63.999,59.999,63.999z"
                    />
                </g>
            </g>
        </g>
    </svg><!-- sunrise --> ';
}
*/

function owmw_sunriseFill() {
	return '
		<svg
        version="1.1"
        id="sunriseFill"
        class="climacon climacon-sunriseFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-sunriseFill">
            <g class="climacon-componentWrap climacon-componentWrap-sunrise">
                <g class="climacon-componentWrap climacon-componentWrap-sunSpoke">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                    d="M71.997,57.999h-3.998c-1.104,0-2-0.896-2-1.999s0.896-2,2-2h3.998c1.104,0,2,0.896,2,2S73.104,57.999,71.997,57.999z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                    d="M64.143,44.688c-0.781,0.781-2.05,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.778-0.781,2.047-0.781,2.828,0c0.778,0.781,0.778,2.047,0,2.828L64.143,44.688z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                    d="M50.001,40.002c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C52.001,39.106,51.104,40.002,50.001,40.002z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                    d="M35.86,44.688l-2.828-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.781-0.781,2.047-0.781,2.828,0l2.828,2.828c0.781,0.781,0.781,2.047,0,2.828C37.907,45.469,36.641,45.469,35.86,44.688z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                    d="M34.002,56c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.999-0.896-1.999-1.999s0.896-2,1.999-2h4C33.107,54,34.002,54.896,34.002,56z"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="50.001"
                    cy="56"
                    r="11.999"
                    />
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="50.001"
                    cy="56"
                    r="7.999"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-arrow">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_arrow climacon-component-stroke_arrow-up"
                    d="M50.001,63.046c0.552,0,0.999-0.447,0.999-1v-3.827l2.536,2.535c0.39,0.391,1.022,0.391,1.414,0c0.39-0.391,0.39-1.023,0-1.414l-4.242-4.242c-0.391-0.391-1.024-0.391-1.414,0l-4.242,4.242c-0.391,0.391-0.391,1.023,0,1.414c0.391,0.391,1.023,0.391,1.414,0l2.535-2.535v3.827C49.001,62.599,49.448,63.046,50.001,63.046z"/>
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-horizonLine">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_horizonLine"
                    d="M59.999,63.999H40.001c-1.104,0-1.999,0.896-1.999,2s0.896,1.999,1.999,1.999h19.998c1.104,0,2-0.895,2-1.999S61.104,63.999,59.999,63.999z"
                    />
                </g>
            </g>
        </g>
    </svg><!-- sunriseFill --> ';
}

/*
function owmw_sunset() {
	return '
		<svg
        version="1.1"
        id="sunset"
        class="climacon climacon-sunset"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-sunset">
            <g class="climacon-componentWrap climacon-componentWrap-sunset">
                <g class="climacon-componentWrap climacon-componentWrap-sunSpoke">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                    d="M32.003,54h-4c-1.104,0-2,0.896-2,2s0.896,2,2,2h4c1.104,0,2-0.896,2-2S33.106,54,32.003,54z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northEast"
                    d="M38.688,41.859l-2.828-2.828c-0.781-0.78-2.047-0.78-2.828,0c-0.781,0.781-0.781,2.047,0,2.828l2.828,2.828c0.781,0.781,2.047,0.781,2.828,0C39.469,43.906,39.469,42.641,38.688,41.859z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                    d="M50.001,40.002c1.104,0,1.999-0.896,1.999-2v-3.999c0-1.104-0.896-2-1.999-2c-1.105,0-2,0.896-2,2v3.999C48.001,39.106,48.896,40.002,50.001,40.002z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northWest"
                    d="M66.969,39.031c-0.779-0.78-2.048-0.78-2.828,0l-2.828,2.828c-0.779,0.781-0.779,2.047,0,2.828c0.781,0.781,2.049,0.781,2.828,0l2.828-2.828C67.749,41.078,67.749,39.812,66.969,39.031z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-west"
                    d="M71.997,54h-3.999c-1.104,0-1.999,0.896-1.999,2s0.896,2,1.999,2h3.999c1.104,0,2-0.896,2-2S73.104,54,71.997,54z"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    d="M50.001,44.002c-6.627,0-11.999,5.371-11.999,11.998c0,1.404,0.254,2.747,0.697,3.999h4.381c-0.683-1.177-1.079-2.54-1.079-3.999c0-4.418,3.582-7.999,8-7.999c4.417,0,7.998,3.581,7.998,7.999c0,1.459-0.396,2.822-1.078,3.999h4.381c0.443-1.252,0.697-2.595,0.697-3.999C61.999,49.373,56.627,44.002,50.001,44.002z"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-arrow">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_arrow climacon-component-stroke_arrow-down"
                    d="M50,49.107c0.552,0,1,0.448,1,1.002v3.83l2.535-2.535c0.391-0.391,1.022-0.391,1.414,0c0.391,0.391,0.391,1.023,0,1.414l-4.242,4.242c-0.392,0.391-1.022,0.391-1.414,0l-4.242-4.242c-0.391-0.391-0.391-1.023,0-1.414c0.392-0.391,1.023-0.391,1.414,0L49,53.939v-3.83C49,49.555,49.447,49.107,50,49.107z"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-horizonLine">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_horizonLine"
                    d="M59.999,63.999H40.001c-1.104,0-1.999,0.896-1.999,2s0.896,1.999,1.999,1.999h19.998c1.104,0,2-0.895,2-1.999S61.104,63.999,59.999,63.999z"
                    />
                </g>
            </g>
        </g>
    </svg><!-- sunset --> ';
}
*/

function owmw_sunsetFill() {
	return '
		<svg
        version="1.1"
        id="sunsetFill"
        class="climacon climacon-sunsetFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <g class="climacon-iconWrap climacon-iconWrap-sunsetFill">
            <g class="climacon-componentWrap climacon-componentWrap-sunsetFill">
                <g class="climacon-componentWrap climacon-componentWrap-sunSpoke">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-west"
                    d="M71.997,57.999h-3.998c-1.104,0-2-0.896-2-1.999s0.896-2,2-2h3.998c1.104,0,2,0.896,2,2S73.104,57.999,71.997,57.999z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northWest"
                    d="M64.143,44.688c-0.781,0.781-2.05,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l2.828-2.828c0.778-0.781,2.047-0.781,2.828,0c0.778,0.781,0.778,2.047,0,2.828L64.143,44.688z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                    d="M50.001,40.002c-1.105,0-2-0.896-2-2v-3.999c0-1.104,0.895-2,2-2c1.104,0,2,0.896,2,2v3.999C52.001,39.106,51.104,40.002,50.001,40.002z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northEast"
                    d="M35.86,44.688l-2.828-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.781-0.781,2.047-0.781,2.828,0l2.828,2.828c0.781,0.781,0.781,2.047,0,2.828C37.907,45.469,36.641,45.469,35.86,44.688z"
                    />
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                    d="M34.002,56c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.999-0.896-1.999-1.999s0.896-2,1.999-2h4C33.107,54,34.002,54.896,34.002,56z"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                    <circle
                    class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                    cx="50.001"
                    cy="56"
                    r="11.999"
                    />
                    <circle
                    class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                    fill="#FFFFFF"
                    cx="50.001"
                    cy="56"
                    r="7.999"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-arrow">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_arrow climacon-component-stroke_arrow-down"
                    d="M50,49.107c0.552,0,1,0.448,1,1.002v3.83l2.535-2.535c0.391-0.391,1.022-0.391,1.414,0c0.391,0.391,0.391,1.023,0,1.414l-4.242,4.242c-0.392,0.391-1.022,0.391-1.414,0l-4.242-4.242c-0.391-0.391-0.391-1.023,0-1.414c0.392-0.391,1.023-0.391,1.414,0L49,53.939v-3.83C49,49.555,49.447,49.107,50,49.107z"
                    />
                </g>
                <g class="climacon-wrapperComponent climacon-wrapperComponent-horizonLine">
                    <path
                    class="climacon-component climacon-component-stroke climacon-component-stroke_horizonLine"
                    d="M59.999,63.999H40.001c-1.104,0-1.999,0.896-1.999,2s0.896,1.999,1.999,1.999h19.998c1.104,0,2-0.895,2-1.999S61.104,63.999,59.999,63.999z"
                    />
                </g>
            </g>
        </g>
    </svg><!-- sunsetFill--> ';
}

function owmw_sunriseAlt() {
	return '
		<svg
        version="1.1"
        id="sunriseAlt"
        class="climacon climacon-sunriseAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="sunriseClip">
            <rect
            x="15"
            y="15"
            width="70"
            height="45"
            />
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-sunriseAlt">
            <g clip-path="url(#sunriseClip)">
                <g class="climacon-componentWrap climacon-componentWrap-sunriseAlt">
                    <g class="climacon-componentWrap climacon-componentWrap-sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M71.997,74.002h-3.999c-1.104,0-2-0.896-2-2c0-1.105,0.896-2,2-2h3.999c1.104,0,2,0.895,2,2C73.997,73.105,73.104,74.002,71.997,74.002z"
                        />
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M64.141,60.689c-0.781,0.78-2.048,0.78-2.828,0c-0.779-0.781-0.779-2.047,0-2.828l2.828-2.828c0.78-0.78,2.047-0.78,2.828,0c0.78,0.781,0.78,2.047,0,2.828L64.141,60.689z"
                        />
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M50,56.003c-1.104,0-1.999-0.896-1.999-2v-3.999c0-1.104,0.896-2,1.999-2s2,0.896,2,2v3.999C52,55.107,51.104,56.003,50,56.003z"
                        />
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M35.86,60.689l-2.828-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.78,2.047-0.78,2.828,0l2.828,2.828c0.78,0.781,0.78,2.047,0,2.828C37.907,61.47,36.641,61.47,35.86,60.689z"
                        />
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M34.002,72.002c0,1.104-0.896,2-1.999,2h-4c-1.104,0-2-0.896-2-2c0-1.105,0.896-2,2-2h4C33.106,70.002,34.002,70.896,34.002,72.002z"
                        />
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        d="M61.302,76h-4.381c0.683-1.176,1.078-2.539,1.078-3.998c0-4.418-3.581-8-7.999-8c-4.417,0-7.999,3.582-7.999,8c0,1.459,0.396,2.822,1.079,3.998h-4.381c-0.444-1.252-0.698-2.594-0.698-3.998c0-6.627,5.373-11.999,11.999-11.999c6.627,0,11.999,5.371,11.999,11.999C61.999,73.406,61.745,74.748,61.302,76z"
                        />
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-horizonLine">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_horizonLine"
                d="M40.001,63.998h19.998c1.104,0,2,0.896,2,2c0,1.105-0.896,2-2,2H40.001c-1.104,0-2-0.895-2-2C38.001,64.895,38.897,63.998,40.001,63.998z"
                />
            </g>
        </g>
    </svg><!-- sunriseAlt --> ';
}

function owmw_sunriseAltFill() {
	return '
		<svg
        version="1.1"
        id="sunriseAltFill"
        class="climacon climacon-sunriseAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="sunriseFillClip">
            <rect x="15" y="15" width="70" height="48.999"/>
        </clipPath>

        <g class="climacon-iconWrap climacon-iconWrap-sunriseAltFill">
            <g clip-path="url(#sunriseFillClip)">
                <g class="climacon-componentWrap climacon-componentWrap-sunriseAlt">
                    <g class="climacon-componentWrap climacon-componentWrap-sunSpoke">
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M72.31,77.999h-3.998c-1.104,0-2-0.896-2-1.999s0.896-2,2-2h3.998c1.104,0,2,0.896,2,2S73.416,77.999,72.31,77.999z"
                        />
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M64.455,64.688c-0.781,0.781-2.05,0.781-2.828,0c-0.781-0.78-0.781-2.047,0-2.828l2.828-2.827c0.778-0.781,2.047-0.781,2.828,0c0.778,0.78,0.778,2.047,0,2.827L64.455,64.688z"
                        />
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M50.313,60.002c-1.104,0-2-0.896-2-2v-3.999c0-1.104,0.896-2,2-2s2,0.896,2,2v3.999C52.313,59.105,51.416,60.002,50.313,60.002z"
                        />
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M36.172,64.688l-2.828-2.828c-0.781-0.78-0.781-2.047,0-2.827c0.781-0.781,2.047-0.781,2.828,0L39,61.859c0.781,0.781,0.781,2.048,0,2.828C38.22,65.469,36.954,65.469,36.172,64.688z"
                        />
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M34.314,76c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.999-0.896-1.999-1.999s0.896-2,1.999-2h4C33.419,74,34.314,74.896,34.314,76z"
                        />
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                        <circle 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="50.313" 
                        cy="76" 
                        r="11.999"
                        />
                        <circle
                        class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                        fill="#FFFFFF" 
                        cx="50.001" 
                        cy="76" 
                        r="7.999"
                        />
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-horizonLine">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_horizonLine"
                d="M40.001,63.998h19.998c1.104,0,2,0.896,2,2c0,1.105-0.896,2-2,2H40.001c-1.104,0-2-0.895-2-2C38.001,64.895,38.897,63.998,40.001,63.998z"
                />
            </g>
        </g>
    </svg><!-- sunriseAltFill --> ';
}

function owmw_sunsetAlt() {
	return '
		<svg
        version="1.1"
        id="sunsetAlt"
        class="climacon climacon-sunsetAlt"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="sunriseClip">
            <rect
            x="15"
            y="15"
            width="70"
            height="45"
            />
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-sunsetAlt">
            <g clip-path="url(#sunriseClip)">
                <g class="climacon-componentWrap climacon-componentWrap-sunsetAlt">
                    <g class="climacon-componentWrap climacon-componentWrap-sunSpoke">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M71.997,74.002h-3.999c-1.104,0-2-0.896-2-2c0-1.105,0.896-2,2-2h3.999c1.104,0,2,0.895,2,2C73.997,73.105,73.104,74.002,71.997,74.002z"
                        />
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M64.141,60.689c-0.781,0.78-2.048,0.78-2.828,0c-0.779-0.781-0.779-2.047,0-2.828l2.828-2.828c0.78-0.78,2.047-0.78,2.828,0c0.78,0.781,0.78,2.047,0,2.828L64.141,60.689z"
                        />
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M50,56.003c-1.104,0-1.999-0.896-1.999-2v-3.999c0-1.104,0.896-2,1.999-2s2,0.896,2,2v3.999C52,55.107,51.104,56.003,50,56.003z"
                        />
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M35.86,60.689l-2.828-2.828c-0.781-0.781-0.781-2.047,0-2.828c0.78-0.78,2.047-0.78,2.828,0l2.828,2.828c0.78,0.781,0.78,2.047,0,2.828C37.907,61.47,36.641,61.47,35.86,60.689z"
                        />
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M34.002,72.002c0,1.104-0.896,2-1.999,2h-4c-1.104,0-2-0.896-2-2c0-1.105,0.896-2,2-2h4C33.106,70.002,34.002,70.896,34.002,72.002z"
                        />
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                        <path
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        d="M61.302,76h-4.381c0.683-1.176,1.078-2.539,1.078-3.998c0-4.418-3.581-8-7.999-8c-4.417,0-7.999,3.582-7.999,8c0,1.459,0.396,2.822,1.079,3.998h-4.381c-0.444-1.252-0.698-2.594-0.698-3.998c0-6.627,5.373-11.999,11.999-11.999c6.627,0,11.999,5.371,11.999,11.999C61.999,73.406,61.745,74.748,61.302,76z"
                        />
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-horizonLine">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_horizonLine"
                d="M40.001,63.998h19.998c1.104,0,2,0.896,2,2c0,1.105-0.896,2-2,2H40.001c-1.104,0-2-0.895-2-2C38.001,64.895,38.897,63.998,40.001,63.998z"
                />
            </g>
        </g>
    </svg><!-- sunsetAlt --> ';
}

function owmw_sunsetAltFill() {
	return '	
		<svg
        version="1.1"
        id="sunsetAltFill"
        class="climacon climacon-sunsetAltFill"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="15 10 80 65"
        xml:space="preserve">
        <clipPath id="sunriseFillClip">
            <rect x="15" y="15" width="70" height="48.999"/>
        </clipPath>
        <g class="climacon-iconWrap climacon-iconWrap-sunsetAltFill">
            <g clip-path="url(#sunriseFillClip)">
                <g class="climacon-componentWrap climacon-componentWrap-sunset climacon-componentWrap-sunsetAlt">
                    <g class="climacon-componentWrap climacon-componentWrap-sunSpoke">
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-east"
                        d="M72.31,77.999h-3.998c-1.104,0-2-0.896-2-1.999s0.896-2,2-2h3.998c1.104,0,2,0.896,2,2S73.416,77.999,72.31,77.999z"
                        />
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northEast"
                        d="M64.455,64.688c-0.781,0.781-2.05,0.781-2.828,0c-0.781-0.78-0.781-2.047,0-2.828l2.828-2.827c0.778-0.781,2.047-0.781,2.828,0c0.778,0.78,0.778,2.047,0,2.827L64.455,64.688z"
                        />
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-north"
                        d="M50.313,60.002c-1.104,0-2-0.896-2-2v-3.999c0-1.104,0.896-2,2-2s2,0.896,2,2v3.999C52.313,59.105,51.416,60.002,50.313,60.002z"
                        />
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-northWest"
                        d="M36.172,64.688l-2.828-2.828c-0.781-0.78-0.781-2.047,0-2.827c0.781-0.781,2.047-0.781,2.828,0L39,61.859c0.781,0.781,0.781,2.048,0,2.828C38.22,65.469,36.954,65.469,36.172,64.688z"
                        />
                        <path 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunSpoke climacon-component-stroke_sunSpoke-west"
                        d="M34.314,76c0,1.104-0.896,1.999-2,1.999h-4c-1.104,0-1.999-0.896-1.999-1.999s0.896-2,1.999-2h4C33.419,74,34.314,74.896,34.314,76z"
                    />
                    </g>
                    <g class="climacon-wrapperComponent climacon-wrapperComponent-sunBody">
                        <circle 
                        class="climacon-component climacon-component-stroke climacon-component-stroke_sunBody"
                        cx="50.313" 
                        cy="76" 
                        r="11.999"
                        />
                        <circle 
                        class="climacon-component climacon-component-fill climacon-component-fill_sunBody"
                        fill="#FFFFFF" 
                        cx="50.001" 
                        cy="76" 
                        r="7.999"
                        />
                    </g>
                </g>
            </g>
            <g class="climacon-wrapperComponent climacon-wrapperComponent-horizonLine">
                <path
                class="climacon-component climacon-component-stroke climacon-component-stroke_horizonLine"
                d="M40.001,63.998h19.998c1.104,0,2,0.896,2,2c0,1.105-0.896,2-2,2H40.001c-1.104,0-2-0.895-2-2C38.001,64.895,38.897,63.998,40.001,63.998z"
                />
            </g>
        </g>
    </svg><!-- sunsetAltFill--> ';
}
?>
