! function() {
	"use strict";
	var t, e = window.wp.element,
		c = window.wp.i18n,
		l = window.wp.blocks,
		a = window.wp.compose,
		o = window.wp.components,
		n = {
			from: [{
				type: "shortcode",
				tag: "owm-weather",
				attributes: {
					id: {
						type: "string",
						shortcode: t => {
							let {
								named: {
									id: e
								}
							} = t;
							return parseInt(e)
						}
					}
				}
			}],
			to: [{
				type: "block",
				blocks: ["core/shortcode"],
				transform: t => (0, l.createBlock)("core/shortcode", {
					text: `[owm_weather id="${t.id}" /]`
				})
			}]
		};
	window.owmw_be = null !== (t = window.owmw_be) && void 0 !== t ? t : {
		weather: []
	}, (0, l.registerBlockType)("owm-weather/weather-selector", {
		title: (0, c.__)("OWM Weather", "owm-weather"),
		description: (0, c.__)("Insert a OWM Weather shortcode in your page.", "owm-weather"),
		category: "widgets",
		attributes: {
			id: {
				type: "string"
			},
			title: {
				type: "string"
			}
		},
		icon: 'cloud',
		transforms: n,
		edit: function t(l) {
			let {
				attributes: r,
				setAttributes: n
			} = l;
			const i = new Map;
			if (Object.entries(window.owmw_be.weather).forEach((t => {
					let [e, c] = t;
					i.set(c.id.toString(), c)
				})), !i.size && !r.id) return (0, e.createElement)("div", {
				className: "components-placeholder"
			}, (0, e.createElement)("p", null, (0, c.__)("No weather posts were found. Create a weather first.", "owm-weather")));
			const s = Array.from(i.values(), (t => ({
				value: t.id,
				label: t.title
			})));
			if (r.id) s.length || s.push({
				value: r.id,
				label: r.title
			});
			else {
				const t = s[0];
				r = {
					id: t.value,
					title: t.label
				}
			}
			const m = `owm-weather-weather-selector-${(0,a.useInstanceId)(t)}`;
			return (0, e.createElement)("div", {
				className: "components-placeholder"
			}, (0, e.createElement)("label", {
				htmlFor: m,
				className: "components-placeholder__label"
			}, (0, c.__)("Select a weather post:", "owm-weather")), (0, e.createElement)(o.SelectControl, {
				id: m,
				options: s,
				value: r.id,
				onChange: t => n({
					id: t,
					title: i.get(t).title
				})
			}))
		},
		save: t => {
			var c, l, r, a;
			let {
				attributes: o
			} = t;
            return o = {
                id: null !== (c = o.id) && void 0 !== c ? c : null === (l = window.owmw_be.weather[0]) || void 0 === l ? void 0 : l.id,
                title: null !== (r = o.title) && void 0 !== r ? r : null === (a = window.owmw_be.weather[0]) || void 0 === a ? void 0 : a.title
                }, (0, e.createElement)("div", null, '[owm-weather id="', o.id, '" /]')
		}
	})
}();
