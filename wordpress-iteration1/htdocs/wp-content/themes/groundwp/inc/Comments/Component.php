<?php
/**
 * GroundWP\GroundWP\Comments\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Comments;

use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use function GroundWP\GroundWP\groundwp;
use function add_action;
use function is_singular;
use function comments_open;
use function get_option;
use function wp_enqueue_script;
use function the_ID;
use function esc_attr;
use function wp_list_comments;
use function the_comments_navigation;
use function add_filter;
use function remove_filter;

/**
 * Class for managing comments UI.
 *
 * Exposes template tags:
 * * `groundwp()->the_comments( array $args = [] )`
 *
 * @link https://wordpress.org/plugins/amp/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'comments';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_comment_reply_script' ] );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `groundwp()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() {
		return [
			'the_comments' => [ $this, 'the_comments' ],
		];
	}

	/**
	 * Enqueues the WordPress core 'comment-reply' script as necessary.
	 */
	public function action_enqueue_comment_reply_script() {

		// If the AMP plugin is active, return early.
		if ( groundwp()->is_amp() ) {
			return;
		}

		// Enqueue comment script on singular post/page views only.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Displays the list of comments for the current post.
	 *
	 * Internally this method calls `wp_list_comments()`. However, in addition to that it will render the wrapping
	 * element for the list, so that must not be added manually. The method will also take care of generating the
	 * necessary markup if amp-live-list should be used for comments.
	 *
	 * @param array $args Optional. Array of arguments. See `wp_list_comments()` documentation for a list of supported
	 *                    arguments.
	 */
	public function the_comments( array $args = [] ) {
		$args = array_merge(
			$args,
			[
				'style'      => 'ol',
				'short_ping' => true,
			]
		);

		$amp_live_list = groundwp()->using_amp_live_list_comments();

		if ( $amp_live_list ) {
			$comment_order     = get_option( 'comment_order' );
			$comments_per_page = get_option( 'page_comments' ) ? (int) get_option( 'comments_per_page' ) : 10000;
			$poll_inverval     = MINUTE_IN_SECONDS * 1000;

			?>
			<amp-live-list
				id="amp-live-comments-list-<?php the_ID(); ?>"
				<?php echo ( 'asc' === $comment_order ) ? ' sort="ascending" ' : ''; ?>
				data-poll-interval="<?php echo esc_attr( $poll_inverval ); ?>"
				data-max-items-per-page="<?php echo esc_attr( $comments_per_page ); ?>"
			>
			<?php

			add_filter( 'navigation_markup_template', [ $this, 'filter_add_amp_live_list_pagination_attribute' ] );
		}

		?>
		<ol class="comment-list"<?php echo $amp_live_list ? ' items' : ''; ?>>
			<?php wp_list_comments( $args ); ?>
		</ol><!-- .comment-list -->
		<?php

		the_comments_navigation();

		if ( $amp_live_list ) {
			remove_filter( 'navigation_markup_template', [ $this, 'filter_add_amp_live_list_pagination_attribute' ] );

			?>
				<div update>
					<button class="button" on="tap:amp-live-comments-list-<?php the_ID(); ?>.update"><?php \esc_html_e( 'New comment(s)', 'groundwp' ); ?></button>
				</div>
			</amp-live-list>
			<?php
		}
	}

	/**
	 * Adds a pagination reference point attribute for amp-live-list when theme supports AMP.
	 *
	 * This is used by the navigation_markup_template filter in the comments template.
	 *
	 * @link https://www.ampproject.org/docs/reference/components/amp-live-list#pagination
	 *
	 * @param string $markup Navigation markup.
	 * @return string Filtered markup.
	 */
	public function filter_add_amp_live_list_pagination_attribute( $markup ) {
		return preg_replace( '/(\s*<[a-z0-9_-]+)/i', '$1 pagination ', $markup, 1 );
	}
}
