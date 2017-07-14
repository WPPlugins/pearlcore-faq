<?php
/**
 * The Template for displaying Faq 
 *
 *
 * @author 		pearlcore
 * @package 	Pearlcore_Faq/Templates
 * @version     2.3
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;
$pc_delete = isset($pc_delete) ? $pc_delete : '';
$pc_class = isset($pc_class) ? $pc_class : '';
$pc_show_likes = isset($pc_show_likes) ? $pc_show_likes : '';
$pc_date_show = isset($pc_date_show) ? $pc_date_show : '';
$pc_author_show = isset($pc_author_show) ? $pc_author_show : '';
$pc_title = isset($pc_title) ? $pc_title : 'pc_title';
$query_args = isset($query_args) ? $query_args : '';
$pc_table_name = $wpdb->prefix . 'pc_like_counts';
$pc_user_ip = get_faq_client_ip();
$pc_posts = new WP_Query($query_args);
$pc_faq_count = 0;
?>
<div class="pc_faq_wrapper theme-a" id="theme-a">
    <?php if (isset($pc_delete) && ($pc_delete == '' || $pc_delete == null)): ?>
        <div class="pc_faq_title"><?php echo$pc_title; ?></div>
    <?php endif; ?>
    <section class="cd-faq">
        <div class="cd-faq-items">
            <ul id="basics" class="cd-faq-group">
                <?php if ($pc_posts->have_posts()): ?>
                    <?php while ($pc_posts->have_posts()) : $pc_posts->the_post(); ?>
                        <?php
                        $pc_id = $pc_posts->post->ID;
                        $pc_count = $wpdb->get_results("SELECT * FROM $pc_table_name WHERE post_id = '$pc_id' AND user_ip = '$pc_user_ip'");
                        if ($pc_count):
                            $pc_count_class = 'pc_faq_dislike';
                        else:
                            $pc_count_class = 'pc_faq_like';
                        endif;
                        $pc_faq_count++;
                        $pc_question = $pc_posts->post->post_title;
                        $pc_answer = $pc_posts->post->post_content;
                        $pc_date = get_the_date('d M Y', $pc_id);
                        $pc_author = get_post_meta($pc_id, 'pc_faq_author_name', true);
                        if (!$pc_author):
                            $pc_author_link = get_author_posts_url(get_the_author_meta('ID'));
                            $pc_author = '<a href="' . $pc_author_link . '">' . get_the_author() . '</a>';
                        endif;

                        $pc_post_like = get_post_meta($pc_id, 'pc_faq_like', true);
                        if (!$pc_post_like):
                            $pc_post_like = 0;
                        endif;
                        if ($pc_faq_count == 1):
                            $pc_faq_class = 'pc_faq_open';
                            $pc_faq_icon_class = 'fa fa-minus-circle';
                        else:
                            $pc_faq_class = '';
                            $pc_faq_icon_class = 'fa fa-plus-circle';
                        endif;
                        ?>
                        <li>
                            <a class="cd-faq-trigger" href="#0"><?php echo $pc_question; ?></a>
                            <div class="cd-faq-content">
                                <p> <?php echo $pc_answer; ?></p>
                            </div>

                            <?php if (isset($pc_delete) && $pc_delete != ''): ?>
                                <div class="pc_faq_delete_wrapper">
                                    <div class="pc_delete_button">
                                        <span class="pc_faq_delete" id="<?php echo $pc_id; ?>">Delete
                                        </span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="cd-faq-footer">
                                    <?php if (isset($pc_show_likes) && $pc_show_likes == 'yes'): ?>
                                        <div class="pc_faq_like_button">
                                            <span class="count"><?php echo $pc_post_like; ?></span>
                                            <span class="pc_faq_like_icon <?php echo $pc_count_class; ?>" post_id=" <?php echo $pc_id; ?>" pc_type="<?php echo $pc_count_class; ?>">
                                                <i class="fa fa-thumbs-o-up"></i>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($pc_date_show == 'yes' || $pc_author_show == 'yes'): ?>
                                        <div class="pc_faq_author">
                                            <?php if ($pc_date_show == 'yes'): ?>
                                                <span class="pc_faq_date"><?php echo $pc_date; ?> </span>
                                            <?php endif; ?>
                                            <?php if ($pc_author_show == 'yes'): ?>
                                                <span class="pc_faq_author">, BY  <span class="pc_faq_author_name"><?php echo $pc_author; ?></span></span>
                                                <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        </li>

                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else: ?>
                    <p>There is No FAQ yet.</p>
                <?php endif; ?>
            </ul>
        </div>
    </section>
</div>