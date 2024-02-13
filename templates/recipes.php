<?php
if( ! is_user_logged_in() ) {
    wp_redirect( esc_url( site_url('/') ) );
    exit;
}
get_header(); ?>

<div class="container">
    <div>
        <div class="create-note">
            <h2>Add Your Recipe</h2>
            <input type="text" class="new-note-title" placeholder="Title">
            <textarea class="new-note-body"  placeholder="Type your recipe here..."></textarea>
            <span class="submit-note"><?php esc_html_e('Submit', 'wj-recipes') ?></span>
            <!--<input type="file" class="recipe_image" name="recipe_image" id="recipe_image">-->
            <!--<span class="note-limit-message">Sorry! Recipe limit reached!</span>-->
            <span class="note-limit-message">Title can't be left empty!</span>
        </div>
        <ul class="min-list link-list" id="my-notes">
            <?php
            $myRecipes = new WP_Query( array(
                'post_type' => 'wj-recipes',
                'posts_per_page' => -1,
                'author' => get_current_user_id()
            ) );
            while( $myRecipes->have_posts() ) : $myRecipes->the_post();
            ?>
            <li data-id="<?php the_ID() ?>">
                <input readonly class="note-title-field" type="text" value="<?php echo esc_attr( get_the_title() ); ?>">
                <span class="edit-note"><i class="fa fa-pencil"></i> Edit</span>
                <span class="delete-note"><i class="fa fa-trash-o"></i> Delete</span>
                <textarea readonly class="note-body-field" name="" id=""><?php echo esc_html( get_the_content() ); ?></textarea>
                <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right"></i> Save</span>
            </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<?php get_footer(); ?>