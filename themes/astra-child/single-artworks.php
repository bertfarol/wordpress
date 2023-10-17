<?php get_header() ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="entry-content">
                    <p><strong>Artwork Type:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'artwork_type', true)); ?></p>
                    <p><strong>Dimension:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'dimension', true)); ?></p>
                    <p><strong>Artist Name:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'artist_name', true)); ?></p>
                </div>
            </article>
        <?php endwhile; ?>
    </main>
</div>

<?php get_footer() ?>