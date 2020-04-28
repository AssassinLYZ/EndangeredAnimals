<?php
/**
 * Infinite scroll component template part
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

// TODO [erdembircan] maybe an indicator to show page bottom hit.
?>
<div id="groundwp-infinite-scroll">
	<groundwp-infinite-scroll-posts-container inline-template :url="ajaxUrl" :query="query">
		<div>
			<groundwp-infinite-scroll-post  v-for="p in posts" :date="p.date" inline-template>
				<article :id="'post-'+p.id"
						 class="entry card  card--2dp post type-post status-publish format-standard hentry category-block">
					<header class="entry-header">
						<div class="entry-meta">
			<span class="posted-on">
			<a :href="p.link" rel="bookmark">
				<time class="entry-date published updated"
					  :datetime="p.date">{{localTime}}</time></a></span>
						</div>
						<h2 class="entry-title"><a :href="p.link" rel="bookmark">{{p.title.rendered}}</a></h2></header>
					<div class="entry-summary">
						<div v-html="p.excerpt.rendered">
						</div>
					</div>
				</article>
			</groundwp-infinite-scroll-post>
			<div v-show="!disable" id="groundwp-infinite-scroll-landmark" style="display: flex; justify-content: center; width: 100%">
				<?php groundwp()->inline_svg( 'circular_load_icon_svg' ); ?>
			</div>
			<intersection-observer element="#groundwp-infinite-scroll-landmark"
								   @intersect="fetchPosts"></intersection-observer>
		</div>
	</groundwp-infinite-scroll-posts-container>

</div>
