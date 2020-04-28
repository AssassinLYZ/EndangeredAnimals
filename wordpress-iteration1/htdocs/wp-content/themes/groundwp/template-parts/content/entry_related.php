<?php
/**
 * Related-posts component template part
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

?>
<div id="related-posts">
	<related-posts-component inline-template :data-obj="dataObj">
		<intersection-observer inline-template element="#related-posts" @intersect="intersect">
			<div v-if="masterVisibility" class="entry card card--2dp" id="wrapper">
				<header>
					<h2 class="entry-header">{{dataObj.settings.title}}</h2>
				</header>
				<div id="related-posts" class="related-posts-wrapper">
					<div class="related-posts-fetching" v-show="fetching">
							<?php groundwp()->inline_svg( 'circular_load_icon_svg' ); ?>
					</div>
					<related-post v-for="post in posts" :key="post.id" :link="post.link" :title="post.title.rendered" :date="post.date"
								  :excerpt="post.groundwp_related_posts.excerpt" :id="post.id" inline-template>
						<article :id="parsedId" @click="followLink">
							<header class="entry-header">
								<div class="entry-meta">
					<span v-if="dataObj.settings.showDate" class="posted-on">
						<a rel="bookmark" :href="link">
							<time class="entry-date published updated" :datetime="date">
								{{displayDate}}
							</time>
						</a>
					</span>
								</div>
								<h4 class="entry-title">{{title}}</h4>
							</header>
							<div v-if="dataObj.settings.showExcerpt" class="entry-content" v-html="excerpt">
							</div>
						</article>
					</related-post>
				</div>
			</div>
		</intersection-observer>
	</related-posts-component>
</div>

