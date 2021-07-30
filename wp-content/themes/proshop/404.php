<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area inner-page text-center">

		<div class="col-full">

			<div class="error-404 not-found">

				<div class="page-content">

					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'storefront' ); ?></h1>
					</header><!-- .page-header -->

					<p><?php esc_html_e( 'Nothing was found at this location. Try searching, or check out the links below.', 'storefront' ); ?></p>

					<?php
					echo '<section aria-label="' . esc_html__( 'Search', 'storefront' ) . '">'; ?>

						<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?' ); ?></p>

					<?php echo '</section>'; ?>

				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</div>
	</div><!-- #primary -->

<?php
get_footer();
