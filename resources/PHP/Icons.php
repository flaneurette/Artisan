<?php
/*
 * Inline SVG icons (self-hosted, no external requests).
 * Icons: Material Symbols by Google, Apache License 2.0
 * https://www.apache.org/licenses/LICENSE-2.0 - keep this notice when redistributing.
 *
 * Usage:  echo artisan_icon('delete');            // label defaults to "Delete"
 *         echo artisan_icon('edit', 'Edit page'); // custom accessible label
 *         echo artisan_icon('menu', '');          // decorative: hidden from screen readers
 * Style:  .icon { width/height } and colour via CSS (fill: currentColor).
 */
if (!function_exists('artisan_icon')) {
	function artisan_icon($name, $label = null, $class = 'icon') {
		static $paths = array(
		'contract' => 'M6,22q-1.25,0-2.13-0.88T3,19V16H6V2H21V19q0,1.25-0.88,2.13T18,22H6Zm12-2q0.43,0 0.71-0.29Q19,19.43 19,19V4H8V16h9v3q0,0.43 0.29,0.71T18,20ZM9,9V7h9V9H9Zm0,3V10h9v2H9ZM6,20h9V18H5v1q0,0.43 0.29,0.71T6,20Zm0,0q-0.42,0-0.71,0T5,20H15H6Z',
		'database' => 'M12,21q-3.78,0-6.39-1.16Q3,18.68 3,17V7Q3,5.35 5.64,4.17T12,3t6.36,1.17T21,7V17q0,1.68-2.61,2.84Q15.78,21 12,21ZM12,9.02q2.23,0 4.48-0.64T19,7.02Q18.73,6.3 16.49,5.65T12,5Q9.73,5 7.54,5.64T5,7.02Q5.35,7.77 7.54,8.4T12,9.02ZM12,14q1.05,0 2.03-0.1t1.86-0.29t1.67-0.46q0.79-0.27 1.44-0.63v-3q-0.65,0.35-1.44,0.63q-0.79,0.28-1.67,0.46T14.03,10.9T12,11T9.95,10.9T8.06,10.61Q7.18,10.43 6.4,10.15Q5.63,9.88 5,9.52v3q0.63,0.35 1.4,0.63q0.78,0.28 1.66,0.46T9.95,13.9T12,14Zm0,5q1.15,0 2.34-0.18t2.19-0.46t1.67-0.65q0.68-0.36 0.8-0.74V14.53q-0.65,0.35-1.44,0.63q-0.79,0.27-1.67,0.46T14.03,15.9T12,16T9.95,15.9T8.06,15.61Q7.18,15.43 6.4,15.15T5,14.53V17q0.13,0.38 0.79,0.73t1.66,0.64t2.2,0.46T12,19Z',
		'delete' => 'M7,21q-0.82,0-1.41-0.59T5,19V6H4V4H9V3h6V4h5V6H19V19q0,0.82-0.59,1.41T17,21H7ZM17,6H7V19H17V6ZM9,17h2V8H9v9Zm4,0h2V8H13v9ZM7,6V19V6Z',
		'edit' => 'M5,19H6.43L16.2,9.23L14.78,7.8L5,17.58V19ZM3,21V16.75L16.2,3.57q0.3-0.28 0.66-0.43T17.63,3q0.4,0 0.78,0.15T19.05,3.6L20.43,5q0.3,0.27 0.44,0.65T21,6.4q0,0.4-0.14,0.76T20.43,7.82L7.25,21H3ZM19,6.4L17.6,5L19,6.4ZM15.48,8.52L14.78,7.8L16.2,9.23l-0.72-0.7Z',
		'menu' => 'M3,18V16H21v2H3Zm0-5V11H21v2H3ZM3,8V6H21V8H3Z',
		'production_quantity_limits' => 'M11.29,9.21Q11,8.92 11,8.5q0-0.43 0.29-0.71T12,7.5t0.71,0.29T13,8.5q0,0.42-0.29,0.71T12,9.5T11.29,9.21ZM11,6V1h2V6H11ZM5.59,21.41Q5,20.83 5,20t0.59-1.41T7,18q0.83,0 1.41,0.59Q9,19.18 9,20T8.41,21.41Q7.83,22 7,22T5.59,21.41Zm10,0Q15,20.83 15,20t0.59-1.41T17,18t1.41,0.59Q19,19.18 19,20t-0.59,1.41T17,22t-1.41-0.59ZM1,4V2H4.28l4.25,9h7l3.9-7H21.7l-4.4,7.95q-0.27,0.5-0.74,0.78T15.55,13H8.1L7,15H19v2H7q-1.13,0-1.71-0.98T5.25,14.05L6.6,11.6L3,4H1Z',
		'published_with_changes' => 'M11,21.95q-1.9-0.2-3.54-1.04T4.61,18.74T2.7,15.68T2,12Q2,9.73 2.91,7.8T5.4,4.5H3v-2H9v6H7V5.77Q5.63,6.88 4.81,8.49T4,12q0,3.07 2.01,5.31T11,19.93v2.02ZM10.58,16.6L6.35,12.35l1.4-1.4l2.83,2.82L16.25,8.1l1.4,1.42L10.58,16.6ZM15,21.5v-6h2v2.73q1.38-1.13 2.19-2.73T20,12q0-3.08-2.01-5.31T13,4.07V2.05q3.8,0.38 6.4,3.2Q22,8.07 22,12q0,2.27-0.91,4.2T18.6,19.5H21v2H15Z',
		'shopping_basket' => 'M5.53,21q-0.67,0-1.2-0.41Q3.8,20.18 3.6,19.52L1.05,10.27Q0.93,9.8 1.21,9.4T2,9H6.75l4.4-6.55q0.13-0.2 0.35-0.32T11.98,2t0.47,0.13T12.8,2.45L17.2,9H22q0.5,0 0.79,0.4t0.16,0.88L20.4,19.52q-0.2,0.65-0.73,1.06T18.48,21H5.53ZM5.5,19h13l2.2-8H3.3l2.2,8Zm7.91-2.59Q14,15.83 14,15t-0.59-1.41T12,13t-1.41,0.59Q10,14.18 10,15t0.59,1.41T12,17t1.41-0.59ZM9.18,9H14.8L11.98,4.8L9.18,9ZM12,15Z'
		);
		if (!isset($paths[$name])) { return ''; }
		if ($label === null) { $label = ucfirst(str_replace('_', ' ', $name)); }
		$a = ($label === '')
			? 'aria-hidden="true"'
			: 'role="img" aria-label="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '"';
		return '<svg class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" ' . $a . ' focusable="false"><path d="' . $paths[$name] . '"/></svg>';
	}
}
