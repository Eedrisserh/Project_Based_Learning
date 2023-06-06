<?php
/**
 * AnWP Football Leagues :: Data.
 *
 * @since   0.2.0
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Data class.
 *
 * @property-read array $cards     Card type options
 *
 * @since 0.1.0
 */
class AnWPFL_Data {

	/**
	 * Parent plugin class.
	 *
	 * @var AnWP_Football_Leagues
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Countries data.
	 *
	 * @var    - Array of countries data
	 * @since  0.1.0
	 */
	private $countries = [];

	/**
	 * Player positions.
	 *
	 * @var    - Array of positions
	 * @since  0.1.0
	 */
	private $positions = [];

	/**
	 * Player positions.
	 *
	 * @var    - Array of positions
	 * @since  0.7.4
	 */
	private $positions_plural = [];

	/**
	 * Series Letters.
	 *
	 * @var    - Array of letters
	 * @since  0.5.5
	 */
	private $series = [];

	/**
	 * Admin strings for localization.
	 *
	 * @var    - Array of strings
	 * @since  0.5.5
	 */
	private $admin_l10n = [];

	/**
	 * Cards.
	 *
	 * @var    - Array of card types
	 * @since  0.3.0
	 */
	private $cards = [];

	/**
	 * Constructor.
	 *
	 * @since  0.1.0
	 *
	 * @param  AnWP_Football_Leagues $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;

		$this->countries = [
			'af' => esc_html_x( 'Afghanistan', 'country', 'anwp-football-leagues' ),
			'al' => esc_html_x( 'Albania', 'country', 'anwp-football-leagues' ),
			'dz' => esc_html_x( 'Algeria', 'country', 'anwp-football-leagues' ),
			'ds' => esc_html_x( 'American Samoa', 'country', 'anwp-football-leagues' ),
			'ad' => esc_html_x( 'Andorra', 'country', 'anwp-football-leagues' ),
			'ao' => esc_html_x( 'Angola', 'country', 'anwp-football-leagues' ),
			'ai' => esc_html_x( 'Anguilla', 'country', 'anwp-football-leagues' ),
			'aq' => esc_html_x( 'Antarctica', 'country', 'anwp-football-leagues' ),
			'ag' => esc_html_x( 'Antigua and Barbuda', 'country', 'anwp-football-leagues' ),
			'ar' => esc_html_x( 'Argentina', 'country', 'anwp-football-leagues' ),
			'am' => esc_html_x( 'Armenia', 'country', 'anwp-football-leagues' ),
			'aw' => esc_html_x( 'Aruba', 'country', 'anwp-football-leagues' ),
			'au' => esc_html_x( 'Australia', 'country', 'anwp-football-leagues' ),
			'at' => esc_html_x( 'Austria', 'country', 'anwp-football-leagues' ),
			'az' => esc_html_x( 'Azerbaijan', 'country', 'anwp-football-leagues' ),
			'bs' => esc_html_x( 'Bahamas', 'country', 'anwp-football-leagues' ),
			'bh' => esc_html_x( 'Bahrain', 'country', 'anwp-football-leagues' ),
			'bd' => esc_html_x( 'Bangladesh', 'country', 'anwp-football-leagues' ),
			'bb' => esc_html_x( 'Barbados', 'country', 'anwp-football-leagues' ),
			'by' => esc_html_x( 'Belarus', 'country', 'anwp-football-leagues' ),
			'be' => esc_html_x( 'Belgium', 'country', 'anwp-football-leagues' ),
			'bz' => esc_html_x( 'Belize', 'country', 'anwp-football-leagues' ),
			'bj' => esc_html_x( 'Benin', 'country', 'anwp-football-leagues' ),
			'bm' => esc_html_x( 'Bermuda', 'country', 'anwp-football-leagues' ),
			'bt' => esc_html_x( 'Bhutan', 'country', 'anwp-football-leagues' ),
			'bo' => esc_html_x( 'Bolivia', 'country', 'anwp-football-leagues' ),
			'ba' => esc_html_x( 'Bosnia and Herzegovina', 'country', 'anwp-football-leagues' ),
			'bw' => esc_html_x( 'Botswana', 'country', 'anwp-football-leagues' ),
			'bv' => esc_html_x( 'Bouvet Island', 'country', 'anwp-football-leagues' ),
			'br' => esc_html_x( 'Brazil', 'country', 'anwp-football-leagues' ),
			'io' => esc_html_x( 'British Indian Ocean Territory', 'country', 'anwp-football-leagues' ),
			'bn' => esc_html_x( 'Brunei Darussalam', 'country', 'anwp-football-leagues' ),
			'bg' => esc_html_x( 'Bulgaria', 'country', 'anwp-football-leagues' ),
			'bf' => esc_html_x( 'Burkina Faso', 'country', 'anwp-football-leagues' ),
			'bi' => esc_html_x( 'Burundi', 'country', 'anwp-football-leagues' ),
			'kh' => esc_html_x( 'Cambodia', 'country', 'anwp-football-leagues' ),
			'cm' => esc_html_x( 'Cameroon', 'country', 'anwp-football-leagues' ),
			'ca' => esc_html_x( 'Canada', 'country', 'anwp-football-leagues' ),
			'cv' => esc_html_x( 'Cape Verde', 'country', 'anwp-football-leagues' ),
			'ky' => esc_html_x( 'Cayman Islands', 'country', 'anwp-football-leagues' ),
			'cf' => esc_html_x( 'Central African Republic', 'country', 'anwp-football-leagues' ),
			'td' => esc_html_x( 'Chad', 'country', 'anwp-football-leagues' ),
			'cl' => esc_html_x( 'Chile', 'country', 'anwp-football-leagues' ),
			'cn' => esc_html_x( 'China', 'country', 'anwp-football-leagues' ),
			'cx' => esc_html_x( 'Christmas Island', 'country', 'anwp-football-leagues' ),
			'cc' => esc_html_x( 'Cocos (Keeling) Islands', 'country', 'anwp-football-leagues' ),
			'co' => esc_html_x( 'Colombia', 'country', 'anwp-football-leagues' ),
			'km' => esc_html_x( 'Comoros', 'country', 'anwp-football-leagues' ),
			'cg' => esc_html_x( 'Republic of the Congo', 'country', 'anwp-football-leagues' ),
			'cd' => esc_html_x( 'Democratic Republic of the Congo', 'country', 'anwp-football-leagues' ),
			'ck' => esc_html_x( 'Cook Islands', 'country', 'anwp-football-leagues' ),
			'cr' => esc_html_x( 'Costa Rica', 'country', 'anwp-football-leagues' ),
			'hr' => esc_html_x( 'Croatia (Hrvatska)', 'country', 'anwp-football-leagues' ),
			'cu' => esc_html_x( 'Cuba', 'country', 'anwp-football-leagues' ),
			'cw' => esc_html_x( 'Curaçao', 'country', 'anwp-football-leagues' ),
			'cy' => esc_html_x( 'Cyprus', 'country', 'anwp-football-leagues' ),
			'cz' => esc_html_x( 'Czech Republic', 'country', 'anwp-football-leagues' ),
			'dk' => esc_html_x( 'Denmark', 'country', 'anwp-football-leagues' ),
			'dj' => esc_html_x( 'Djibouti', 'country', 'anwp-football-leagues' ),
			'dm' => esc_html_x( 'Dominica', 'country', 'anwp-football-leagues' ),
			'do' => esc_html_x( 'Dominican Republic', 'country', 'anwp-football-leagues' ),
			'tp' => esc_html_x( 'East Timor', 'country', 'anwp-football-leagues' ),
			'tl' => esc_html_x( 'Timor-Leste', 'country', 'anwp-football-leagues' ),
			'ec' => esc_html_x( 'Ecuador', 'country', 'anwp-football-leagues' ),
			'eg' => esc_html_x( 'Egypt', 'country', 'anwp-football-leagues' ),
			'sv' => esc_html_x( 'El Salvador', 'country', 'anwp-football-leagues' ),
			'gq' => esc_html_x( 'Equatorial Guinea', 'country', 'anwp-football-leagues' ),
			'er' => esc_html_x( 'Eritrea', 'country', 'anwp-football-leagues' ),
			'ee' => esc_html_x( 'Estonia', 'country', 'anwp-football-leagues' ),
			'et' => esc_html_x( 'Ethiopia', 'country', 'anwp-football-leagues' ),
			'fk' => esc_html_x( 'Falkland Islands (Malvinas)', 'country', 'anwp-football-leagues' ),
			'fo' => esc_html_x( 'Faroe Islands', 'country', 'anwp-football-leagues' ),
			'fj' => esc_html_x( 'Fiji', 'country', 'anwp-football-leagues' ),
			'fi' => esc_html_x( 'Finland', 'country', 'anwp-football-leagues' ),
			'fr' => esc_html_x( 'France', 'country', 'anwp-football-leagues' ),
			'fx' => esc_html_x( 'France, Metropolitan', 'country', 'anwp-football-leagues' ),
			'gf' => esc_html_x( 'French Guiana', 'country', 'anwp-football-leagues' ),
			'pf' => esc_html_x( 'French Polynesia', 'country', 'anwp-football-leagues' ),
			'tf' => esc_html_x( 'French Southern Territories', 'country', 'anwp-football-leagues' ),
			'ga' => esc_html_x( 'Gabon', 'country', 'anwp-football-leagues' ),
			'gm' => esc_html_x( 'Gambia', 'country', 'anwp-football-leagues' ),
			'ge' => esc_html_x( 'Georgia', 'country', 'anwp-football-leagues' ),
			'de' => esc_html_x( 'Germany', 'country', 'anwp-football-leagues' ),
			'gh' => esc_html_x( 'Ghana', 'country', 'anwp-football-leagues' ),
			'gi' => esc_html_x( 'Gibraltar', 'country', 'anwp-football-leagues' ),
			'gk' => esc_html_x( 'Guernsey', 'country', 'anwp-football-leagues' ),
			'gr' => esc_html_x( 'Greece', 'country', 'anwp-football-leagues' ),
			'gl' => esc_html_x( 'Greenland', 'country', 'anwp-football-leagues' ),
			'gd' => esc_html_x( 'Grenada', 'country', 'anwp-football-leagues' ),
			'gp' => esc_html_x( 'Guadeloupe', 'country', 'anwp-football-leagues' ),
			'gu' => esc_html_x( 'Guam', 'country', 'anwp-football-leagues' ),
			'gt' => esc_html_x( 'Guatemala', 'country', 'anwp-football-leagues' ),
			'gn' => esc_html_x( 'Guinea', 'country', 'anwp-football-leagues' ),
			'gw' => esc_html_x( 'Guinea-Bissau', 'country', 'anwp-football-leagues' ),
			'gy' => esc_html_x( 'Guyana', 'country', 'anwp-football-leagues' ),
			'ht' => esc_html_x( 'Haiti', 'country', 'anwp-football-leagues' ),
			'hm' => esc_html_x( 'Heard and Mc Donald Islands', 'country', 'anwp-football-leagues' ),
			'hn' => esc_html_x( 'Honduras', 'country', 'anwp-football-leagues' ),
			'hk' => esc_html_x( 'Hong Kong', 'country', 'anwp-football-leagues' ),
			'hu' => esc_html_x( 'Hungary', 'country', 'anwp-football-leagues' ),
			'is' => esc_html_x( 'Iceland', 'country', 'anwp-football-leagues' ),
			'in' => esc_html_x( 'India', 'country', 'anwp-football-leagues' ),
			'im' => esc_html_x( 'Isle of Man', 'country', 'anwp-football-leagues' ),
			'id' => esc_html_x( 'Indonesia', 'country', 'anwp-football-leagues' ),
			'ir' => esc_html_x( 'Iran (Islamic Republic of)', 'country', 'anwp-football-leagues' ),
			'iq' => esc_html_x( 'Iraq', 'country', 'anwp-football-leagues' ),
			'ie' => esc_html_x( 'Ireland', 'country', 'anwp-football-leagues' ),
			'il' => esc_html_x( 'Israel', 'country', 'anwp-football-leagues' ),
			'it' => esc_html_x( 'Italy', 'country', 'anwp-football-leagues' ),
			'ci' => esc_html_x( 'Ivory Coast', 'country', 'anwp-football-leagues' ),
			'je' => esc_html_x( 'Jersey', 'country', 'anwp-football-leagues' ),
			'jm' => esc_html_x( 'Jamaica', 'country', 'anwp-football-leagues' ),
			'jp' => esc_html_x( 'Japan', 'country', 'anwp-football-leagues' ),
			'jo' => esc_html_x( 'Jordan', 'country', 'anwp-football-leagues' ),
			'kz' => esc_html_x( 'Kazakhstan', 'country', 'anwp-football-leagues' ),
			'ke' => esc_html_x( 'Kenya', 'country', 'anwp-football-leagues' ),
			'ki' => esc_html_x( 'Kiribati', 'country', 'anwp-football-leagues' ),
			'kp' => esc_html_x( 'Korea, Democratic People\'s Republic of', 'country', 'anwp-football-leagues' ),
			'kr' => esc_html_x( 'Korea, Republic of', 'country', 'anwp-football-leagues' ),
			'xk' => esc_html_x( 'Kosovo', 'country', 'anwp-football-leagues' ),
			'kw' => esc_html_x( 'Kuwait', 'country', 'anwp-football-leagues' ),
			'kg' => esc_html_x( 'Kyrgyzstan', 'country', 'anwp-football-leagues' ),
			'la' => esc_html_x( 'Lao People\'s Democratic Republic', 'country', 'anwp-football-leagues' ),
			'lv' => esc_html_x( 'Latvia', 'country', 'anwp-football-leagues' ),
			'lb' => esc_html_x( 'Lebanon', 'country', 'anwp-football-leagues' ),
			'ls' => esc_html_x( 'Lesotho', 'country', 'anwp-football-leagues' ),
			'lr' => esc_html_x( 'Liberia', 'country', 'anwp-football-leagues' ),
			'ly' => esc_html_x( 'Libyan Arab Jamahiriya', 'country', 'anwp-football-leagues' ),
			'li' => esc_html_x( 'Liechtenstein', 'country', 'anwp-football-leagues' ),
			'lt' => esc_html_x( 'Lithuania', 'country', 'anwp-football-leagues' ),
			'lu' => esc_html_x( 'Luxembourg', 'country', 'anwp-football-leagues' ),
			'mo' => esc_html_x( 'Macau', 'country', 'anwp-football-leagues' ),
			'mk' => esc_html_x( 'Macedonia', 'country', 'anwp-football-leagues' ),
			'mg' => esc_html_x( 'Madagascar', 'country', 'anwp-football-leagues' ),
			'mw' => esc_html_x( 'Malawi', 'country', 'anwp-football-leagues' ),
			'my' => esc_html_x( 'Malaysia', 'country', 'anwp-football-leagues' ),
			'mv' => esc_html_x( 'Maldives', 'country', 'anwp-football-leagues' ),
			'ml' => esc_html_x( 'Mali', 'country', 'anwp-football-leagues' ),
			'mt' => esc_html_x( 'Malta', 'country', 'anwp-football-leagues' ),
			'mh' => esc_html_x( 'Marshall Islands', 'country', 'anwp-football-leagues' ),
			'mq' => esc_html_x( 'Martinique', 'country', 'anwp-football-leagues' ),
			'mr' => esc_html_x( 'Mauritania', 'country', 'anwp-football-leagues' ),
			'mu' => esc_html_x( 'Mauritius', 'country', 'anwp-football-leagues' ),
			'ty' => esc_html_x( 'Mayotte', 'country', 'anwp-football-leagues' ),
			'mx' => esc_html_x( 'Mexico', 'country', 'anwp-football-leagues' ),
			'fm' => esc_html_x( 'Micronesia, Federated States of', 'country', 'anwp-football-leagues' ),
			'md' => esc_html_x( 'Moldova, Republic of', 'country', 'anwp-football-leagues' ),
			'mc' => esc_html_x( 'Monaco', 'country', 'anwp-football-leagues' ),
			'mn' => esc_html_x( 'Mongolia', 'country', 'anwp-football-leagues' ),
			'me' => esc_html_x( 'Montenegro', 'country', 'anwp-football-leagues' ),
			'ms' => esc_html_x( 'Montserrat', 'country', 'anwp-football-leagues' ),
			'ma' => esc_html_x( 'Morocco', 'country', 'anwp-football-leagues' ),
			'mz' => esc_html_x( 'Mozambique', 'country', 'anwp-football-leagues' ),
			'mm' => esc_html_x( 'Myanmar', 'country', 'anwp-football-leagues' ),
			'na' => esc_html_x( 'Namibia', 'country', 'anwp-football-leagues' ),
			'nr' => esc_html_x( 'Nauru', 'country', 'anwp-football-leagues' ),
			'np' => esc_html_x( 'Nepal', 'country', 'anwp-football-leagues' ),
			'nl' => esc_html_x( 'Netherlands', 'country', 'anwp-football-leagues' ),
			'an' => esc_html_x( 'Netherlands Antilles', 'country', 'anwp-football-leagues' ),
			'nc' => esc_html_x( 'New Caledonia', 'country', 'anwp-football-leagues' ),
			'nz' => esc_html_x( 'New Zealand', 'country', 'anwp-football-leagues' ),
			'ni' => esc_html_x( 'Nicaragua', 'country', 'anwp-football-leagues' ),
			'ne' => esc_html_x( 'Niger', 'country', 'anwp-football-leagues' ),
			'ng' => esc_html_x( 'Nigeria', 'country', 'anwp-football-leagues' ),
			'nu' => esc_html_x( 'Niue', 'country', 'anwp-football-leagues' ),
			'nf' => esc_html_x( 'Norfolk Island', 'country', 'anwp-football-leagues' ),
			'mp' => esc_html_x( 'Northern Mariana Islands', 'country', 'anwp-football-leagues' ),
			'no' => esc_html_x( 'Norway', 'country', 'anwp-football-leagues' ),
			'om' => esc_html_x( 'Oman', 'country', 'anwp-football-leagues' ),
			'pk' => esc_html_x( 'Pakistan', 'country', 'anwp-football-leagues' ),
			'pw' => esc_html_x( 'Palau', 'country', 'anwp-football-leagues' ),
			'ps' => esc_html_x( 'Palestine', 'country', 'anwp-football-leagues' ),
			'pa' => esc_html_x( 'Panama', 'country', 'anwp-football-leagues' ),
			'pg' => esc_html_x( 'Papua New Guinea', 'country', 'anwp-football-leagues' ),
			'py' => esc_html_x( 'Paraguay', 'country', 'anwp-football-leagues' ),
			'pe' => esc_html_x( 'Peru', 'country', 'anwp-football-leagues' ),
			'ph' => esc_html_x( 'Philippines', 'country', 'anwp-football-leagues' ),
			'pn' => esc_html_x( 'Pitcairn', 'country', 'anwp-football-leagues' ),
			'pl' => esc_html_x( 'Poland', 'country', 'anwp-football-leagues' ),
			'pt' => esc_html_x( 'Portugal', 'country', 'anwp-football-leagues' ),
			'pr' => esc_html_x( 'Puerto Rico', 'country', 'anwp-football-leagues' ),
			'qa' => esc_html_x( 'Qatar', 'country', 'anwp-football-leagues' ),
			're' => esc_html_x( 'Reunion', 'country', 'anwp-football-leagues' ),
			'ro' => esc_html_x( 'Romania', 'country', 'anwp-football-leagues' ),
			'ru' => esc_html_x( 'Russian Federation', 'country', 'anwp-football-leagues' ),
			'rw' => esc_html_x( 'Rwanda', 'country', 'anwp-football-leagues' ),
			'kn' => esc_html_x( 'Saint Kitts and Nevis', 'country', 'anwp-football-leagues' ),
			'lc' => esc_html_x( 'Saint Lucia', 'country', 'anwp-football-leagues' ),
			'vc' => esc_html_x( 'Saint Vincent and the Grenadines', 'country', 'anwp-football-leagues' ),
			'ws' => esc_html_x( 'Samoa', 'country', 'anwp-football-leagues' ),
			'sm' => esc_html_x( 'San Marino', 'country', 'anwp-football-leagues' ),
			'st' => esc_html_x( 'Sao Tome and Principe', 'country', 'anwp-football-leagues' ),
			'sa' => esc_html_x( 'Saudi Arabia', 'country', 'anwp-football-leagues' ),
			'sn' => esc_html_x( 'Senegal', 'country', 'anwp-football-leagues' ),
			'rs' => esc_html_x( 'Serbia', 'country', 'anwp-football-leagues' ),
			'sc' => esc_html_x( 'Seychelles', 'country', 'anwp-football-leagues' ),
			'sl' => esc_html_x( 'Sierra Leone', 'country', 'anwp-football-leagues' ),
			'sg' => esc_html_x( 'Singapore', 'country', 'anwp-football-leagues' ),
			'sk' => esc_html_x( 'Slovakia', 'country', 'anwp-football-leagues' ),
			'si' => esc_html_x( 'Slovenia', 'country', 'anwp-football-leagues' ),
			'sb' => esc_html_x( 'Solomon Islands', 'country', 'anwp-football-leagues' ),
			'so' => esc_html_x( 'Somalia', 'country', 'anwp-football-leagues' ),
			'za' => esc_html_x( 'South Africa', 'country', 'anwp-football-leagues' ),
			'ss' => esc_html_x( 'South Sudan', 'country', 'anwp-football-leagues' ),
			'gs' => esc_html_x( 'South Georgia South Sandwich Islands', 'country', 'anwp-football-leagues' ),
			'es' => esc_html_x( 'Spain', 'country', 'anwp-football-leagues' ),
			'lk' => esc_html_x( 'Sri Lanka', 'country', 'anwp-football-leagues' ),
			'sh' => esc_html_x( 'St. Helena', 'country', 'anwp-football-leagues' ),
			'pm' => esc_html_x( 'St. Pierre and Miquelon', 'country', 'anwp-football-leagues' ),
			'sd' => esc_html_x( 'Sudan', 'country', 'anwp-football-leagues' ),
			'sr' => esc_html_x( 'Suriname', 'country', 'anwp-football-leagues' ),
			'sj' => esc_html_x( 'Svalbard and Jan Mayen Islands', 'country', 'anwp-football-leagues' ),
			'sz' => esc_html_x( 'Swaziland', 'country', 'anwp-football-leagues' ),
			'se' => esc_html_x( 'Sweden', 'country', 'anwp-football-leagues' ),
			'ch' => esc_html_x( 'Switzerland', 'country', 'anwp-football-leagues' ),
			'sy' => esc_html_x( 'Syrian Arab Republic', 'country', 'anwp-football-leagues' ),
			'tw' => esc_html_x( 'Taiwan', 'country', 'anwp-football-leagues' ),
			'tj' => esc_html_x( 'Tajikistan', 'country', 'anwp-football-leagues' ),
			'tz' => esc_html_x( 'Tanzania, United Republic of', 'country', 'anwp-football-leagues' ),
			'th' => esc_html_x( 'Thailand', 'country', 'anwp-football-leagues' ),
			'tg' => esc_html_x( 'Togo', 'country', 'anwp-football-leagues' ),
			'tk' => esc_html_x( 'Tokelau', 'country', 'anwp-football-leagues' ),
			'to' => esc_html_x( 'Tonga', 'country', 'anwp-football-leagues' ),
			'tt' => esc_html_x( 'Trinidad and Tobago', 'country', 'anwp-football-leagues' ),
			'tn' => esc_html_x( 'Tunisia', 'country', 'anwp-football-leagues' ),
			'tr' => esc_html_x( 'Turkey', 'country', 'anwp-football-leagues' ),
			'tm' => esc_html_x( 'Turkmenistan', 'country', 'anwp-football-leagues' ),
			'tc' => esc_html_x( 'Turks and Caicos Islands', 'country', 'anwp-football-leagues' ),
			'tv' => esc_html_x( 'Tuvalu', 'country', 'anwp-football-leagues' ),
			'ug' => esc_html_x( 'Uganda', 'country', 'anwp-football-leagues' ),
			'ua' => esc_html_x( 'Ukraine', 'country', 'anwp-football-leagues' ),
			'ae' => esc_html_x( 'United Arab Emirates', 'country', 'anwp-football-leagues' ),
			'gb' => esc_html_x( 'United Kingdom', 'country', 'anwp-football-leagues' ),
			'us' => esc_html_x( 'United States', 'country', 'anwp-football-leagues' ),
			'um' => esc_html_x( 'United States minor outlying islands', 'country', 'anwp-football-leagues' ),
			'uy' => esc_html_x( 'Uruguay', 'country', 'anwp-football-leagues' ),
			'uz' => esc_html_x( 'Uzbekistan', 'country', 'anwp-football-leagues' ),
			'vu' => esc_html_x( 'Vanuatu', 'country', 'anwp-football-leagues' ),
			'va' => esc_html_x( 'Vatican City State', 'country', 'anwp-football-leagues' ),
			've' => esc_html_x( 'Venezuela', 'country', 'anwp-football-leagues' ),
			'vn' => esc_html_x( 'Vietnam', 'country', 'anwp-football-leagues' ),
			'vg' => esc_html_x( 'Virgin Islands (British)', 'country', 'anwp-football-leagues' ),
			'vi' => esc_html_x( 'Virgin Islands (U.S.)', 'country', 'anwp-football-leagues' ),
			'wf' => esc_html_x( 'Wallis and Futuna Islands', 'country', 'anwp-football-leagues' ),
			'eh' => esc_html_x( 'Western Sahara', 'country', 'anwp-football-leagues' ),
			'ye' => esc_html_x( 'Yemen', 'country', 'anwp-football-leagues' ),
			'zr' => esc_html_x( 'Zaire', 'country', 'anwp-football-leagues' ),
			'zm' => esc_html_x( 'Zambia', 'country', 'anwp-football-leagues' ),
			'zw' => esc_html_x( 'Zimbabwe', 'country', 'anwp-football-leagues' ),
		];

		// Non standard Nations (FIFA)
		$this->countries = array_merge(
			$this->countries,
			[
				'_England'          => esc_html_x( 'England', 'country', 'anwp-football-leagues' ),
				'_Northern_Ireland' => esc_html_x( 'Northern_Ireland', 'country', 'anwp-football-leagues' ),
				'_Scotland'         => esc_html_x( 'Scotland', 'country', 'anwp-football-leagues' ),
				'_Wales'            => esc_html_x( 'Wales', 'country', 'anwp-football-leagues' ),
			]
		);

		/**
		 * Filter available countries.
		 *
		 * @param array  List of countries.
		 *
		 * @since 0.5.5
		 */
		$this->countries = apply_filters( 'anwpfl/config/countries', $this->countries );

		$this->positions = [
			'g' => esc_html_x( 'Goalkeeper', 'position', 'anwp-football-leagues' ),
			'd' => esc_html_x( 'Defender', 'position', 'anwp-football-leagues' ),
			'm' => esc_html_x( 'Midfielder', 'position', 'anwp-football-leagues' ),
			'f' => esc_html_x( 'Forward', 'position', 'anwp-football-leagues' ),
		];

		$this->positions_plural = [
			'g' => __( 'Goalkeepers', 'anwp-football-leagues' ),
			'd' => __( 'Defenders', 'anwp-football-leagues' ),
			'm' => __( 'Midfielders', 'anwp-football-leagues' ),
			'f' => __( 'Forwards', 'anwp-football-leagues' ),
		];

		$this->cards = [
			'y'  => AnWPFL_Text::get_value( 'data__cards__yellow_card', esc_html__( 'Yellow Card', 'anwp-football-leagues' ) ),
			'r'  => AnWPFL_Text::get_value( 'data__cards__red_card', esc_html__( 'Red Card', 'anwp-football-leagues' ) ),
			'yr' => AnWPFL_Text::get_value( 'data__cards__red_yellow_card', esc_html__( '2nd Yellow > Red Card', 'anwp-football-leagues' ) ),
		];

		$this->series = [
			'w' => esc_html_x( 'w', 'Win - in club form', 'anwp-football-leagues' ),
			'd' => esc_html_x( 'd', 'Draw - in club form', 'anwp-football-leagues' ),
			'l' => esc_html_x( 'l', 'Lost - in club form', 'anwp-football-leagues' ),
		];

		// Load translatable series options
		if ( AnWPFL_Options::get_value( 'text_outcome_letter_w' ) ) {
			$this->series['w'] = AnWPFL_Options::get_value( 'text_outcome_letter_w' );
		}

		if ( AnWPFL_Options::get_value( 'text_outcome_letter_d' ) ) {
			$this->series['d'] = AnWPFL_Options::get_value( 'text_outcome_letter_d' );
		}

		if ( AnWPFL_Options::get_value( 'text_outcome_letter_l' ) ) {
			$this->series['l'] = AnWPFL_Options::get_value( 'text_outcome_letter_l' );
		}

		$this->admin_l10n = [
			'add_clubs_only_competition'             => esc_html__( 'You can add or remove clubs only on Competition page', 'anwp-football-leagues' ),
			'add_event'                              => esc_html__( 'add event', 'anwp-football-leagues' ),
			'add_sideline'                           => esc_html__( 'Add Missing Player', 'anwp-football-leagues' ),
			'add_initial_points'                     => esc_html__( 'Add Initial Points', 'anwp-football-leagues' ),
			'add_match_event'                        => esc_html__( 'Add Match Event', 'anwp-football-leagues' ),
			'add_new_group'                          => esc_html__( 'Add new group (tie)', 'anwp-football-leagues' ),
			'add_new_round'                          => esc_html__( 'Add New Round', 'anwp-football-leagues' ),
			'add_player'                             => esc_html__( '- add player -', 'anwp-football-leagues' ),
			'add_remove_color'                       => esc_html__( 'add/remove color', 'anwp-football-leagues' ),
			'add_remove_points'                      => esc_html__( 'add/remove points', 'anwp-football-leagues' ),
			'add_table_color'                        => esc_html__( 'Add Table Color', 'anwp-football-leagues' ),
			'additional_minute'                      => esc_html__( 'Additional Minute', 'anwp-football-leagues' ),
			'aggtext'                                => esc_html__( 'Aggregate Text', 'anwp-football-leagues' ),
			'aggtext_hint'                           => esc_html__( 'For Example: "Agg: 2-2; Team A won on penalties (5-3)"', 'anwp-football-leagues' ),
			'are_you_sure'                           => esc_html__( 'Are you sure?', 'anwp-football-leagues' ),
			'assistant'                              => esc_html__( 'Assistant', 'anwp-football-leagues' ),
			'attach_clubs_current_table'             => esc_html__( 'Please, attach some clubs to the current Standing Table', 'anwp-football-leagues' ),
			'attach_person_to_staff'                 => esc_html__( 'Attach Person to Staff', 'anwp-football-leagues' ),
			'attach_player_to_squad'                 => esc_html__( 'Attach Player to Squad', 'anwp-football-leagues' ),
			'attach_to_squad'                        => esc_html__( 'Attach to squad', 'anwp-football-leagues' ),
			'attendance'                             => esc_html__( 'Attendance', 'anwp-football-leagues' ),
			'automatic_pos_calculation'              => esc_html__( 'Automatic Position Calculation', 'anwp-football-leagues' ),
			'available_rules'                        => esc_html__( 'Available rules', 'anwp-football-leagues' ),
			'available_placeholders'                 => esc_html__( 'Available placeholders', 'anwp-football-leagues' ),
			'away_club'                              => esc_html__( 'Away Club', 'anwp-football-leagues' ),
			'ball_possession'                        => esc_html__( 'Ball Possession', 'anwp-football-leagues' ),
			'basic_info'                             => esc_html__( 'Basic Info', 'anwp-football-leagues' ),
			'card'                                   => esc_html__( 'Card', 'anwp-football-leagues' ),
			'card_type'                              => esc_html__( 'Card Type', 'anwp-football-leagues' ),
			'clear'                                  => esc_html__( 'Clear', 'anwp-football-leagues' ),
			'close'                                  => esc_html__( 'Close', 'anwp-football-leagues' ),
			'club'                                   => esc_html__( 'Club', 'anwp-football-leagues' ),
			'club_initial_points'                    => esc_html__( 'Club initial points', 'anwp-football-leagues' ),
			'club_staff'                             => esc_html__( 'Club Staff', 'anwp-football-leagues' ),
			'club_squad'                             => esc_html__( 'Club Squad', 'anwp-football-leagues' ),
			'clubs'                                  => esc_html__( 'Clubs', 'anwp-football-leagues' ),
			'coach'                                  => esc_html__( 'Coach', 'anwp-football-leagues' ),
			'color'                                  => esc_html__( 'Color', 'anwp-football-leagues' ),
			'color_by'                               => esc_html__( 'Color by', 'anwp-football-leagues' ),
			'comment'                                => esc_html__( 'Comment', 'anwp-football-leagues' ),
			'competition'                            => esc_html__( 'Competition', 'anwp-football-leagues' ),
			'competition_order'                      => esc_html__( 'Competition Order', 'anwp-football-leagues' ),
			'competition_order_hint'                 => esc_html__( 'Used for Knockout matches. For example: order is used on the Player page in the list of latest matches grouped by competition', 'anwp-football-leagues' ),
			'competition_status'                     => esc_html__( 'Competition Status', 'anwp-football-leagues' ),
			'competition_type'                       => esc_html__( 'Competition Type', 'anwp-football-leagues' ),
			'confirm_delete'                         => esc_html__( 'Confirm Delete', 'anwp-football-leagues' ),
			'corner_kicks'                           => esc_html__( 'Corner Kicks', 'anwp-football-leagues' ),
			'create_competition_first'               => esc_html__( 'Please, create a Competition first.', 'anwp-football-leagues' ),
			'create_different_types_of_competitions' => esc_html__( 'Create different types of Competitions', 'anwp-football-leagues' ),
			'current_ranking_rules'                  => esc_html__( 'Current standing ranking rules', 'anwp-football-leagues' ),
			'custom'                                 => esc_html_x( 'Custom', 'round robin format', 'anwp-football-leagues' ),
			'custom_player_number'                   => esc_html__( 'Edit Player number', 'anwp-football-leagues' ),
			'delete_round'                           => esc_html__( 'Delete Round', 'anwp-football-leagues' ),
			'delete_event'                           => esc_html__( 'Delete Event', 'anwp-football-leagues' ),
			'delete_sideline'                        => esc_html__( 'Delete Missing Player', 'anwp-football-leagues' ),
			'disable'                                => esc_html__( 'Disable', 'anwp-football-leagues' ),
			'double'                                 => esc_html_x( 'Double', 'round robin format', 'anwp-football-leagues' ),
			'drawn'                                  => esc_html__( 'Drawn', 'anwp-football-leagues' ),
			'edit_group_title'                       => esc_html__( 'Edit Group Title', 'anwp-football-leagues' ),
			'edit_player_number'                     => esc_html__( 'Edit Number', 'anwp-football-leagues' ),
			'edit_squad'                             => esc_html__( 'Edit Squad', 'anwp-football-leagues' ),
			'enable'                                 => esc_html__( 'Enable', 'anwp-football-leagues' ),
			'events_only_for_finished'               => esc_html__( 'You can add events only for finished matches.', 'anwp-football-leagues' ),
			'extra_time'                             => esc_html__( 'Extra Time', 'anwp-football-leagues' ),
			'filter_players_by'                      => esc_html__( 'Filter players by', 'anwp-football-leagues' ),
			'filter_competitions_by_active_season'   => esc_html__( 'filter competitions by active season', 'anwp-football-leagues' ),
			'final_score'                            => esc_html__( 'Final Score', 'anwp-football-leagues' ),
			'fixture'                                => esc_html__( 'Fixture', 'anwp-football-leagues' ),
			'fouls'                                  => esc_html__( 'Fouls', 'anwp-football-leagues' ),
			'friendly'                               => esc_html__( 'Friendly', 'anwp-football-leagues' ),
			'from_penalty'                           => esc_html__( 'From Penalty', 'anwp-football-leagues' ),
			'from_previous_round'                    => esc_html__( 'from previous round', 'anwp-football-leagues' ),
			'full_time'                              => esc_html__( 'Full Time', 'anwp-football-leagues' ),
			'ga'                                     => esc_html_x( 'GA', 'table - goals against', 'anwp-football-leagues' ),
			'gd'                                     => esc_html_x( 'GD', 'table - goals diff', 'anwp-football-leagues' ),
			'general'                                => esc_html__( 'General', 'anwp-football-leagues' ),
			'gf'                                     => esc_html_x( 'GF', 'table - goals for', 'anwp-football-leagues' ),
			'goal'                                   => esc_html__( 'Goal', 'anwp-football-leagues' ),
			'goal_from_penalty'                      => esc_html__( 'Goal From Penalty', 'anwp-football-leagues' ),
			'goals'                                  => esc_html__( 'GOALS', 'anwp-football-leagues' ),
			'goals_difference'                       => esc_html__( 'Goals Difference', 'anwp-football-leagues' ),
			'goals_scored'                           => esc_html__( 'Goals Scored', 'anwp-football-leagues' ),
			'group'                                  => esc_html__( 'Group', 'anwp-football-leagues' ),
			'groups'                                 => esc_html__( 'Groups', 'anwp-football-leagues' ),
			'half_time'                              => esc_html__( 'Half Time', 'anwp-football-leagues' ),
			'home_club'                              => esc_html__( 'Home Club', 'anwp-football-leagues' ),
			'in'                                     => esc_html_x( 'IN', 'Substitute', 'anwp-football-leagues' ),
			'injured'                                => esc_html__( 'Injured', 'anwp-football-leagues' ),
			'in_club'                                => esc_html__( 'in club', 'anwp-football-leagues' ),
			'inherit_from_settings'                  => esc_html__( 'inherit (from settings)', 'anwp-football-leagues' ),
			'initial_standing_table_data'            => esc_html__( 'Initial Standing Table Data', 'anwp-football-leagues' ),
			'initial_points'                         => esc_html__( 'Initial points', 'anwp-football-leagues' ),
			'ignore_group_structure'                 => esc_html__( 'ignore group structure', 'anwp-football-leagues' ),
			'job'                                    => esc_html__( 'Job', 'anwp-football-leagues' ),
			'knockout'                               => esc_html__( 'Knockout', 'anwp-football-leagues' ),
			'knockout_format'                        => esc_html__( 'Knockout Format', 'anwp-football-leagues' ),
			'league'                                 => esc_html__( 'League', 'anwp-football-leagues' ),
			'left_club'                              => esc_html__( 'left club', 'anwp-football-leagues' ),
			'line_ups'                               => esc_html__( 'Line Ups', 'anwp-football-leagues' ),
			'lost'                                   => esc_html__( 'Lost', 'anwp-football-leagues' ),
			'main_stage_id'                          => esc_html__( 'Main Stage ID', 'anwp-football-leagues' ),
			'match'                                  => esc_html__( 'match', 'anwp-football-leagues' ),
			'match_data'                             => esc_html__( 'Match Data', 'anwp-football-leagues' ),
			'match_events'                           => esc_html__( 'Match Events', 'anwp-football-leagues' ),
			'match_stats'                            => esc_html__( 'Match Stats', 'anwp-football-leagues' ),
			'match_sidelines'                        => esc_html__( 'Missing Players', 'anwp-football-leagues' ),
			'match_setup'                            => esc_html__( 'Match Setup', 'anwp-football-leagues' ),
			'match_week'                             => esc_html__( 'MatchWeek', 'anwp-football-leagues' ),
			'match_postponed'                        => esc_html__( 'Match Postponed', 'anwp-football-leagues' ),
			'match_time_to_be_defined'               => esc_html__( 'Time To Be Defined', 'anwp-football-leagues' ),
			'minute'                                 => esc_html__( 'Minute', 'anwp-football-leagues' ),
			'missed_penalty'                         => esc_html_x( 'Missed Penalty', 'Substitute', 'anwp-football-leagues' ),
			'multistage'                             => esc_html__( 'Multistage', 'anwp-football-leagues' ),
			'multistage_main'                        => esc_html__( 'Multistage Main', 'anwp-football-leagues' ),
			'multistage_secondary'                   => esc_html__( 'Multistage Secondary', 'anwp-football-leagues' ),
			'multistage_setup'                       => esc_html__( 'Multistage Setup', 'anwp-football-leagues' ),
			'multistage_setup_hint'                  => esc_html__( 'How To Setup Competition With Multiple Stages', 'anwp-football-leagues' ),
			'no'                                     => esc_html__( 'No', 'anwp-football-leagues' ),
			'none'                                   => esc_html__( 'None', 'anwp-football-leagues' ),
			'not_specified'                          => esc_html__( 'not specified', 'anwp-football-leagues' ),
			'number'                                 => esc_html__( 'Number', 'anwp-football-leagues' ),
			'no_players_found'                       => esc_html__( 'No players found.', 'anwp-football-leagues' ),
			'notes_below_table'                      => esc_html__( 'Notes (below table)', 'anwp-football-leagues' ),
			'official'                               => esc_html__( 'Official', 'anwp-football-leagues' ),
			'offsides'                               => esc_html__( 'Offsides', 'anwp-football-leagues' ),
			'other'                                  => esc_html__( 'other', 'anwp-football-leagues' ),
			'on_loan'                                => esc_html__( 'on loan', 'anwp-football-leagues' ),
			'on_trial'                               => esc_html__( 'on trial', 'anwp-football-leagues' ),
			'out'                                    => esc_html_x( 'OUT', 'Substitute', 'anwp-football-leagues' ),
			'own_goal'                               => esc_html__( 'Own Goal', 'anwp-football-leagues' ),
			'penalty'                                => esc_html__( 'Penalty', 'anwp-football-leagues' ),
			'penalty_shootout'                       => esc_html__( 'Penalty Shootout', 'anwp-football-leagues' ),
			'place'                                  => esc_html__( 'Place', 'anwp-football-leagues' ),
			'place_and_time'                         => esc_html__( 'Place and Time', 'anwp-football-leagues' ),
			'played'                                 => esc_html__( 'Played', 'anwp-football-leagues' ),
			'player'                                 => esc_html__( 'Player', 'anwp-football-leagues' ),
			'player_in'                              => esc_html__( 'Player In', 'anwp-football-leagues' ),
			'player_name'                            => esc_html__( 'Player Name', 'anwp-football-leagues' ),
			'player_not_in_squad'                    => esc_html__( '! player not in squad', 'anwp-football-leagues' ),
			'player_out'                             => esc_html__( 'Player Out', 'anwp-football-leagues' ),
			'players'                                => esc_html__( 'Players', 'anwp-football-leagues' ),
			'position'                               => esc_html__( 'Position', 'anwp-football-leagues' ),
			'points'                                 => esc_html__( 'Points', 'anwp-football-leagues' ),
			'points_for_a_draw'                      => esc_html__( 'Points for a draw', 'anwp-football-leagues' ),
			'points_for_a_loss'                      => esc_html__( 'Points for a loss', 'anwp-football-leagues' ),
			'points_for_a_win'                       => esc_html__( 'Points for a win', 'anwp-football-leagues' ),
			'quickly_create_players'                 => esc_html__( 'Quickly create players with "Import Data" Tool', 'anwp-football-leagues' ),
			'ranking_rules'                          => esc_html__( 'Ranking Rules', 'anwp-football-leagues' ),
			'ranking_rules_notes_1'                  => esc_html__( 'Ranking rules are used to determine the position of the team in the Standing Table (from top to bottom).', 'anwp-football-leagues' ),
			'ranking_rules_notes_2'                  => esc_html__( 'Click on arrows to change ranking rules order.', 'anwp-football-leagues' ),
			'red'                                    => esc_html__( 'Red', 'anwp-football-leagues' ),
			'red_cards'                              => esc_html__( 'Red Cards', 'anwp-football-leagues' ),
			'remove_group'                           => esc_html__( 'Remove Group', 'anwp-football-leagues' ),
			'remove_only_empty_round'                => esc_html__( 'You can remove only empty Round. Delete all attached groups/pairs first.', 'anwp-football-leagues' ),
			'result'                                 => esc_html__( 'Result', 'anwp-football-leagues' ),
			'round'                                  => esc_html__( 'Round', 'anwp-football-leagues' ),
			'round_robin'                            => esc_html__( 'Round Robin', 'anwp-football-leagues' ),
			'round_robin_format'                     => esc_html__( 'Round-Robin Format', 'anwp-football-leagues' ),
			'save_changes'                           => esc_html__( 'Save changes', 'anwp-football-leagues' ),
			'save_event'                             => esc_html__( 'Save Event', 'anwp-football-leagues' ),
			'save_squad'                             => esc_html__( 'Save Squad', 'anwp-football-leagues' ),
			'save'                                   => esc_html__( 'Save', 'anwp-football-leagues' ),
			'save_continue'                          => esc_html__( 'Save & Continue', 'anwp-football-leagues' ),
			'season'                                 => esc_html__( 'Season', 'anwp-football-leagues' ),
			'select'                                 => esc_html__( 'Select', 'anwp-football-leagues' ),
			'select_and_continue'                    => esc_html__( 'Select and Continue', 'anwp-football-leagues' ),
			'select_club'                            => esc_html__( 'Select club', 'anwp-football-leagues' ),
			'select_coach'                           => esc_html__( '- select coach -', 'anwp-football-leagues' ),
			'select_competition_clubs'               => esc_html__( 'Select Competition & Clubs', 'anwp-football-leagues' ),
			'select_competition_group'               => esc_html__( 'Select Competition Group', 'anwp-football-leagues' ),
			'select_date'                            => esc_html__( 'Select date', 'anwp-football-leagues' ),
			'select_event'                           => esc_html__( 'Select event', 'anwp-football-leagues' ),
			'select_reason'                          => esc_html__( 'Select reason', 'anwp-football-leagues' ),
			'select_home_away_first'                 => esc_html__( 'Select Home and Away Clubs first.', 'anwp-football-leagues' ),
			'kickoff_time'                           => esc_html__( 'Kick off time', 'anwp-football-leagues' ),
			'select_rules_from_list'                 => esc_html__( 'Select appropriate rules from the list below.', 'anwp-football-leagues' ),
			'select_season'                          => esc_html__( '- select season -', 'anwp-football-leagues' ),
			'select_stadium'                         => esc_html__( '- select stadium -', 'anwp-football-leagues' ),
			'select_stage'                           => esc_html__( '- select stage -', 'anwp-football-leagues' ),
			'select_two_teams_only'                  => esc_html__( 'Select two teams only.', 'anwp-football-leagues' ),
			'set_default'                            => esc_html__( 'set default', 'anwp-football-leagues' ),
			'set_stat_only_finished'                 => esc_html__( 'You can set statistics only for finished matches.', 'anwp-football-leagues' ),
			'shootout_scored'                        => esc_html__( 'Scored', 'anwp-football-leagues' ),
			'shots'                                  => esc_html__( 'Shots', 'anwp-football-leagues' ),
			'show_all_clubs_ignoring_structure'      => esc_html__( 'Show all clubs ignoring group structure (not recommended)', 'anwp-football-leagues' ),
			'shots_on_goal'                          => esc_html__( 'Shots on Goal', 'anwp-football-leagues' ),
			'single'                                 => esc_html_x( 'Single', 'round robin format', 'anwp-football-leagues' ),
			'single_leg_ties'                        => esc_html__( 'single-leg ties', 'anwp-football-leagues' ),
			'squad'                                  => esc_html__( 'squad', 'anwp-football-leagues' ),
			'special_status'                         => esc_html__( 'Special Status', 'anwp-football-leagues' ),
			'stadium'                                => esc_html__( 'Stadium', 'anwp-football-leagues' ),
			'stage_order'                            => esc_html__( 'Stage Order', 'anwp-football-leagues' ),
			'stage_title'                            => esc_html__( 'Stage Title', 'anwp-football-leagues' ),
			'standing_table'                         => esc_html__( 'Standing Table', 'anwp-football-leagues' ),
			'standing_table_colors'                  => esc_html__( 'Standing Table Colors', 'anwp-football-leagues' ),
			'start_typing_name'                      => esc_html__( '... start typing name', 'anwp-football-leagues' ),
			'stats'                                  => esc_html__( 'STATS', 'anwp-football-leagues' ),
			'status'                                 => esc_html__( 'Status', 'anwp-football-leagues' ),
			'step'                                   => esc_html__( 'Step', 'anwp-football-leagues' ),
			'structure'                              => esc_html__( 'Structure', 'anwp-football-leagues' ),
			'substitute'                             => esc_html__( 'Substitute', 'anwp-football-leagues' ),
			'substitutes'                            => esc_html__( 'Substitutes', 'anwp-football-leagues' ),
			'suspended'                              => esc_html__( 'Suspended', 'anwp-football-leagues' ),
			'tie'                                    => esc_html__( 'Tie', 'anwp-football-leagues' ),
			'ties'                                   => esc_html__( 'Ties', 'anwp-football-leagues' ),
			'tips'                                   => esc_html__( 'Tips', 'anwp-football-leagues' ),
			'toggle'                                 => esc_html__( 'Toggle', 'anwp-football-leagues' ),
			'tutorial'                               => esc_html__( 'Tutorial', 'anwp-football-leagues' ),
			'two_legged_ties'                        => esc_html__( 'two-legged ties', 'anwp-football-leagues' ),
			'use_batch_import_tool'                  => esc_html__( 'Use Batch import tool for fast Clubs creation', 'anwp-football-leagues' ),
			'use_separate_group'                     => esc_html__( 'Use separate group', 'anwp-football-leagues' ),
			'want_to_delete_round'                   => esc_html__( 'Do you really want to delete Round?', 'anwp-football-leagues' ),
			'want_to_delete_event'                   => esc_html__( 'Do you really want to delete Event?', 'anwp-football-leagues' ),
			'want_to_delete_sideline'                => esc_html__( 'Do you really want to delete Missing Player?', 'anwp-football-leagues' ),
			'wins'                                   => esc_html__( 'Wins', 'anwp-football-leagues' ),
			'won'                                    => esc_html__( 'Won', 'anwp-football-leagues' ),
			'yellow'                                 => esc_html__( 'Yellow', 'anwp-football-leagues' ),
			'yellow_cards'                           => esc_html__( 'Yellow Cards', 'anwp-football-leagues' ),
			'yellow_red'                             => esc_html__( '2nd Yellow > Red', 'anwp-football-leagues' ),
			'yellow_reds'                            => esc_html__( '2d Yellow > Red Cards', 'anwp-football-leagues' ),
			'yes'                                    => esc_html__( 'Yes', 'anwp-football-leagues' ),
		];
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  0.2.0 (2018-01-05)
	 *
	 * @param  string $field Field to get.
	 *
	 * @throws Exception     Throws an exception if the field is invalid.
	 * @return mixed         Value of the field.
	 */
	public function __get( $field ) {

		if ( property_exists( $this, $field ) ) {
			return $this->$field;
		}

		throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
	}

	public function cb_get_countries() {
		return $this->countries;
	}

	public function get_positions() {
		return $this->positions;
	}

	public function get_standing_headers() {
		return [
			'played' => [
				'tooltip' => AnWPFL_Options::get_value( 'text_standing_played_tooltip', __( 'Matches played', 'anwp-football-leagues' ) ),
				'text'    => AnWPFL_Options::get_value( 'text_standing_played_column', _x( 'M', 'Matches played - on standing page', 'anwp-football-leagues' ) ),
			],
			'won'    => [
				'tooltip' => AnWPFL_Options::get_value( 'text_standing_won_tooltip', __( 'Won', 'anwp-football-leagues' ) ),
				'text'    => AnWPFL_Options::get_value( 'text_standing_won_column', _x( 'W', 'Won - on standing page', 'anwp-football-leagues' ) ),
			],
			'drawn'  => [
				'tooltip' => AnWPFL_Options::get_value( 'text_standing_drawn_tooltip', __( 'Drawn', 'anwp-football-leagues' ) ),
				'text'    => AnWPFL_Options::get_value( 'text_standing_drawn_column', _x( 'D', 'Drawn', 'anwp-football-leagues' ) ),
			],
			'lost'   => [
				'tooltip' => AnWPFL_Options::get_value( 'text_standing_lost_tooltip', __( 'Lost', 'anwp-football-leagues' ) ),
				'text'    => AnWPFL_Options::get_value( 'text_standing_lost_column', _x( 'L', 'Lost - on standing page', 'anwp-football-leagues' ) ),
			],
			'gf'     => [
				'tooltip' => AnWPFL_Options::get_value( 'text_standing_gf_tooltip', __( 'Goals for', 'anwp-football-leagues' ) ),
				'text'    => AnWPFL_Options::get_value( 'text_standing_gf_column', __( 'GF', 'anwp-football-leagues' ) ),
			],
			'ga'     => [
				'tooltip' => AnWPFL_Options::get_value( 'text_standing_ga_tooltip', __( 'Goals against', 'anwp-football-leagues' ) ),
				'text'    => AnWPFL_Options::get_value( 'text_standing_ga_column', __( 'GA', 'anwp-football-leagues' ) ),
			],
			'gd'     => [
				'tooltip' => AnWPFL_Options::get_value( 'text_standing_gd_tooltip', __( 'Goal difference', 'anwp-football-leagues' ) ),
				'text'    => AnWPFL_Options::get_value( 'text_standing_gd_column', __( 'GD', 'anwp-football-leagues' ) ),
			],
			'points' => [
				'tooltip' => AnWPFL_Options::get_value( 'text_standing_points_tooltip', __( 'Points', 'anwp-football-leagues' ) ),
				'text'    => AnWPFL_Options::get_value( 'text_standing_points_column', _x( 'Pt', 'Points - on standing page', 'anwp-football-leagues' ) ),
			],
		];
	}

	public function get_positions_plural() {
		return $this->positions_plural;
	}

	/**
	 * Getter for array of series (form) letters.
	 *
	 * @return array
	 * @since 0.5.5
	 */
	public function get_series() {
		return $this->series;
	}

	/**
	 * Getter for array of admin localization strings.
	 *
	 * @return array
	 * @since 0.5.5
	 */
	public function get_l10n_admin() {
		return $this->admin_l10n;
	}

	public function get_value_by_key( $key, $context ) {

		switch ( $context ) {
			case 'position':
				return empty( $this->positions[ $key ] ) ? '' : $this->positions[ $key ];

			case 'country':
				return empty( $this->countries[ $key ] ) ? '' : $this->countries[ $key ];
		}

		return '';
	}

	/**
	 * Get player event icons.
	 *
	 * @return array
	 * @since 0.6.1
	 */
	public function get_event_icons() {

		return [
			'goal'         => '<svg class="icon__ball icon--lineups"><use xlink:href="#icon-ball"></use></svg>',
			'goal_own'     => '<svg class="icon__ball icon__ball--own icon--lineups"><use xlink:href="#icon-ball"></use></svg>',
			'goal_penalty' => '<svg class="icon__ball icon--lineups"><use xlink:href="#icon-ball_penalty"></use></svg>',
			'subs_in'      => '<svg class="icon__subs-in icon--lineups"><use xlink:href="#icon-arrow-o-up"></use></svg>',
			'subs_out'     => '<svg class="icon__subs-out icon--lineups"><use xlink:href="#icon-arrow-o-down"></use></svg>',
			'card_y'       => '<svg class="icon__card icon--lineups"><use xlink:href="#icon-card_y"></use></svg>',
			'card_r'       => '<svg class="icon__card icon--lineups"><use xlink:href="#icon-card_r"></use></svg>',
			'card_yr'      => '<svg class="icon__card icon--lineups"><use xlink:href="#icon-card_yr"></use></svg>',
		];
	}

	/**
	 * Get localized positions for players.
	 *
	 * @return array
	 * @since 0.6.4
	 */
	public function get_positions_l10n() {

		return [
			'g' => esc_html_x( 'g', 'goalkeeper - player position Letter', 'anwp-football-leagues' ),
			'd' => esc_html_x( 'd', 'defender - player position Letter', 'anwp-football-leagues' ),
			'm' => esc_html_x( 'm', 'midfielder - player position Letter', 'anwp-football-leagues' ),
			'f' => esc_html_x( 'f', 'forward - player position Letter', 'anwp-football-leagues' ),
		];
	}

	/**
	 * Get import options data.
	 *
	 * @return array
	 * @since 0.8.2
	 */
	public function get_import_options() {
		$options = [];

		// Positions
		foreach ( $this->get_positions() as $key => $text ) {
			$options['positions'][] = mb_strtolower( $text );
		}

		// Countries
		$options['countries'] = array_values( $this->cb_get_countries() );

		// Clubs
		$options['clubs'] = array_values( $this->plugin->club->get_clubs_options() );

		$options['rest_root']  = esc_url_raw( rest_url() );
		$options['rest_nonce'] = wp_create_nonce( 'wp_rest' );

		return $options;
	}
}
