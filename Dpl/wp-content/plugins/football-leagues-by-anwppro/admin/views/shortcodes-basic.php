<?php
/**
 * Shortcodes page for AnWP Football Leagues
 *
 * @link       https://anwp.pro
 * @since      0.10.7
 *
 * @package    AnWP_Football_Leagues
 * @subpackage AnWP_Football_Leagues/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="anwp-b-wrap">
	<div class="inside p-3">
		<h1 class="mb-4">Available Shortcodes</h1>

		<div class="bg-info text-white py-1 px-3 mb-3 d-inline-block">Standing Table</div>
		<p><code class="border py-2 px-3 bg-light d-inline-block text-dark">[anwpfl-standing id="" title="" exclude_ids=""]</code></p>
		<ul class="list-unstyled">
			<li><strong>id</strong> – (required) standing table ID</li>
			<li><strong>title</strong> – (optional) text title for this shortcode</li>
			<li><strong>exclude_ids</strong> – list of comma-separated club ID’s to exclude</li>
		</ul>
		<hr>
		<div class="bg-info text-white py-1 px-3 mb-3 d-inline-block">Clubs</div>
		<p>
			<code class="border py-2 px-3 bg-light d-inline-block text-dark">[anwpfl-clubs competition_id="" logo_size="big" logo_height="50px" logo_width="50px" exclude_ids=""]</code>
		</p>
		<ul class="list-unstyled">
			<li><strong>competition_id</strong> – (required) competition ID</li>
			<li><strong>logo_size</strong> – (required) “big” or “small” – logo size from appropriate club’s logo field</li>
			<li><strong>logo_height</strong> – (required) Height value with units. Example: “50px” or “3rem”.</li>
			<li><strong>logo_width</strong> – (required) Width value with units. Example: “50px” or “3rem”.</li>
			<li><strong>exclude_ids</strong> – list of comma separated club ID’s to exclude</li>
			<li><strong>layout</strong> – ‘2col’, ‘3col’, ‘4col’, ‘6col’ or ” (empty). If empty – applied ‘logo_height’ and ‘logo_width’</li>
		</ul>
		<hr>
		<div class="bg-info text-white py-1 px-3 mb-3 d-inline-block">Match</div>
		<p>
			<code class="border d-inline-block py-2 px-3 bg-light text-dark">[anwpfl-match match_id="" layout="" sections="goals,cards,line_ups,substitutes,stats,missed_penalties,summary"]</code>
		</p>
		<ul class="list-unstyled">
			<li><strong>match_id</strong> – match ID</li>
			<li><strong>club_last</strong> – (club ID) Last finished match of the club</li>
			<li><strong>club_next</strong> – (club ID) Next upcoming match of the club</li>
			<li><strong>layout</strong> – ‘slim’ or ”(empty for default)</li>
			<li>
				<strong>sections</strong> – Comma separated list of sections. Available options: “goals”, “cards”, “line_ups”, “substitutes”, “stats”, “missed_penalties”, “summary”, "missing", "penalty_shootout".
			</li>
		</ul>
		<hr>
		<div class="bg-info text-white py-1 px-3 mb-3 d-inline-block">Matches</div>
		<p>
			<code class="border d-inline-block py-2 px-3 bg-light text-dark">[anwpfl-matches competition_id="" show_secondary="" type="" limit="0" filter_by="" filter_values="" group_by="" sort_by_date=""]</code>
		</p>
		<ul class="list-unstyled">
			<li><strong>competition_id</strong> – competition ID</li>
			<li>
				<strong>show_secondary</strong> – 0 or 1. Include matches from secondary stages. Works for multistage competitions only. You should set main stage ID in “competition_id” parameter.
			</li>
			<li><strong>sort_by_matchweek</strong> – ‘asc’ or ‘desc’. Set priority sorting by matchweeks. Default: ”</li>
			<li><strong>competition_logo</strong> – “0” or “1”. Default: “1”</li>
			<li><strong>type</strong> – “result” or “fixture” – show finished or future matches. Empty to show all. Default value is “”.</li>
			<li><strong>limit</strong> – limit number of matches. 0 for all. Default: 0</li>
			<li><strong>filter_by</strong> – “club” or “matchweek”</li>
			<li><strong>filter_values</strong> – Comma separated list of options. Club ID’s or Round numbers.</li>
			<li><strong>group_by</strong> – Available options: “day”, “month”, “matchweek”, “stage”. Stage works only if show_secondary is set to 1.</li>
			<li><strong>sort_by_date</strong> – Available options: “asc” or “desc”. Show oldest or latest matches. Default: “”.</li>
			<li><strong>show_club_logos</strong> – “0” or “1”. Default: “1”</li>
			<li><strong>show_match_datetime</strong> – “0” or “1”. Default: “1”</li>
			<li><strong>club_links</strong> – “0” or “1”. Default: “1”</li>
			<li><strong>group_by_header_style</strong> – “” or “secondary”. Default: “”</li>
			<li><strong>season_id</strong> – anwp_season term_id.</li>
			<li><strong>stadium_id</strong> – filter by stadium (if not empty)</li>
			<li><strong>date_from</strong> – (v0.10.3) matches from date. Format: YYYY-MM-DD. Default: ”</li>
			<li><strong>date_to</strong> – (v0.10.3) matches before date. Format: YYYY-MM-DD. Default: ”</li>
		</ul>
		<hr>
		<div class="bg-info text-white py-1 px-3 mb-3 d-inline-block">Squad</div>
		<p><code class="border py-2 px-3 bg-light d-inline-block text-dark">[anwpfl-squad season_id="" club_id=""]</code></p>
		<hr>
		<div class="bg-info text-white py-1 px-3 mb-3 d-inline-block">Competition Header Block</div>
		<p><code class="border py-2 px-3 bg-light d-inline-block text-dark">[anwpfl-competition-header id="" title_as_link="0"]</code></p>
		<ul class="list-unstyled">
			<li><strong>id</strong> – (required) competition ID</li>
			<li><strong>title_as_link</strong> – ‘0’ or ‘1’. Default: 0.</li>
		</ul>
		<hr>
		<div class="bg-info text-white py-1 px-3 mb-3 d-inline-block">Players</div>
		<p><code class="border py-2 px-3 bg-light d-inline-block text-dark">[anwpfl-players ]</code></p>
		<ul class="list-unstyled">
			<li><strong>type</strong> – ‘scorers’ or ‘assists’</li>
			<li><strong>competition_id</strong> – competition ID</li>
			<li>
				<strong>join_secondary</strong> – 0 or 1. Include stats from secondary stages. Works for multistage competitions only. You should set main stage ID in “competition_id” parameter.
			</li>
			<li><strong>season_id</strong> – season ID</li>
			<li><strong>league_id</strong> – league ID</li>
			<li><strong>club_id</strong> – club ID</li>
			<li><strong>limit</strong> – limit number. Default: 0</li>
			<li><strong>soft_limit</strong> – ‘no’ or ‘yes’. Default: yes. Increase number of players to the end of players with equal stats value.</li>
			<li><strong>show_photo</strong> – ‘no’ or ‘yes’. Default: yes.</li>
			<li><strong>compact</strong> – 0 or 1. Default: 0.</li>
			<li><strong>layout</strong> – ”, ‘small’, ‘mini’. Mini is used for widget.</li>
		</ul>
		<hr>
		<div class="bg-info text-white py-1 px-3 mb-3 d-inline-block">Cards</div>

		<p>Show list of clubs or players with cards (yellow, red)</p>
		<p>
			<code class="border py-2 px-3 bg-light d-inline-block text-dark">[anwpfl-cards competition_id="" show_secondary="0" season_id="" league_id="" club_id="" type="players" limit="0" soft_limit="yes" show_photo="yes" sort_by_point="" points_r="5" points_yr="2" hide_zero="1" layout=""]</code>
		</p>
		<ul class="list-unstyled">
			<li><strong>competition_id</strong> – competition ID</li>
			<li>
				<strong>show_secondary</strong> – 0 or 1. Include cards from secondary stages. Works for multistage competitions only. You should set main stage ID in “competition_id” parameter.
			</li>
			<li><strong>season_id</strong> – season ID (optional)</li>
			<li><strong>league_id</strong> – league ID (optional)</li>
			<li><strong>club_id</strong> – club ID. Filter by club if set. Works only for players. (optional)</li>
			<li><strong>type</strong> – ‘clubs’ or ‘players’</li>
			<li><strong>limit</strong> – limit number. Default: 0</li>
			<li><strong>soft_limit</strong> – ‘no’ or ‘yes’. Default: yes. Increase number of players to the end of players with equal stats value.</li>
			<li><strong>show_photo</strong> – ‘no’ or ‘yes’. Default: yes.</li>
			<li>
				<strong>sort_by_point</strong> – Available options: “asc” or “” (empty for descending sorting). Ascending is useful for “Fair Play” report. Default: “”.
			</li>
			<li><strong>points_r</strong> – points for the Red card. Default: 5.</li>
			<li><strong>points_yr</strong> – points for the 2-d Yellow &gt; Red card. Default: 2.</li>
			<li><strong>hide_zero</strong> – ‘1’ or ‘0’. Default: 1. Hide items with zero points.</li>
			<li><strong>layout</strong> – “”, ‘mini’. Default: “”. Mini is used for widget.</li>
		</ul>
		<hr>
		<div class="bg-info text-white py-1 px-3 mb-3 d-inline-block">Player</div>
		<p>
			<code class="border py-2 px-3 bg-light d-inline-block text-dark">[anwpfl-player player_id="" options_text="" profile_link="" profile_link_text="" show_club=""]</code>
		</p>
		<ul class="list-unstyled">
			<li><strong>player_id</strong> – (required) player ID</li>
			<li><strong>show_club</strong>&nbsp;– 0 or 1.</li>
			<li><strong>profile_link</strong>&nbsp;– 0 or 1. Show link to player profile</li>
			<li><strong>profile_link_text</strong>&nbsp;– profile link text</li>
			<li><strong>options_text</strong>&nbsp;– Separate line by “|”, number and label – with “:”. E.q.: “Goals: 8 | Assists: 5”.</li>
		</ul>
	</div>
</div>
